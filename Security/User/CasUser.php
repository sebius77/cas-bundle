<?php
/*
      Copyright 2026 CAS-BUNDLE - Sébastien Gaudin (sebastien.gaudin10@gmail.com)
      
      Licensed under the Apache License, Version 2.0 (the "License");
      you may not use this file except in compliance with the License.
      You may obtain a copy of the License at
      
          http://www.apache.org/licenses/LICENSE-2.0
      
      Unless required by applicable law or agreed to in writing, software
      distributed under the License is distributed on an "AS IS" BASIS,
      WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
      See the License for the specific language governing permissions and
      limitations under the License.
*/

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
    public function eraseCredentials(): void
    {

    }
}
