<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements UserInterface, \Serializable{

    /**
     * @ORM\Id
     * @ORM\Column(name="user_name", type="string", length=255, nullable=false)
     */
    private $userName;

    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    function __toString() {
        return $this->userName;
    }

    function getUserName() {
        return $this->userName;
    }

    function getPassword() {
        return $this->password;
    }


    function getRole() {
        return $this->role;
    }

    function getEmail() {
        return $this->email;
    }

    function setUserName($userName) {
        $this->userName = $userName;
    }

    public function setPassword(String $password) {
        $this->password = password_hash($password, PASSWORD_BCRYPT, array("cost" => 13));
    }

    function setRole($role) {
        $this->role = $role;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return array($this->role);
    }

    public function serialize() {
        return serialize(array(
            $this->userName,
            $this->password
        ));
    }

    public function unserialize($serialized) {
        list (
                $this->userName,
                $this->password
                ) = unserialize($serialized);
    }
}
