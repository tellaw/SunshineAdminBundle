<?php

namespace Tellaw\SunshineAdminBundle\Twig;

use Tellaw\SunshineAdminBundle\Service\WidgetService;

class WidgetExtension extends \Twig_Extension
{

    /**
     * Pages configuration
     * @var array
     */
    private $configuration;

    /**
     * Service managing widgets
     * @var WidgetService
     */
    private $widgetService;

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
            new \Twig_SimpleFunction('getWidgetUrl', array($this, 'getWidgetUrl'), array()),
            new \Twig_SimpleFunction(
                'sunshine_render_entity_field',
                array($this, 'renderEntityField'),
                array('needs_environment' => true)
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('truncate', array($this, 'truncateText'), array('needs_environment' => true)),
            new \Twig_SimpleFilter('urldecode', 'urldecode'),
        );
    }

    /**
     * @param $widget
     * @param $widgetName
     * @param $pageId
     * @param $row
     * @return mixed
     */
    public function getWidgetUrl($widget, $widgetName, $pageId, $row)
    {
        return $this->widgetService->getUrlOfWidget($widget, $widgetName, $pageId, $row);
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
     * @param \Twig_Environment $twig
     * @param $fieldValue
     * @param array $parameters
     * @param bool $view
     * @return string
     */
    public function renderEntityField(\Twig_Environment $twig, $fieldValue, array $parameters, $view = true)
    {
        $this->twig = $twig;
        $template = "@TellawSunshineAdmin/Widget/view/field_".$parameters['type'].".html.twig";
        $parameters['value'] = $fieldValue;
        $parameters['view'] = $view;

        return $twig->render($template, $parameters);
    }

}
