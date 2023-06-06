<?php

namespace Sebius77\CasBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sebius77\CasBundle\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 * https://symfony.com/doc/current/bundles/extension.html
 */
class Sebius77CasExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $authenticator = $container->autowire('sebius77.cas_authenticator',
            'Sebius77\CasBundle\Security\CasAuthenticator');
        $authenticator->setArguments(array($config));

        $entryPoint = $container->autowire('sebius77.cas_entry_point',
        'Sebius77\CasBundle\Security\AuthenticationEntryPoint');
        $entryPoint->setArguments(array($config));

        $container->register('sebius77.cas_user_provider',
            'Sebius77\CasBundle\Security\User\CasUserProvider');
    }

    public function prepend(ContainerBuilder $container): void
    {

    }
}
