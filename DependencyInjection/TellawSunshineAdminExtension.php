<?php

namespace Tellaw\SunshineAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class TellawSunshineAdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('tellaw_sunshine_admin.menu', $config['menu']);
        $container->setParameter('tellaw_sunshine_admin.entities', $config['entities']);
        $container->setParameter('tellaw_sunshine_admin.pages', $config['pages']);
        $container->setParameter('tellaw_sunshine_admin.theme', $config['theme']);
        $container->setParameter('tellaw_sunshine_admin.tinymce', $config['tinymce']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
