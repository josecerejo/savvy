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
     *     'MODULES' => array(
     *         'LOGIN' => array(
     *             'INDEX' => array(
     *                 'TITLE' => 'Login',
     *                 [...]
     *             ),
     *             [...]
     *         ),
     *         [...]
     *     )
     * );
     *
     * @var array
     */
    private $ldata = null;

    /**
     * Initialize base localization; module localization files will be loaded
     * on demand
     *
     * @return void
     */
    public function init()
    {
        if ($this->ldata === null) {
            $this->ldata = array('MODULES' => array());

            foreach (array('default', Registry::getInstance()->get('locale')) as $language) {
                $filename = sprintf('%s/src/Savvy/Language/%s.xml', Registry::getInstance()->get('root'), $language);
                $this->loadLocalization($this->ldata, $filename);
            }
        }
    }

    /**
     * Get text from localization data by key name
     *
     * @param string $key key name, e.g. "EXCEPTION\E_SOMETHING_WENT_WRONG"
     * @param string $module module name
     * @return string localized string, or key if string was not found
     */
    public function get($key, $module = null)
    {
        $result = false;
        $pointer = &$this->ldata;
        $segments = explode("\\", $key);

        if ($module !== null) {
            if ($key[0] === "\\") {
                array_shift($segments);
            } else {
                $segments = array_merge(array('MODULES', strtoupper($module)), $segments);

                if (isset($this->ldata['MODULES'][strtoupper($module)]) === false) {
                    $this->ldata['MODULES'][strtoupper($module)] = array();

                    foreach (array('default', Registry::getInstance()->get('locale')) as $language) {
                        $filename = sprintf(
                            '%s/src/Savvy/Module/%s/Language/%s.xml',
                            Registry::getInstance()->get('root'),
                            ucfirst($module),
                            $language
                        );

                        $this->loadLocalization($this->ldata['MODULES'][strtoupper($module)], $filename);
                    }
                }
            }
        }

        foreach ($segments as $i => $segment) {
            $index = strtoupper($segment);

            if (isset($pointer[$index])) {
                if (is_array($pointer[$index]) && ($i < count($segments) - 1)) {
                    $pointer = &$pointer[$index];
                } else {
                    $result = $pointer[$index];
                }
            } else {
                break;
            }
        }

        if ($result === false) {
            $result = $key;
        }

        return $result;
    }

    /**
     * Load localization XML file and update data array
     *
     * @param array $ldata reference to localization data array
     * @param string $filename XML file to load
     * @return bool true on success
     */
    private function loadLocalization(&$ldata, $filename)
    {
        $result = false;

        if (is_readable($filename)) {
            $xml = simplexml_load_file($filename);

            if ((bool)$xml->strings) {
                foreach ($xml->strings as $section) {
                    $sectionName = strtoupper((string)$section->attributes()->section);

                    foreach ($section as $node) {
                        $keyName = strtoupper((string)$node->attributes()->key);
                        $ldata[$sectionName][$keyName] = (string)$node;
                        $result = true;
                    }
                }
            }
        }

        return $result;
    }
}
