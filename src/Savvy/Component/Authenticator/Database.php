<?php

namespace Savvy\Component\Authenticator;

use Savvy\Base as Base;

/**
 * Authenticate against username/password stored in database
 *
 * @package Savvy
 * @subpackage Component\Authenticator
 */
class Database extends AbstractAuthenticator
{
    /**
     * Actual authenticator implementation
     *
     * @return int internal authentication result
     */
    protected function authenticate()
    {
        $result = self::AUTHENTICATION_UNKNOWN;
        $repository = Base\Database::getInstance()->getEntityManager()->getRepository('Savvy\Storage\Model\User');

        if ($user = $repository->findOneByUsername($this->getUsername())) {
            $result = self::AUTHENTICATION_FAILURE;

            if (strcmp((string)$user->getPassword(), $this->getPassword()) === 0) {
                $result = self::AUTHENTICATION_SUCCESS;
            }
        }

        return $result;
    }
}
