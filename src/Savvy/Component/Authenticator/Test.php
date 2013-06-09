<?php

namespace Savvy\Component\Authenticator;

use Savvy\Base as Base;

/**
 * This authenticator is used by unit tests
 *
 * @package Savvy
 * @subpackage Component\Authenticator
 */
class Test extends AbstractAuthenticator
{
    /**
     * Actual authenticator implementation
     *
     * @return int internal authentication result
     */
    protected function authenticate()
    {
        $result = self::AUTHENTICATION_UNKNOWN;

        if (defined('APPLICATION_MODE') && constant('APPLICATION_MODE') === 'test') {
            $result = self::AUTHENTICATION_FAILURE;

            if ($this->getUsername() === 'PHPUnit' && $this->getPassword() === 'PHPUnit') {
                $result = self::AUTHENTICATION_SUCCESS;
            }
        }

        return $result;
    }
}
