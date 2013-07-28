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
     * Open framed window with external source
     *
     * @return void
     */
    protected function externalFrameAction()
    {
        $this->getResponse()->addCommand()->open('test', 'externalFrame');
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
