<?php

namespace Savvy\Module\Login\Presenter;

use Savvy\Runner\GUI as GUI;
use Savvy\Component\Authenticator as Authenticator;

/**
 * Login module index presenter
 *
 * @package Savvy
 * @subpackage Module\Login
 */
class IndexPresenter extends GUI\Presenter
{
    /**
     * Validate login and start application session on success
     *
     * @return void
     */
    protected function validateAction()
    {
        $success = Authenticator\Factory::getInstance()->validate(
            $this->getRequest()->getForm('username'),
            $this->getRequest()->getForm('password')
        );

        if ($success === true) {
            $this->getResponse()->addCommand()->close('loginWindow');
            $this->getResponse()->addCommand()->open('test');
        } else {
            $this->getResponse()->addCommand()->reset('login');
            $this->getResponse()->addCommand()->focus('username');
            $this->getResponse()->addCommand()->shake('loginWindow');
            $this->getResponse()->setSuccess(false);
        }
    }
}
