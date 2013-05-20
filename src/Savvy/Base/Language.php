<?php

namespace Savvy\Base;

use Doctrine;

/**
 * Language and localization support
 *
 * @package Savvy
 * @subpackage Base
 */
class Language extends AbstractSingleton
{
    /**
     * Localization data.
     *
     * array(
     *     'EXCEPTION' => array(
     *         'E_SOMETHING_WENT_WRONG' => 'Something went wrong',
     *         [...]
     *     ),
     *     [...]
     * );
     *
     * @var array
     */
    private $locale = null;

    /**
     * Initialize localization data
     *
     * @return void
     */
    public function init()
    {
        if ($this->locale === null) {
            $this->locale = array();

            foreach (array('default', Registry::getInstance()->get('locale')) as $language) {
                $filename = sprintf('%s/src/Savvy/Language/%s.xml', Registry::getInstance()->get('root'), $language);

                if (is_readable($filename)) {
                    $xml = simplexml_load_file($filename);

                    if ((bool)$xml->strings) {
                        $section = (string)$xml->strings->attributes()->section;

                        foreach ($xml->strings->children() as $node) {
                            $this->locale[$section][(string)$node->attributes()->key] = (string)$node;
                        }
                    }
                }
            }
        }
    }

    /**
     * Get string from localization data by key; returns false if key was not
     * found.
     *
     * @param string $key identifier, e.g. "EXCEPTION\E_SOMETHING_WENT_WRONG"
     * @return string localized string, or false if key was not found
     */
    public function get($key)
    {
        $result = false;
        
        $segments = explode("\\", $key);
        $pointer = &$this->locale;

        foreach ($segments as $i => $segment) {
            if (isset($pointer[$segment])) {
                if (is_array($pointer[$segment]) && ($i < count($segments) - 1)) {
                    $pointer = &$pointer[$segment];
                } else {
                    $result = $pointer[$segment];
                }
            } else {
                break;
            }
        }

        return $result;
    }
}
