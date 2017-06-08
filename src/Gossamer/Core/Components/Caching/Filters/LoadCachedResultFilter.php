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
 * Date: 3/6/2017
 * Time: 8:50 PM
 */

namespace Gossamer\Core\Components\Caching\Filters;


use Gossamer\Core\Http\HttpRequest;
use Gossamer\Caching\CacheManager;
use Gossamer\Horus\Filters\AbstractFilter;
use Gossamer\Horus\Filters\FilterChain;
use Gossamer\Horus\Http\HttpInterface;


class LoadCachedResultFilter extends AbstractFilter
{
    use \Gossamer\Core\Configuration\Traits\LoadConfigurationTrait;

    /**
     * @param HttpInterface $request
     * @param HttpInterface $response
     * @param FilterChain $chain
     * @return bool
     */
    public function execute(HttpInterface $request, HttpInterface $response, FilterChain $chain) {

        if(!$request instanceof HttpRequest) {
            throw new \Exception('Must be an instance of HttpRequest');
        }

        try{
            $result = $this->loadCachedResult($request);
        }catch(\Exception $e) {
            echo $e->getMessage();
        }

            if($result !== false) {
                //found it in cache - drop out now, no need to process request
                $response->setAttribute(FilterChain::IMMEDIATE_WRITE, $result);

                return true;
            }
            

        //not a cached page - continue with filter chain
        try {
            return $chain->execute($request, $response, $chain);
        } catch (\Exception $e) {

        }
    }

    /**
     * @param HttpRequest $httpRequest
     * @return bool
     */
    private function loadCachedResult(HttpRequest $httpRequest) {
        $cacheManager = new CacheManager();
        $cacheManager->setHttpRequest($httpRequest);

       return $cacheManager->retrieveFromCache($this->getKey($httpRequest));
    }

    /**
     * @param $uri
     * @param array $config
     * @return mixed

    private function getKey($uri, array $config) {
        $salt = $config['salt'];

        return hash($salt, $uri);
    }
*/
    protected function getKey(HttpRequest $request) {
        return md5($request->getRequestParams()->getServer('REQUEST_URI') . $request->getRequestParams()->getQueryString(false));
        //return hash($salt, $this->request->getRequestParams()->getUri());
    }

}