<?php

namespace Savvy\Runner\GUI\HTML\Widget;

/**
 * Layout Ext JS widget
 *
 * @package Savvy
 * @subpackage Runner\GUI\HTML\Widget
 */
class Layout extends AbstractWidget
{
    /**
     * Widget configuration
     *
     * @var array
     */
    protected $configuration = array(
        'type'      => array(
            'type'      => self::TYPE_VARIABLE
        ),
        'align'     => array(
            'type'      => self::TYPE_VARIABLE
        )
    );
}
