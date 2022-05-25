<?php
// src/CasBundle/Security/User/CasUser.php

namespace Sebius77\CasBundle\Security\User;

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
       return $this->roles;
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
        return null;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getUsername(): string
    {
        return (string) $this->uid;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->uid;
    }

    /**
     *
     */
    public function eraseCredentials()
    {

    }
}
