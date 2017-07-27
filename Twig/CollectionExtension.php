<?php

namespace Tellaw\SunshineAdminBundle\Twig;

use Twig\TwigFunction;

/**
 * @author Ismail mezrani <ismailmezrani@gmail.com>
 */
class CollectionExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction(
                'form_row_collection',
                null,
                array('node_class' => 'Symfony\Bridge\Twig\Node\RenderBlockNode', 'is_safe' => array('html'))
            ),
            new TwigFunction(
                'getClassName',
                [$this, 'getClassName']
            ),
        );
    }

    public function getClassName($class)
    {
        $reflectionClass = new \ReflectionClass($class);

        return $reflectionClass->getShortName();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'form_collection';
    }

}