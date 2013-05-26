<?php

namespace Savvy\Runner\GUI\HTML\Widget;

/**
 * Window Ext JS widget
 *
 * @package Savvy
 * @subpackage Runner\GUI\HTML\Widget
 */
class Window extends AbstractWidget
{
    /**
     * Widget configuration
     *
     * @var array
     */
    public $configuration = array(
        'resizable'     => array(
            'type'          => self::TYPE_VARIABLE,
            'value'         => '0'
        ),
        'closable'      => array(
            'type'          => self::TYPE_VARIABLE,
            'value'         => '0'
        ),
        'border'        => array(
            'type'          => self::TYPE_VARIABLE,
            'value'         => '0'
        ),
        'plain'         => array(
            'type'          => self::TYPE_VARIABLE
        ),
        'id'            => array(
            'type'          => self::TYPE_VARIABLE
        ),
        'width'         => array(
            'type'          => self::TYPE_VARIABLE
        ),
        'height'        => array(
            'type'          => self::TYPE_VARIABLE
        ),
        'resizeHandles' => array(
            'type'          => self::TYPE_VARIABLE
        ),
        'title'         => array(
            'type'          => self::TYPE_VARIABLE,
            'localize'      => true
        ),
        'layout'        => array(
            'type'          => self::TYPE_CHILD,
            'name'          => 'layout'
        ),
        'buttons'       => array(
            'type'          => self::TYPE_CHILDS,
            'name'          => 'button'
        ),
        'items'         => array(
            'type'          => self::TYPE_CHILDS,
            'name'          => 'item'
        ),
        'header'        => array(
            'type'          => self::TYPE_VARIABLE
        )
    );

    /**
     * Map resizable configuration
     *
     * @return string
     */
    protected function getResizable()
    {
        $result = null;

        if (isset($this->attributes['resizable']) === true) {
            switch ($this->attributes['resizable']) {
                case 'vertical':
                case 'horizontal':
                    $result = 'true';
                    break;
                default:
                    $result = $this->attributes['resizable'];
            }
        }

        return $result;
    }

    /**
     * Map resizeHandles configuration
     *
     * @return string|null
     */
    protected function getResizeHandles()
    {
        $result = null;

        if (isset($this->attributes['resizable']) === true) {
            switch ($this->attributes['resizable']) {
                case 'vertical':
                    $result = 'n s';
                    break;
                case 'horizontal':
                    $result = 'w e';
                    break;
            }
        }

        return $result;
    }

    /**
     * Get header configuration
     *
     * @return string|null
     */
    protected function getHeader()
    {
        $result = 'false';

        foreach (array('header', 'title', 'closable') as $attribute) {
            $cfg = $this->configuration[$attribute];

            if (isset($cfg['value']) && (bool)$cfg['value']) {
                $result = null;
                break;
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
        $result = '';

        $result .= "Ext.define('View." . implode('.', $this->route) . "',{";
        $result .= "extend:'Ext.Window',";
        $result .= "initComponent:function(){";

        if ($configuration = $this->getConfiguration()) {
            $result .= "Ext.apply(this," . parent::render() .");";
            $result .= "View." . implode('.', $this->route) . ".superclass.initComponent.apply(this,arguments);";
        }

        $result .= "}});\n";

        return $result;
    }
}
