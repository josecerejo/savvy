<?php

namespace Savvy\Runner\GUI\Widget;

/**
 * Element Ext JS widget
 *
 * @package Savvy
 * @subpackage Runner\GUI\Widget
 */
class Element extends AbstractWidget
{
    /**
     * Widget configuration
     *
     * @var array
     */
    protected $configuration = array(
        'tag'         => array(
            'type'        => self::TYPE_VARIABLE
        ),
        'src'         => array(
            'type'        => self::TYPE_VARIABLE
        ),
        'frameborder' => array(
            'type'        => self::TYPE_VARIABLE
        )
    );
}
