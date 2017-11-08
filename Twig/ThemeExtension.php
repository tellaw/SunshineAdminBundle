<?php

namespace Tellaw\SunshineAdminBundle\Twig;

/**
 * Class ThemeExtension
 * @package Tellaw\SunshineAdminBundle\Twig
 */
class ThemeExtension extends \Twig_Extension
{

    /**
     * Theme configuration
     * @var array
     */
    private $configuration;

    /**
     * Constructor
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getLogo', array($this, 'getLogo'), array()),
            new \Twig_SimpleFunction('getApplicationName', array($this, 'getApplicationName'), array()),
        );
    }

    /**
     * Renvoie les valeurs du logo.
     */
    public function getLogo()
    {
        return $this->configuration['logo'];
    }

    public function getApplicationName()
    {
        return $this->configuration['name'];
    }
}
