<?php

namespace Vibalco\DatatableBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vibalco_datatable');

        $rootNode
                ->children()
                ->arrayNode('all')
                ->children()
                ->scalarNode('action')->defaultTrue()->end()
                ->scalarNode('search')->defaultFalse()->end()
                ->end()
                ->end()
                ->arrayNode('js')
                ->useAttributeAsKey('name')
                ->prototype('scalar')
                ->end()
                ->end()
                ->end()
        ;

        return $treeBuilder;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__ . '/../Resources/config/schema';
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return 'http://symfony.com/schema/dic/doctrine';
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($container->getParameter('kernel.debug'));
    }


}
