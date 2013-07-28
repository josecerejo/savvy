<?php

namespace Savvy\Module\Test\Presenter;

use Savvy\Base as Base;
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

    /**
     * Close module
     *
     * @return void
     */
    protected function closeAction()
    {
        $this->getResponse()->addCommand()->close('testWindow');
    }

    /**
     * Quit session (logout)
     *
     * @return void
     */
    protected function logoutAction()
    {
        Base\Session::getInstance()->quit();
        $this->getResponse()->addCommand()->quit();
    }
}
