<?php

namespace Tellaw\SunshineAdminBundle\Twig;

use ReflectionClass;
use Symfony\Component\Routing\RouterInterface;
use Tellaw\SunshineAdminBundle\Service\WidgetService;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class WidgetExtension extends AbstractExtension
{

    /**
     * Pages configuration
     * @var array
     */
    protected $configuration;

    /**
     * Service managing widgets
     * @var WidgetService
     */
    private $widgetService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Constructor
     *
     * @param array $configuration
     * @param WidgetService $widgetService
     */
    public function __construct(array $configuration, WidgetService $widgetService, RouterInterface $router)
    {
        $this->configuration = $configuration;
        $this->widgetService = $widgetService;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getWidgetUrl', array($this, 'getWidgetUrl'), array()),
            new \Twig_SimpleFunction(
                'sunshine_render_entity_field',
                array($this, 'renderEntityField'),
                array('needs_environment' => true)
            ),
            new TwigFunction('getEntityName', [$this, 'getEntityName']),
            new TwigFunction('routeExists', [$this, 'routeExists']),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new TwigFilter('truncate', array($this, 'truncateText'), array('needs_environment' => true)),
            new TwigFilter('urldecode', 'urldecode'),
        );
    }

    /**
     * @param $widget
     * @param $widgetName
     * @param $pageId
     * @param $row
     * @return mixed
     */
    public function getWidgetUrl($widget, $widgetName, $pageId, $row, $extraParam = null)
    {
        return $this->widgetService->getUrlOfWidget($widget, $widgetName, $pageId, $row, $extraParam);
    }


    /**
     * Copied from the official Text Twig extension.
     *
     * code: https://github.com/twigphp/Twig-extensions/blob/master/lib/Twig/Extensions/Extension/Text.php
     * author: Henrik Bjornskov <hb@peytz.dk>
     * copyright holder: (c) 2009 Fabien Potencier
     *
     * @param \Twig_Environment $env
     * @param $value
     * @param int $length
     * @param bool $preserve
     * @param string $separator
     * @return string
     */
    public function truncateText(\Twig_Environment $env, $value, $length = 64, $preserve = false, $separator = '...')
    {
        try {
            $value = (string)$value;
        } catch (\Exception $e) {
            $value = '';
        }

        if (mb_strlen($value, $env->getCharset()) > $length) {
            if ($preserve) {
                // If breakpoint is on the last word, return the value without separator.
                if (false === ($breakpoint = mb_strpos($value, ' ', $length, $env->getCharset()))) {
                    return $value;
                }

                $length = $breakpoint;
            }

            return rtrim(mb_substr($value, 0, $length, $env->getCharset())).$separator;
        }

        return $value;
    }

    /**
     *
     * @param Environment $twig
     * @param $fieldValue
     * @param array $parameters
     * @param bool $view
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderEntityField(Environment $twig, $fieldValue, array $parameters, $view = true)
    {
        $this->twig = $twig;
        $template = "@TellawSunshineAdmin/Widget/fields/field_".$parameters['type'].".html.twig";
        $parameters['value'] = $fieldValue;
        $parameters['view'] = $view;

        return $twig->render($template, $parameters);
    }

    /**
     * Get Object class short name
     *
     * @param $object
     * @return string
     * @throws \ReflectionException
     */
    public function getEntityName($object)
    {
        return (new ReflectionClass($object))->getShortName();
    }

    /**
     * @param $name
     * @return bool
     */
    public function routeExists($name)
    {
        return (null === $this->router->getRouteCollection()->get($name)) ? false : true;
    }
}
