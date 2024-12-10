<?php
// src/Security/User/CasUserProvider.php

namespace Sebius77\CasBundle\Security\User;

use Sebius77\CasBundle\Security\User\CasUser;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CasUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{

    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Load a user object from your data source or throw UserNotFoundException.
        // The $identifier argument is whtatever value is being returned by the
        // getUserIdentifier() method in your User class.
        if (!empty($identifier)) {
            $user = new CasUser();
            $user->setUid($identifier);
            
            return $user;    
        }
        throw new UserNotFoundException();
    }

    public function loadUserByUsername(string $identifier)
    {
        return $this->loadUserByIdentifier($identifier);
    }

    /**
     * Refreshes the user after being reloaded from the session
     * 
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User Data.
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof CasUser) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        // Return a User object after making sure its data is "fresh".
        return $this->loadUserByIdentifier($user->getUserIdentifier());

        // Or throw a UserNotFoundException if the user no longer exists.
        //throw new \Exception('TODO: fill in refreshUser() inside ' . __FILE__);  
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class): bool
    {
        return CasUser::class === $class || is_subclass_of($class, CasUser::class);
    }

    /**
     * Upgrades thes hashed password of a user, typically for using a better hash algorithm.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // TODO: when hashed passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newHashedPassword);
    }
}
