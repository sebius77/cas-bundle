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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 * 
 * To learn more see https://symfony.com/doc/current/bundles/configuration.html
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sebius77_cas');
        
        $rootNode = $treeBuilder->getRootNode();
    
        $rootNode
            ->children()
            ->scalarNode('server_login_url')->end()
            ->scalarNode('server_validation_url')->end()
            ->scalarNode('server_logout_url')->end()
            ->scalarNode('xml_namespace')
            ->defaultValue('cas')
            ->end()
            ->arrayNode('options')
            ->prototype('scalar')->end()
            ->defaultValue(array())
            ->end()
            ->scalarNode('username_attribute')
            ->defaultValue('user')
            ->end()
            ->scalarNode('query_ticket_parameter')
            ->defaultValue('ticket')
            ->end()
            ->scalarNode('query_service_parameter')
            ->defaultValue('service')
            ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
