<?php

namespace Savvy\Runner;

/**
 * Abstract runner class
 *
 * @package Savvy
 * @subpackage Runner
 */
abstract class AbstractRunner
{
    /**
     * Languages for current environment in preferred order
     *
     * @var string
     */
    protected $language = null;

    /**
     * Main method
     *
     * @return int exit code / error level
     */
    abstract public function run();

    /**
     * Returns true if this runner is suitable for current mode of operation
     *
     * @return bool
     */
    abstract public function isSuitable();

    /**
     * Set language for current environment
     *
     * @param string $language
     * @return \Savvy\Runner\AbstractRunner
     */
    protected function setLanguage($language)
    {
        $this->language = (string)$language;
        return $this;
    }

    /**
     * Get languages for current environment in preferred order
     *
     * @return array languages in preferred order
     */
    public function getLanguage()
    {
        if ($this->language === null) {
            $languages = \Savvy\Base\Registry::getInstance()->get('languages');

            if ($languages === false) {
                $this->setLanguage('en');
            } else {
                $languages = explode(',', $languages);
                $this->setLanguage($languages[0]);
            }
        }

        return $this->language;
    }
}
