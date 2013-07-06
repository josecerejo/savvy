<?php

namespace Savvy\Runner\GUI\Widget;

use Savvy\Base as Base;

/**
 * Abstract class for Ext JS widgets
 *
 * @package Savvy
 * @subpackage Runner\GUI\Widget
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
     * @var \Savvy\Runner\GUI\Widget\AbstractWidget
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
     * Widget configuration
     *
     * @var array
     */
    protected $configuration = array();

    /**
     * Class constructor
     *
     * @param \SimpleXMLElement $xml XML view to be rendered
     * @param array $route route to current view
     * @param \Savvy\Runner\GUI\Widget\AbstractWidget $parent parent widget object
     */
    public function __construct(\SimpleXMLElement $xml, $route = array(), $parent = null)
    {
        $this->xml = $xml;
        $this->route = $route;
        $this->parent = $parent;
        $this->widget = join('', array_slice(explode('\\', get_called_class()), -1));

        if ((bool)$this->xml->attributes()) {
            $attributesElement = (array)$this->xml->attributes();

            foreach ($attributesElement['@attributes'] as $name => $value) {
                if (method_exists($this, $setter = sprintf('set%s', ucfirst($name)))) {
                    $this->$setter($value);
                }

                $this->attributes[$name] = $value;
            }
        }

        foreach ($this->configuration as $name => $properties) {
            if (($result = $this->$name) !== null) {
                $this->configuration[$name]['value'] = $result;
            } elseif (isset($properties['type'])) {
                switch ($properties['type']) {
                    case self::TYPE_CHILD:
                    case self::TYPE_CHILDS:
                        $childElements = (array)$this->xml->children();

                        if (isset($childElements[$properties['name']])) {
                            $this->configuration[$name]['value'] = array();

                            if (is_array($childElements[$properties['name']]) === false) {
                                $children = array(0 => $childElements[$properties['name']]);
                            } else {
                                $children = $childElements[$properties['name']];
                            }

                            foreach ($children as $childElement) {
                                $widgetClass = sprintf(
                                    "\\Savvy\\Runner\\GUI\\Widget\\%s",
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
     * Set configuration value
     *
     * @param string $name
     * @param string $value
     * @return bool
     */
    public function setConfiguration($name, $value)
    {
        $result = false;

        if (isset($this->configuration[$name])) {
            if (isset($this->configuration[$name]['value']) && is_array($this->configuration[$name]['value'])) {
                if (in_array($value, $this->configuration[$name]['value']) === false) {
                    $this->configuration[$name]['value'][] = $value;
                }
            } else {
                $this->configuration[$name]['value'] = $value;
            }

            $result = true;
        }

        return $result;
    }

    /**
     * Get attribute value, inherit getter if possible
     *
     * @param string $name attribute name
     * @return mixed attribute value
     */
    public function __get($name)
    {
        $result = null;

        if (method_exists($this, $getter = sprintf('get%s', ucfirst($name)))) {
            $result = $this->$getter();
        } elseif (isset($this->attributes[$name])) {
            $result = $this->attributes[$name];
        }

        return $result;
    }

    /**
     * Get (and process) widget configurations
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
                    if ($properties['type'] === self::TYPE_INTERNAL) {
                        $value = $properties['value'];
                    } else {
                        $value = implode($properties['value'], ',');
                    }
                } else {
                    $value = $properties['value'];

                    if (isset($this->configuration[$name]['localize'])) {
                        $value = Base\Language::getInstance()->get($value, $this->route[0]);
                    }
                }

                if (isset($properties['type'])) {
                    switch ($properties['type']) {
                        case self::TYPE_VARIABLE:
                            if (($value === 'false') || ($value === 'true') || is_numeric($value)) {
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
     * Get name of form we're currently nested in
     *
     * @param bool $instance true to get instance
     * @return string current form name/object or false
     */
    protected function currentForm($instance = false)
    {
        $result = false;

        if ($this->widget === 'Item' && $this->configuration['xtype']['value'] === 'form') {
            if ($instance) {
                $result = $this;
            } else {
                $result = $this->configuration['name']['value'];
            }
        } elseif ($this->parent !== null) {
            $result = $this->parent->currentForm($instance);
        }

        return $result;
    }

    /**
     * Get handler for button action
     *
     * @return string
     */
    protected function getHandler()
    {
        $result = "function(){";

        if ($this->currentForm() === false) {
            $this->configuration['scope'] = array(
                'type'  => self::TYPE_CODE,
                'value' => 'this'
            );

            switch ($this->attributes['action']) {
                case 'close':
                    $result .= 'this.close();';
            }
        } else {
            $currentForm = $this->currentForm(true);

            $result .= "var f=Ext.getCmp('" . $this->currentForm() . "');";

            // show loading message
            if ($message = $this->getConfiguration('message')) {
                $result .= "var lm=new Ext.LoadMask(f,{msg:'" . $message . "'});lm.show();";
            }

            $result .= "f.getForm().submit({headers:{'Application-Session':Ext.util.session()},";

            if (isset($this->attributes['action'])) {
                // presenter action
                $result .= "url:'/" . implode('/', $this->route) . "?action=" . $this->attributes['action'] . "',";
            }

            // add encrypted fields to post parameters
            if ($encryptedFields = $currentForm->getConfiguration('encryptedFields')) {
                $fields = array();

                foreach ($currentForm->getConfiguration('encryptedFields') as $i => $field) {
                    $fields[] = $field .":Ext.util.md5(" .
                        "Ext.util.md5(f.getForm().findField('" . $field . "').getValue())+Ext.util.session())";
                }

                $result .= "params:{" . implode(",", $fields) . "},";
            }

            $result .= "success:function(o,r){";

            // hide loading message on success
            if ((bool)$message) {
                $result .= "lm.hide();";
            }

            // invoke RPC processor for "success" responses
            $result .= "if(r.result.rpc){var rpc=new Ext.util.rpc(r.result.rpc);}";

            $result .= "},failure:function(o,r){";

            // hide loading message on failure
            if ((bool)$message) {
                $result .= "lm.hide();";
            }

            // invoke RPC processor for "failure" responses
            $result .= "if(r.result.rpc){var rpc=new Ext.util.rpc(r.result.rpc);}";

            $result .= '}});';
        }

        $result .= '}';

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
}
