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
 * Time: 6:37 PM
 */

namespace Gossamer\Core\Components\Security\Providers;


use Gossamer\Core\Datasources\DatasourceInterface;
use Gossamer\Ra\Exceptions\ClientCredentialsNotFoundException;
use Gossamer\Ra\Security\Client;
use Gossamer\Ra\Security\ClientInterface;
use Gossamer\Ra\Security\Providers\AuthenticationProviderInterface;

class UserAuthenticationProvider implements AuthenticationProviderInterface, DatasourceInterface
{

    protected $connection;

    public function loadClientByCredentials($credential) {


       // throw new ClientCredentialsNotFoundException('no user found with credential ' . $credential);
    }

    public function refreshClient(ClientInterface $client) {
        // TODO: Implement refreshClient() method.
    }

    public function supportsClass($class) {
        // TODO: Implement supportsClass() method.
    }

    public function getRoles(ClientInterface $client) {
        // TODO: Implement getRoles() method.
    }


    public function setConnection($connection) {
        $this->connection = $connection;
    }
}