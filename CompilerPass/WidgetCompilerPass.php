<?php

namespace Tellaw\SunshineAdminBundle\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WidgetCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {

        if (!$container->hasDefinition('sunshine.widgets')) {
            return;
        }

        $definition = $container->getDefinition('sunshine.widgets');
        $taggedServices = $container->findTaggedServiceIds('sunshine.widget');
        foreach ($taggedServices as $id => $tagAttrbiutes) {
            $definition->addMethodCall( 'addServiceWidget', array( $id, new Reference($id)));
        }
    }
} 
