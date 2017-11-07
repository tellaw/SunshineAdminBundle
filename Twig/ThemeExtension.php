<?php

namespace Tellaw\SunshineAdminBundle\Twig;

use Tellaw\SunshineAdminBundle\Service\WidgetService;

class ThemeExtension extends \Twig_Extension
{

    /**
     * Pages configuration
     * @var array
     */
    private $configuration;

    /**
     * Constructor
     *
     * @param array $configuration
     * @param WidgetService $widgetService
     */
    public function __construct(array $configuration, WidgetService $widgetService)
    {
        $this->configuration = $configuration;
        $this->widgetService = $widgetService;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getLogo', array($this, 'getLogo'), array()),
        );
    }

    /**
     * Renvoie les valeurs du logo.
     */
    public function getLogo()
    {
        return $this->configuration['logo'];
    }
}
