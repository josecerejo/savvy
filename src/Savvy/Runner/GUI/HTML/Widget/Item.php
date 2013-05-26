<?php

namespace Savvy\Runner\GUI\HTML\Widget;

/**
 * Item Ext JS widget
 *
 * @package Savvy
 * @subpackage Runner\GUI\HTML\Widget
 */
class Item extends AbstractWidget
{
    /**
     * Widget configuration
     *
     * @var array
     */
    protected $configuration = array(
        'xtype'        => array(
            'type'         => self::TYPE_VARIABLE
        ),
        'inputType'    => array(
            'type'         => self::TYPE_VARIABLE
        ),
        'name'         => array(
            'type'         => self::TYPE_VARIABLE
        ),
        'fieldLabel'   => array(
            'type'         => self::TYPE_VARIABLE,
            'localize'     => true
        ),
        'labelAlign'   => array(
            'type'         => self::TYPE_VARIABLE
        ),
        'padding'      => array(
            'type'         => self::TYPE_VARIABLE
        ),
        'id'           => array(
            'type'         => self::TYPE_VARIABLE
        ),
        'width'        => array(
            'type'         => self::TYPE_VARIABLE
        ),
        'border'       => array(
            'type'         => self::TYPE_VARIABLE,
            'value'        => '0'
        ),
        'allowBlank'   => array(
            'type'         => self::TYPE_VARIABLE
        ),
        'buttons'      => array(
            'type'         => self::TYPE_CHILDS,
            'name'         => 'button'
        ),
        'items'        => array(
            'type'         => self::TYPE_CHILDS,
            'name'         => 'item'
        ),
        'layout'       => array(
            'type'         => self::TYPE_CHILD,
            'name'         => 'layout'
        ),
        'listeners'    => array(
            'type'         => self::TYPE_CODE
        )
    );

    /**
     * Map xtype configuration
     *
     * @return string
     */
    protected function getXtype()
    {
        $result = null;

        if (isset($this->attributes['type']) === true) {
            switch ($this->attributes['type']) {
                case 'text':
                case 'password':
                    $result = 'textfield';
                    break;
                default:
                    $result = $this->attributes['type'];
            }
        }

        return $result;
    }

    /**
     * Map inputType configuration
     *
     * @return string
     */
    protected function getInputType()
    {
        $result = null;

        if (isset($this->attributes['type']) === true) {
            if ($this->attributes['type'] === 'password') {
                $result = 'password';
            }
        }

        return $result;
    }

    /**
     * Map fieldLabel configuration
     *
     * @return string
     */
    protected function getFieldLabel()
    {
        $result = null;

        if (isset($this->attributes['label']) === true) {
            $result = $this->attributes['label'];
        }

        return $result;
    }

    /**
     * Map layout configuration
     *
     * @return string
     */
    protected function getLayout()
    {
        $result = null;

        if (isset($this->attributes['layout']) === true) {
            if ($this->attributes['layout'] === 'inherit') {
                $result = $this->parent->configuration['layout']['value'];
            }
        }

        return $result;
    }

    /**
     * Map id configuration
     *
     * @return string
     */
    protected function getId()
    {
        $result = null;

        if (isset($this->attributes['name']) === true) {
            if (isset($this->attributes['id']) === false) {
                $result = $this->attributes['name'];
            }
        }

        return $result;
    }

    /**
     * Generate listeners code
     *
     * @return string
     */
    protected function getListeners()
    {
        $result = null;

        if (isset($this->attributes['focus']) && (bool)$this->attributes['focus']) {
            $result = "{afterrender:function(f){f.focus(false,100);}}";
        }

        return $result;
    }
}
