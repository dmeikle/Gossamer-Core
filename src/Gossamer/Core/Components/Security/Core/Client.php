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
 * Date: 3/26/2017
 * Time: 4:00 PM
 */

namespace Gossamer\Core\Components\Security\Core;



use Gossamer\Core\Components\Security\Core\ClientInterface;

/**
 * Client class used for storing user context data
 *
 * @author Dave Meikle
 */
class Client implements ClientInterface {

    protected $id;
    protected $password = null;
    protected $roles = array();
    protected $credentials = 'anonymous';
    protected $ipAddress = null;
    protected $status = null;
    protected $email = null;
    /**
     *
     * @param array $params
     */
    public function __construct(array $params = array()) {

        if (count($params) > 0) {

//            $this->password = $params['password'];
            $this->password = (array_key_exists('password', $params)) ? $params['password'] : '';
            $this->id = (array_key_exists('id', $params)) ? $params['id'] : '';

//            $this->password = ( array_key_exists('StaffAuthorization', $params) &&
//                array_key_exists('password', $params['StaffAuthorization'])) ? $params['StaffAuthorization']['password'] : '';
            //$this->status = $params['status'];
            $this->status = (array_key_exists('status', $params)) ? $params['status'] : '';
//            $this->roles = explode('|', $params['roles']);
            $this->roles = (array_key_exists('roles', $params)) ? $params['roles'] : '';

            $username = (array_key_exists('username', $params)) ? $params['username'] : '';

            $this->credentials = (array_key_exists('credentials', $params)) ? $params['credentials'] : $username;
            $this->ipAddress = (array_key_exists('ipAddress', $params)) ? $params['ipAddress'] : '';
            //$this->id = (array_key_exists('Staff_id', $params)) ? $params['Staff_id'] : '';
            $this->email = (array_key_exists('email', $params)) ? $params['email'] : null;

            if (array_key_exists('Contacts_id', $params)) {
                $this->id = $params['Contacts_id'];
            } elseif (array_key_exists('User_id', $params)) {
                $this->id = $params['User_id'];
            }elseif (array_key_exists('Members_id', $params)) {
                $this->id = $params['Members_id'];
            }elseif (array_key_exists('memberID', $params)) {
                $this->id = $params['memberID'];
            }
        }
    }

    /**
     * accessor
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * accessor
     *
     * @param array $roles
     */
    public function setRoles(array $roles) {
        $this->roles = $roles;
    }

    /**
     * accessor
     *
     * @param string $credentials
     */
    public function setCredentials($credentials) {
        $this->credentials = $credentials;
    }

    /**
     * accessor
     *
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress) {
        $this->ipAddress = $ipAddress;
    }

    /**
     * accessor
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * accessor
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * accessor
     *
     * @return array
     */
    public function getRoles() {
        if (is_array($this->roles)) {
            return $this->roles;
        }

        return explode('|', $this->roles);
    }

    /**
     * accessor
     *
     * @return string
     */
    public function getCredentials() {
        return $this->credentials;
    }

    /**
     * accessor
     *
     * @return string
     */
    public function getIpAddress() {
        return $this->ipAddress;
    }

    /**
     * accessor
     *
     * @return string
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * accessor
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * accessor
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * accessor
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

}
