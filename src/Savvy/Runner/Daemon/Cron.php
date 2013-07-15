<?php

namespace Savvy\Runner\Daemon;

/**
 * Cron expression parser
 *
 * @package Savvy
 * @subpackage Runner\Daemon
 */
class Cron
{
    /**
     * Weekday look-up table
     *
     * @var array
     */
    protected static $weekdays = array(
        'sun' => 0,
        'mon' => 1,
        'tue' => 2,
        'wed' => 3,
        'thu' => 4,
        'fri' => 5,
        'sat' => 6
    );

    /**
     * Month name look-up table
     *
     * @var array
     */
    protected static $months = array(
        'jan' => 1,
        'feb' => 2,
        'mar' => 3,
        'apr' => 4,
        'may' => 5,
        'jun' => 6,
        'jul' => 7,
        'aug' => 8,
        'sep' => 9,
        'oct' => 10,
        'nov' => 11,
        'dec' => 12
    );

    /**
     * Cron expression
     *
     * @var string
     */
    protected $expression;

    /**
     * Timezone
     *
     * @var \DateTimeZone
     */
    protected $timezone;

    /**
     * Matching register
     *
     * @var array|null
     */
    protected $register;

    /**
     * Class constructor sets cron expression property
     *
     * @param string $expression cron expression
     * @param \DateTimeZone $timezone
     */
    public function __construct($expression = '* * * * *', \DateTimeZone $timezone = null)
    {
        $this->setExpression($expression);
        $this->setTimezone($timezone);
    }

    /**
     * Set expression
     *
     * @param string $expression
     * @return \Savvy\Runner\Daemon\Cron
     */
    public function setExpression($expression)
    {
        $this->expression = trim((string)$expression);
        $this->register = null;
        return $this;
    }

    /**
     * Get expression
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Set timezone
     *
     * @param \DateTimeZone $timezone
     * @return \Savvy\Runner\Daemon\Cron
     */
    public function setTimezone(\DateTimeZone $timezone = null)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * Parse and validate cron expression
     *
     * @return bool true if expression is valid, or false on error
     */
    public function valid()
    {
        $result = true;

        if ($this->register === null) {
            if (sizeof($segments = preg_split('/\s+/', $this->expression)) !== 5) {
                $result = false;
            } else {
                $minv = array(0, 0, 1, 1, 0);
                $maxv = array(59, 23, 31, 12, 7);
                $strv = array(false, false, false, self::$months, self::$weekdays);

                foreach ($segments as $s => $segment) {

                    // month names, weekdays
                    if ($strv[$s] !== false && isset($strv[$s][strtolower($segment)])) {
                        // cannot be used with lists or ranges, see crontab(5) man page
                        $register[$s][$strv[$s][strtolower($segment)]] = true;
                        continue;
                    }

                    // split up list into segments (e.g. "1,3-5,9")
                    foreach (explode(',', $segment) as $l => $listsegment) {

                        // parse steps notation
                        if (strpos($listsegment, '/') !== false) {
                            $steps = false;

                            if (sizeof($stepsegments = explode('/', $listsegment)) === 2) {
                                $listsegment = $stepsegments[0];

                                if (is_numeric($stepsegments[1])) {
                                    if ($stepsegments[1] > 0 && $stepsegments[1] <= $maxv[$s]) {
                                        $steps = intval($stepsegments[1]);
                                    } else {
                                        // steps value is out of allowed range
                                        $result = false;
                                        break 2;
                                    }
                                }
                            }

                            if ($steps === false) {
                                // invalid steps notation
                                $result = false;
                                break 2;
                            }
                        } else {
                            $steps = 1;
                        }

                        // single value
                        if (is_numeric($listsegment)) {
                            if (intval($listsegment) < $minv[$s] || intval($listsegment) > $maxv[$s]) {
                                // value is out of allowed range
                                $result = false;
                                break 2;
                            }

                            if ($steps !== 1) {
                                // single value cannot be combined with steps notation
                                $result = false;
                                break;
                            }

                            $register[$s][intval($listsegment)] = true;
                            continue;
                        }

                        // asterisk indicates full range of values
                        if ($listsegment === '*') {
                            $listsegment = sprintf('%d-%d', $minv[$s], $maxv[$s]);
                        }

                        // range of values, e.g. "9-17"
                        if (strpos($listsegment, '-') !== false) {
                            if (sizeof($ranges = explode('-', $listsegment)) !== 2) {
                                // invalid range notation
                                $result = false;
                                break 2;
                            }

                            // validate range
                            foreach ($ranges as $r => $range) {
                                if (is_numeric($range)) {
                                    if (intval($range) < $minv[$s] || intval($range) > $maxv[$s]) {
                                        // start or end value is out of allowed range
                                        $result = false;
                                        break 3;
                                    }
                                } else {
                                    // non-numeric range notation
                                    $result = false;
                                    break 3;
                                }
                            }

                            // fill matching register
                            if ($ranges[0] === $ranges[1]) {
                                $register[$s][$ranges[0]] = true;
                            } else {
                                for ($i = $minv[$s]; $i <= $maxv[$s]; $i++) {
                                    if (($i - $ranges[0]) % $steps === 0) {
                                        if ($ranges[0] < $ranges[1]) {
                                            if ($i >= $ranges[0] && $i <= $ranges[1]) {
                                                $register[$s][$i] = true;
                                            }
                                        } elseif ($i >= $ranges[0] || $i <= $ranges[1]) {
                                            $register[$s][$i] = true;
                                        }
                                    }
                                }
                            }

                            continue;
                        }

                        // list segment cannot be parsed
                        $result = false;
                        break 1;
                    }
                }

                if ($result === true) {
                    if (isset($register[4][7])) {
                        $register[4][0] = true;
                    }

                    $this->register = $register;
                }
            }
        }

        return $result;
    }

    /**
     * Match current or given timestamp against cron expression
     *
     * @param int $timestamp
     * @return bool
     */
    public function matching($timestamp = null)
    {
        $result = false;

        if ($this->valid()) {
            $dt = new \DateTime('now', $this->timezone);

            if ($timestamp !== null) {
                $dt->setTimestamp((int)$timestamp);
            }

            list($minute, $hour, $day, $month, $weekday) = sscanf($dt->format('i G j n w'), '%d %d %d %d %d');

            if (isset($this->register[4][(int)$weekday]) &&
                isset($this->register[3][(int)$month]) &&
                isset($this->register[2][(int)$day]) &&
                isset($this->register[1][(int)$hour]) &&
                isset($this->register[0][(int)$minute])) {

                $result = true;
            }
        }

        return $result;
    }
}
