<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 *
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/15/2017
 * Time: 7:31 PM
 */

namespace Gossamer\Core\Components\Security\Handlers;

use Gossamer\Core\Configuration\YamlLoader;
use Gossamer\Core\Routing\URISectionComparator;
use Gossamer\Core\Services\ParametersInterface;
use Gossamer\Caching\CacheManager;
use Gossamer\Neith\Logging\LoggingInterface;
use Gossamer\Set\Utils\Container;


/**
 * this class handles all authentication when a user logs in. No need to create
 * a function inside a controller (don't waste time looking for it there, like
 * I sometimes forget a year later.. haha).
 *
 * Configuration is handled in the security.yml file
 * step 1:
 * create a manager that will be called during startup by the services manager.
 * This configuration is stored in the services.yml file.
 * eg:
 *
 * authentication_manager:
 * handler: 'Gossamer\Core\Components\security\Gossamer\CoreAuthenticationManager'
 * 'arguments':
 * - '@user_authentication_provider'
 * #the '@' sign means it's a service already configured.
 * #no '@' sign means you specify the relative path to the file to load
 * step 2:
 * create a provider that can be passed into the manager to do the work.
 * Different providers can be directed to perform differently based on
 * yml file configuration.
 * eg:
 *
 * user_authentication_provider:
 * handler: 'Gossamer\Core\Components\security\providers\UserAuthenticationProvider'
 * datasource: datasource3
 *
 * the services manager will create the UserAuthenticationProvider and pass in
 * the datasource specified by yml key. Then it will create the
 * AuthenticationManager and pass the provider into it. The work is done by
 * the provider (which database, which checks to perform) - the manager just
 * orchestrates the calls.
 *
 * step 3:
 * create a reference that will define the handler to use the manager and the
 * provider inside the services.yml file
 *
 * simple_auth:
 * 'handler': 'Gossamer\Core\Components\security\handlers\AuthenticationHandler'
 * #3 is the local db conn wrapped in a connection adapter
 * 'datasource': 'datasource3'
 * 'arguments':
 * security_context: '@security_context'
 * authentication_manager: '@authentication_manager'
 *
 * step 4:
 * create the rule that calls all of this in firewall.yml :
 *
 * admin_area:
 * pattern: /admin
 * authentication: simple_auth
 * fail_url: admin/login
 *
 *
 * in a nutshell:
 * 1.   create a provider and specify any passed in objects
 * 2.   create a manager and specify any passed in objects, including the provider
 *      to use in this context
 * 3.   create a handler and specify the manager to use
 * 4.   create a firewall reference and tell it which handler to call when the
 *      matching URI pattern occurs
 *
 * @author Dave Meikle
 */
class AuthenticationHandler extends \Gossamer\Ra\Security\Handlers\AuthenticationHandler implements ParametersInterface
{

    use \Gossamer\Core\Configuration\Traits\LoadConfigurationTrait;



    private $loader = null;

    const FIREWALL_CACHE_KEY = 'FIREWALL_RULES';

    /**
     *
     * @param Logger $logger
     */
    public function __construct(LoggingInterface $logger, Container $container, YamlLoader $loader) {
        $this->logger = $logger;
        $this->container = $container;
        $this->loader = $loader;

        $this->loadNodeConfig();
    }

    /**
     * main method called. calls the provider and gets the provider to
     * authenticate the user
     *
     * @return type
     */
    public function execute() {
echo "execute\r\n";
        $this->container->set('securityContext', $this->securityContext);
echo "in";
        if (is_null($this->node) || !array_key_exists('authentication', $this->node)) {
echo "returing";
            return;
        }
        echo "still here\r\n";
        if (array_key_exists('security', $this->node) && (!$this->node['security'] || $this->node['security'] == 'false')) {
            error_log('security element null or not found in node');
echo "leaving\r\n";
            return;
        }
echo "get token\r\n";
        $token = $this->getToken();
echo "got token\r\n";
        try {
echo "authenticate\r\n";
            $this->authenticationManager->authenticate($this->securityContext);

        } catch (\Exception $e) {
print_r($e->getMessage());

            if (array_key_exists('fail_url', $this->node)) {
                header('Location: ' . $this->container->get('HttpRequest')->getRequestParams()->getSiteUrl() . $this->node['fail_url']);
            } else {
                echo json_encode(array('message' => $e->getMessage(), 'code' => $e->getCode()));
            }

        }
echo "finished";
        die();
        //this is handled in the UserLoginManager
        //$this->container->set('securityContext', $this->securityContext);
    }

    /**
     * accessor
     *
     * @return string

    private function getSiteURL() {
     * $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
     * $domainName = $_SERVER['HTTP_HOST'] . '/';
     *
     * return $protocol . $domainName;
     * }
     */


    /**
     * accessor
     *
     * @param array $params
     */
    public function setParameters(array $params) {

        $this->securityContext = $params['security_context'];
        $this->authenticationManager = $params['authentication_manager'];
    }

    /**
     * accessor
     *
     * @return SecurityToken
     */
    protected function getToken() {

        $session = getSession();
echo "have session in gettoken\r\n";
        $token = $this->authenticationManager->generateEmptyToken($session);
echo "have token now\r\n";
        return $token;
    }

    /**
     * loads the firewall configuration
     *
     * @return empty|array
     */
    protected function loadNodeConfig() {

        $this->loader->setFilePath($this->container->get('HttpRequest')->getSiteParams()->getConfigPath() . 'firewall.yml');
        $config = $this->loader->loadConfig();
       
        $request = $this->container->get('HttpRequest');
        $parser = new URISectionComparator(new CacheManager($this->logger), $request);

        $key = $parser->findPattern($config, $this->container->get('HttpRequest')->getRequestParams()->getUri());
        unset($parser);

        if(empty($key)) {
            return;
        }

        $this->node = current($key);
    }

//    protected function createCachedFirewallRules() {
//        $this->loader->setFilePath($this->container->get('HttpRequest')->getSiteParams()->getConfigPath() . 'firewall.yml');
//        $config = $this->loader->loadConfig();
//        $parser = new URISectionComparator(new CacheManager($this->logger),$this->container->get('HttpRequest'));
//        $key = $parser->findPattern($config, $this->container->get('HttpRequest')->getRequestParams()->getUri());
//
//    }
    
}
