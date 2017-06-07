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
 * Date: 3/1/2017
 * Time: 8:34 PM
 */

namespace Gossamer\Core\Kernel;


use Detection\MobileDetect;
use Gossamer\Pesedget\Entities\EntityManager;

class BootstrapLoader
{

   use \Gossamer\Core\Configuration\Traits\LoadConfigurationTrait;

    
    /**
     * initialize method type based on HTTP_METHOD
     *
     * @param void
     *
     * @return void
     */
    private function initMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                return 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                return 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }
        
        return $method;
    }
    
    public function getRequestParams() {
        $requestParams = new \Gossamer\Core\Http\RequestParams();

        $requestParams->setHeaders(getallheaders());
        $requestParams->setPost($_POST);
        $requestParams->setQuerystring($_GET);
        $requestParams->setServer($_SERVER);
        $requestParams->setLayoutType($this->getLayoutType());
        $requestParams->setMethod($this->initMethod());
        $requestParams->setSiteURL($this->getSiteURL());
        
       // $requestParams->setUri()
        return $requestParams;
    }


    /**
     * determines if we are dealing with a computer or mobile device
     *
     * @return array
     */
    private function getLayoutType() {
        $detector = new MobileDetect();
        $isMobile = $detector->isMobile();
        $isTablet = $detector->isTablet();
        unset($detector);

        return array('isMobile' => $isMobile, 'isTablet' => $isTablet, 'isDesktop' => (!$isMobile && !$isTablet));
    }
    
    public function getEntityManager($configPath) {
        $config = $this->loadConfig($configPath . 'credentials.yml');
      
        return new EntityManager($config);
    }

    private function getSiteURL() {
         $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
         $domainName = $_SERVER['HTTP_HOST'] . '/';
        
         return $protocol . $domainName;
         }
}