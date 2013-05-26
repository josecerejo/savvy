<?php

namespace Savvy\Runner\GUI\HTML\Widget;

use Savvy\Base as Base;

/**
 * Abstract class for Ext JS widgets
 *
 * @package Savvy
 * @subpackage Runner\GUI\HTML\Widget
 */
abstract class AbstractWidget
{
    /**
     * Plain JavaScript code
     */
    const TYPE_CODE = 0;

    /**
     * JavaScript variable (string, integer, float or boolean)
     */
    const TYPE_VARIABLE = 1;

    /**
     * XML child element, not repeatable
     */
    const TYPE_CHILD = 2;

    /**
     * XML child element, repeatable
     */
    const TYPE_CHILDS = 3;

    /**
     * For internal use only; not (directly) transformed into JavaScript
     */
    const TYPE_INTERNAL = 99;

    /**
     * XML object
     *
     * @var SimpleXMLElement
     */
    protected $xml = null;

    /**
     * XML object attributes
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * Parent widget
     *
     * @var \Savvy\Runner\GUI\HTML\Widget\AbstractWidget
     */
    protected $parent = null;

    /**
     * Widget type, e.g. "Item", "Button" etc.
     *
     * @var string
     */
    protected $widget = null;

    /**
     * Route to current view
     *
     * @var array
     */
    protected $route = array();

    /**
     * ExtJS compatible Widget configuration
     *
     * @var array
     */
    protected $configuration = array();

    /**
     * Class constructor
     *
     * @param \SimpleXMLElement $xml XML view to be rendered
     * @param array $route route to current view
     * @param \Savvy\Runner\GUI\HTML\Widget\AbstractWidget $parent parent widget object
     */
    public function __construct(\SimpleXMLElement $xml, $route = array(), $parent = null)
    {
        $this->xml = $xml;
        $this->route = $route;
        $this->parent = $parent;
        $this->widget = join('', array_slice(explode('\\', get_called_class()), -1));

        if ((bool)$this->xml->attributes() === true) {
            $attributesElement = (array)$this->xml->attributes();
            $this->attributes = $attributesElement['@attributes'];
        }

        foreach ($this->configuration as $name => $properties) {
            if (($result = $this->$name) !== null) {
                $this->configuration[$name]['value'] = $result;
            } elseif (isset($properties['type'])) {
                switch ($properties['type']) {
                    case self::TYPE_CHILD:
                    case self::TYPE_CHILDS:
                        $childElements = (array)$this->xml->children();

                        if (isset($childElements[$properties['name']]) === true) {
                            $this->configuration[$name]['value'] = array();

                            if (is_array($childElements[$properties['name']]) === false) {
                                $children = array(0 => $childElements[$properties['name']]);
                            } else {
                                $children = $childElements[$properties['name']];
                            }

                            foreach ($children as $childElement) {
                                $widgetClass = sprintf(
                                    "\\Savvy\\Runner\\GUI\\HTML\\Widget\\%s",
                                    ucfirst($properties['name'])
                                );

                                $widgetInstance = new $widgetClass($childElement, $route, $this);
                                $this->configuration[$name]['value'][] = $widgetInstance->render();
                            }
                        }
                }
            }
        }
    }

    /**
     * Interceptor method calls getter for given attribute, or stores attribute
     * value directly in widget configuration if getter does not exist
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $result = null;

        if (method_exists($this, $getter = sprintf('get%s', ucfirst($name))) === true) {
            $result = $this->$getter();
        } elseif (isset($this->attributes[$name]) === true) {
            $result = $this->attributes[$name];
        }

        return $result;
    }

    /**
     * Prepare widget configuration to generate Ext JS output
     *
     * @param string $name configuration name
     * @return mixed output, or false if configuration does not exist
     */
    protected function getConfiguration($name = null)
    {
        $result = false;

        if ($name === null) {
            $result = array();

            foreach ($this->configuration as $name => $properties) {
                if (isset($properties['type']) && $properties['type'] === self::TYPE_INTERNAL) {
                    continue;
                }

                if ($value = $this->getConfiguration($name)) {
                    $result[] = $value;
                }
            }
        } elseif (isset($this->configuration[$name])) {
            $properties = $this->configuration[$name];

            if (isset($properties['value'])) {
                if (is_array($properties['value'])) {
                    $value = implode($properties['value'], ',');
                } else {
                    $value = $properties['value'];

                    if (isset($this->configuration[$name]['localize'])) {
                        $value = Base\Language::getInstance()->get($value, $this->route[0]);
                    }
                }

                if (isset($properties['type'])) {
                    switch ($properties['type']) {
                        case self::TYPE_VARIABLE:
                            if (is_numeric($value)) {
                                $result = sprintf('%s:%d', $name, $value);
                            } elseif (($value === 'false') or ($value === 'true')) {
                                $result = sprintf("%s:%s", $name, $value);
                            } else {
                                $result = sprintf("%s:'%s'", $name, $value);
                            }
                            break;
                        case self::TYPE_CHILDS:
                            $result = sprintf('%s:[%s]', $name, $value);
                            break;
                        case self::TYPE_INTERNAL:
                            $result = $value;
                            break;
                        default:
                            $result = sprintf('%s:%s', $name, $value);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Generate browser output
     *
     * @return string
     */
    public function render()
    {
        $result = null;

        if ($configuration = $this->getConfiguration()) {
            $result = '{' . implode($this->getConfiguration(), ',') . '}';
        }

        return $result;
    }

    /**
     * Get name of form we're currently nested in
     *
     * @return string current form name or false
     */
    protected function currentForm()
    {
        $result = false;

        if ($this->widget === 'Item' && $this->configuration['xtype']['value'] === 'form') {
            $result = $this->configuration['name']['value'];
        } elseif ($this->parent !== null) {
            $result = $this->parent->currentForm();
        }

        return $result;
    }
}
