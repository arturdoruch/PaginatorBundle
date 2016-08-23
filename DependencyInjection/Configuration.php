<?php

namespace ArturDoruch\PaginatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('artur_doruch_paginator');

        $rootNode
            ->children()
                ->integerNode('limit')
                    ->info('Default pagination limit')
                    ->min(1)->defaultValue(10)
                ->end()
                ->scalarNode('prev_page_label')->defaultValue('&#8592; Prev')->end()
                ->scalarNode('next_page_label')->defaultValue('Next &#8594;')->end()
            ->end();

        return $treeBuilder;
    }
}
