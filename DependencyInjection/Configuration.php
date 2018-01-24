<?php

namespace Tellaw\SunshineAdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tellaw_sunshine_admin');

        $rootNode
            ->children()
                ->append($this->addEntitiesNode())
                ->append($this->addMenuNode())
                ->append($this->addPagesNode())
                ->append($this->addThemeNode())
            ->end();

        return $treeBuilder;
    }

    /**
     * Définition du bloc de configuration "Entities"
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    public function addEntitiesNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('entities');

        $node
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->arrayNode('configuration')
                        ->children()
                            ->scalarNode('id')
                                ->defaultValue('id')
                            ->end()
                            ->scalarNode('class')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->booleanNode('displayedInMenu')
                                ->defaultFalse()
                            ->end()
                            ->arrayNode('link')
                                ->prototype('scalar')->end()
                                ->defaultValue([])
                            ->end()
                            ->scalarNode('title')
                            ->end()
                            ->scalarNode('description')
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('attributes')
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('label')
                                ->end()
                                ->enumNode('type')
                                    ->defaultNull()
                                    ->values(array('string', 'integer', 'date', 'datetime', 'object', 'text', 'embedded', 'custom'))
                                ->end()
                                ->booleanNode('expanded')
                                ->end()
                                ->scalarNode('allow_add')
                                ->end()
                                ->scalarNode('allow_delete')
                                ->end()
                                ->scalarNode('configuration')
                                ->end()
                                ->scalarNode('embedded')
                                ->end()
                                ->booleanNode('multiple')
                                ->end()
                                ->booleanNode('sortable')
                                ->end()
                                ->arrayNode('order')
                                    ->prototype('array')
                                        ->children()
                                            ->arrayNode('field')
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('name')
                                                        ->isRequired()
                                                        ->cannotBeEmpty()
                                                        ->end()
                                                        ->enumNode('direction')
                                                        ->defaultValue('DESC')
                                                        ->values(array('DESC', 'ASC'))
                                                        ->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->booleanNode('readonly')
                                ->end()
                                ->scalarNode('filterAttribute')
                                ->end()
                                ->scalarNode('relatedClass')
                                ->end()
                                ->scalarNode('callbackFunction')
                                ->end()
                                ->booleanNode('required')
                                ->defaultValue(false)
                                ->end()
                                ->scalarNode('placeholder')
                                ->end()
                                ->scalarNode('lazy')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('form')
                        ->children()
                            ->scalarNode('title')
                            ->end()
                            ->scalarNode('description')
                            ->end()
                            ->scalarNode('formType')
                            ->end()
                            ->arrayNode('jsIncludes')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('cssIncludes')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('fields')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('label')
                                        ->end()
                                        ->scalarNode('placeholder')
                                        ->end()
                                        ->booleanNode('readonly')
                                        ->end()
                                        ->scalarNode('filterAttribute')
                                        ->end()
                                        ->scalarNode('relatedClass')
                                        ->end()
                                        ->booleanNode('multiple')
                                        ->end()
                                        ->scalarNode('configuration')
                                        ->end()
                                        ->booleanNode('expanded')
                                        ->end()
                                        ->scalarNode('allow_add')
                                        ->end()
                                        ->scalarNode('allow_delete')
                                        ->end()
                                        ->enumNode('type')
                                            ->values(array('string', 'integer', 'date', 'datetime', 'object', 'text', 'embedded', 'custom'))
                                        ->end()
                                        ->scalarNode('lazy')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->scalarNode('class')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('list')
                        ->children()
                            ->scalarNode('title')
                            ->end()
                            ->scalarNode('general_search')->defaultNull()
                            ->end()
                            ->scalarNode('description')
                            ->end()
                            ->arrayNode('fields')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('label')
                                        ->end()
                                        ->enumNode('type')
                                            ->values(array('string', 'integer', 'date', 'datetime', 'object', 'text', 'embedded', 'custom'))
                                        ->end()
                                        ->scalarNode('template')
                                            ->defaultNull()
                                        ->end()
                                        ->scalarNode('filterAttribute')
                                        ->end()
                                        ->scalarNode('relatedClass')
                                            ->defaultFalse()
                                        ->end()
                                        ->enumNode('order')
                                            ->values(array('asc', 'desc'))
                                            ->defaultNull()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('filters')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('label')
                                            ->defaultNull()
                                        ->end()
                                        ->booleanNode('multiple')->defaultNull()
                                        ->end()
                                        ->arrayNode('value')
                                                ->children()
                                                    ->scalarNode('provider')->defaultNull()->end()
                                                    ->arrayNode('arguments')->prototype("variable")->end()
                                                    ->defaultValue([])
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('search')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('label')
                                            ->defaultNull()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }

    /**
     * Définition du bloc de configuration "Menu"
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    public function addMenuNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('menu');

        $node
            ->prototype('array')
                ->children()
                    ->scalarNode('label')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('icon')
                    ->end()
                    ->scalarNode('target')
                    ->end()
                    ->enumNode('type')
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->values(array('page', 'external', 'section', 'subMenu'))
                    ->end()
                    ->arrayNode('security')
                    ->children()
                    ->variableNode('roles')->defaultNull()->end()
                    ->variableNode('permissions')->defaultNull()->end()
                    ->scalarNode('entity')->defaultNull()->end()
                    ->end()
                    ->end()
                    ->variableNode('parameters')->end()
                    ->variableNode('children')->end()
                ->end()
            ->end();

        return $node;
    }



    /**
     * Définition du bloc de configuration "Pages"
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    public function addPagesNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('pages');

        $node
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('title')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('parent')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('description')
                        ->defaultNull()
                    ->end()
                    ->arrayNode('jsIncludes')
                        ->prototype('scalar')->end()
                    ->end()
                    ->arrayNode('cssIncludes')
                        ->prototype('scalar')->end()
                    ->end()
                    ->variableNode('roles')->end()
                    ->variableNode('rows')->end()
                    ->variableNode('content')->end()
                ->end();

        return $node;
    }


    /**
     * Définition du bloc de configuration "Theme"
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    public function addThemeNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('theme')->addDefaultsIfNotSet();
        $node
            ->children()
                ->arrayNode('logo')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('url')->defaultValue('bundles/tellawsunshineadmin/assets/vendors/base/media/img/logo/logo_default_dark.png')->end()
                        ->scalarNode('alt')->defaultValue("logo")->end()
                        ->booleanNode('external_url')->defaultFalse()->end()
                    ->end()
                ->end()
                ->scalarNode('name')
                    ->defaultValue('Sunshine | Dashboard')
                ->end()
            ->end();

        return $node;
    }
}
