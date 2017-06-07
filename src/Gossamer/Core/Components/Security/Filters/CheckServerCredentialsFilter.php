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
 * Date: 3/3/2017
 * Time: 12:56 AM
 */

namespace Gossamer\Core\Components\Security\Filters;


use Gossamer\Horus\Filters\AbstractFilter;
use Gossamer\Horus\Filters\FilterChain;
use Gossamer\Horus\Http\HttpInterface;

class CheckServerCredentialsFilter extends AbstractFilter
{

    public function execute(HttpInterface $request, HttpInterface $response, FilterChain $chain) {

      //  throw new \Exception('test the error response', 405);
        return $chain->execute($request, $response, $chain);
    }
}