<?php

namespace Tellaw\SunshineAdminBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tellaw\SunshineAdminBundle\CompilerPass\WidgetCompilerPass;

class TellawSunshineAdminBundle extends Bundle
{

    public function build ( ContainerBuilder $container ) {
        $container->addCompilerPass(new WidgetCompilerPass());
    }

}
