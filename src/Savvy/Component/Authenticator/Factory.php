<?php

namespace Savvy\Component\Authenticator;

use Savvy\Base as Base;
use Savvy\Component\Authenticator as Authenticator;

/**
 * Factory for authenticator instances
 *
 * @package Savvy
 * @subpackage Component\Authenticator
 */
class Factory extends \Savvy\Base\AbstractFactory
{
    /**
     * Create authentication facility from configuration
     *
     * @return \Savvy\Component\Authenticator\AbstractAuthenticator
     */
    public static function getInstance()
    {
        $authenticatorInstance = null;

        if ($authenticators = Base\Registry::getInstance()->get('authentication')) {
            foreach (array_reverse(explode(',', $authenticators)) as $authenticator) {
                $authenticatorClass = sprintf("Savvy\\Component\\Authenticator\\%s", ucfirst($authenticator));

                if (\Doctrine\Common\ClassLoader::classExists($authenticatorClass)) {
                    if ($authenticatorInstance instanceof AbstractAuthenticator) {
                        $successor = $authenticatorInstance;
                    } else {
                        $successor = null;
                    }

                    $authenticatorInstance = new $authenticatorClass;
                    $authenticatorInstance->setSuccessor($successor);
                } else {
                    throw new Exception($authenticator, Exception::E_COMPONENT_AUTHENTICATOR_FACTORY_UNKNOWN_FACILITY);
                }
            }
        }

        if (($authenticatorInstance instanceof AbstractAuthenticator) === false) {
            throw new Exception(null, Exception::E_COMPONENT_AUTHENTICATOR_FACTORY_NO_FACILITY);
        }

        return $authenticatorInstance->setFirst(true);
    }
}
