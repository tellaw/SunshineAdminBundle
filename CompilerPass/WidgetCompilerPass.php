<?php

namespace Tellaw\SunshineAdminBundle\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Tellaw\SunshineAdminBundle\Service\WidgetService;

class WidgetCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(WidgetService::class)) {
            return;
        }

        $definition = $container->getDefinition(WidgetService::class);
        $taggedServices = $container->findTaggedServiceIds('sunshine.widget');
        foreach ($taggedServices as $id => $tagAttrbiutes) {
            $definition->addMethodCall( 'addServiceWidget', array( $id, new Reference($id)));
        }
    }
} 
