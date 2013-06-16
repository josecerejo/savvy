<?php

namespace Savvy\Component\Authenticator;

use Savvy\Base as Base;

/**
 * Authenticate using a MD5 password stored in database
 *
 * @package Savvy
 * @subpackage Component\Authenticator
 */
class Database extends AbstractAuthenticator
{
    /**
     * Actual authenticator implementation
     *
     * @return int result
     */
    protected function authenticate()
    {
        $result = self::AUTHENTICATION_UNKNOWN;
        $repository = Base\Database::getInstance()->getEntityManager()->getRepository('Savvy\Storage\Model\User');

        if ($user = $repository->findOneByUsername($this->getUsername())) {
            $result = self::AUTHENTICATION_FAILURE;
            $salt = Base\Session::getInstance()->getApplicationSessionId();

            if ((string)$user->getPassword() === '' && $this->getPassword() === '') {
                $result = self::AUTHENTICATION_SUCCESS;
            }
            elseif (strcmp(md5($user->getPassword() . $salt), $this->getPassword()) === 0) {
                $result = self::AUTHENTICATION_SUCCESS;
            }
        }

        return $result;
    }
}
