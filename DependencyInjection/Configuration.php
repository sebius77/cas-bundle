<?php

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
    public function getConfigTreeBuilder()
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
