<?php

namespace Savvy\Base;

/**
 * Abstract factory
 *
 * @package Savvy
 * @subpackage Base
 */
abstract class AbstractFactory
{
    /**
     * Create instance of object
     *
     * @return \stdClass
     */
    abstract public function getInstance();
}
