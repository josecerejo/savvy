<?php

namespace Savvy\Runner\GUI\HTML\Widget;

/**
 * Button Ext JS widget
 *
 * @package Savvy
 * @subpackage Runner\GUI\HTML\Widget
 */
class Button extends AbstractWidget
{
    /**
     * Widget configuration
     *
     * @var array
     */
    protected $configuration = array(
        'text'         => array(
            'type'         => self::TYPE_VARIABLE,
            'localize'     => true
        ),
        'message'      => array(
            'type'         => self::TYPE_INTERNAL,
            'localize'     => true
        ),
        'scale'        => array(
            'type'         => self::TYPE_VARIABLE,
            'value'        => 'small'
        ),
        'handler'      => array(
            'type'         => self::TYPE_CODE
        )
    );
}
