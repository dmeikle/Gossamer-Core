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
 * Date: 3/8/2017
 * Time: 7:25 PM
 */

namespace Gossamer\Core\Services;

use Gossamer\Neith\Logging\LoggingInterface;
use Gossamer\Set\Utils\Container;


/**
 * similar to an EventDispatcher, this class will create all services needed
 * for injection into any bootstrap or other services. This way a service starting
 * up can have any required objects created for it on the fly.
 *
 * @author Dave Meikle
 */
class ServiceDispatcher
{

    private $logger = null;
    private $config = array();
    private $key = null;
    private $nodeConfig;
    private $directoryList;
    private $container;


    /**
     *
     * @param Logger $logger
     * @param YAMLParser $parser
     */
    public function __construct(LoggingInterface $logger, array $directoryList, array $nodeConfig, Container $container)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
        //$this->loadKeyFromFirewallConfiguration($parser);
        $this->container = $container;
    }

    /**
     * load the configuration files
     *
     * @param YAMLParser $parser
     */
    private function loadConfigurations(YAMLParser $parser, array $nodeConfig)
    {

        // $subdirectories = $this->getDirectoryList();

        //first load the system service configurations
        $parser->setFilePath(__SITE_PATH . '/app/config/services.yml');
        $config = $parser->loadConfig();

        if (is_array($config)) {
            $this->config[] = $config;
        }
        $folder = str_replace('\\', DIRECTORY_SEPARATOR, $nodeConfig['namespace']) . DIRECTORY_SEPARATOR . $nodeConfig['componentFolder'];
        //now load all the component configurations
//        foreach ($subdirectories as $folder) {
//            $parser->setFilePath($folder . '/config/services.yml');
//            $config = $parser->loadConfig();
//
//            if (is_array($config)) {
//                $this->config[] = $config;
//            }
//        }

        $parser->setFilePath(__SITE_PATH . DIRECTORY_SEPARATOR . $folder . '/config/services.yml');
        $config = $parser->loadConfig();
        // file_put_contents(__DEBUG_OUTPUT_PATH, print_r($config, true), FILE_APPEND);
        if (is_array($config)) {
            $this->config[] = $config;
        }

        // file_put_contents(__DEBUG_OUTPUT_PATH, print_r($this->config, true), FILE_APPEND);

        //  file_put_contents(__DEBUG_OUTPUT_PATH,__SITE_PATH . DIRECTORY_SEPARATOR . $folder . '/config/services.yml'."\r\n", FILE_APPEND);

    }

//    private function loadKeyFromFirewallConfiguration(YAMLParser $parser)
//    {
//        file_put_contents(__DEBUG_OUTPUT_PATH, "loading firewall\r\n", FILE_APPEND);
//        $firewall = new Firewall($this->logger);
//        $this->key = $firewall->getFirewallKey($parser);
//        //echo "key ".$this->key.'<br>';
//        file_put_contents(__DEBUG_OUTPUT_PATH, $this->key . ": firewall complete\r\n", FILE_APPEND);
//        // die;
//        unset($firewall);
//    }

    public function dispatch(ServiceManager $serviceManager)
    {
        $serviceManager->executeService($this->key);
    }

}
