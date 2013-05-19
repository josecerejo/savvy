<?php

namespace Savvy\Module\Login\Presenter;

use Savvy\Runner\GUI as GUI;

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
        $success = false;

        if ($username = $this->getRequest()->getForm('username')) {
            $em = \Savvy\Base\Database::getInstance()->getEntityManager();

            if ($user = $em->getRepository('Savvy\Storage\Model\User')->findByUsername($username)) {
                $success = true;
            }
        }

        if ($success === true) {
            $this->getResponse()->addCommand()->close('loginWindow');
            $this->getResponse()->addCommand()->open('test');
        } else {
            $this->getResponse()->addCommand()->reset('login');
            $this->getResponse()->addCommand()->focus('username');
            $this->getResponse()->addCommand()->shake('loginWindow');
        }

        $this->getResponse()->setSuccess($success);
    }
}
