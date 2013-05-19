<?php

namespace Savvy\Component\Utility;

/**
 * Search and translate various ISO codes (country, language etc.)
 *
 * @package Savvy
 * @subpackage Component\Utility
 */
class Isoquery extends AbstractUtility
{
    /**
     * Configure command-line utility
     *
     * @return void
     */
    protected function configure()
    {
        $this->setExecutable(\Savvy\Base\Registry::getInstance()->get('utility.isoquery'));
    }

    /**
     * Query ISO standards by code or number
     *
     * @param string $standard ISO standard to use, e.g. "639-3", "4217"
     * @param string $query Code or number to translate
     * @return array
     */
    public function query($standard, $query)
    {
        $result = array();

        if ($output = $this->execute(sprintf("--iso=%s '%s' 2>/dev/null", $standard, $query))) {
            $result = preg_split("/\t/", $output[0]);
        }

        return $result;
    }
}
