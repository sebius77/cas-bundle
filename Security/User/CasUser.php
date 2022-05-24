<?php
// src/CasBundle/Security/User/CasUser.php

namespace Sebius77\CasAuthBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

class CasUser implements UserInterface
{
    private $uid;
    private $roles = [];

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid($uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return mixed
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     *
     */
    public function eraseCredentials()
    {

    }
}
