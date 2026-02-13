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

namespace Sebius77\CasBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sebius77\CasBundle\DependencyInjection\Configuration;
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
