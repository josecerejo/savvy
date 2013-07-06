<?php

namespace Savvy\Task;

/**
 * Maintenance task
 *
 * @package Savvy
 * @subpackage Task
 */
class Maintenance extends AbstractTask
{
    /**
     * Run task
     *
     * @throws \Savvy\Task\Exception
     * @return void
     */
    public function execute()
    {
        $this->setResult(self::RESULT_SUCCESS);
    }
}
