<?php

namespace Savvy\Module\Test\Presenter;

use Savvy\Runner\GUI as GUI;

/**
 * Test module index presenter
 *
 * @package Savvy
 * @subpackage Module\Test
 */
class IndexPresenter extends GUI\Presenter
{
    /**
     * Open modal dialog
     *
     * @return void
     */
    protected function modalAction()
    {
        $this->getResponse()->addCommand()->modal('test', 'modal');
    }
}
