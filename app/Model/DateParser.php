<?php

namespace Model;

use DateTime;

/**
 * Date parser model
 *
 * @package  model
 * @author   Frederic Guillot
 */
class DateParser extends Base
{
    /**
     * Return a timestamp if the given date format is correct otherwise return 0
     *
     * @access public
     * @param  string   $value  Date to parse
     * @param  string   $format Date format
     * @return integer
     */
    public function getValidDate($value, $format)
    {
        $date = DateTime::createFromFormat($format, $value);

        if ($date !== false) {
            $errors = DateTime::getLastErrors();
            if ($errors['error_count'] === 0 && $errors['warning_count'] === 0) {
                $timestamp = $date->getTimestamp();
                return $timestamp > 0 ? $timestamp : 0;
            }
        }

        return 0;
    }

    /**
     * Parse a date ad return a unix timestamp, try different date formats
     *
     * @access public
     * @param  string   $value   Date to parse
     * @return integer
     */
    public function getTimestamp($value)
    {
        foreach ($this->getDateFormats() as $format) {

            $timestamp = $this->getValidDate($value, $format);

            if ($timestamp !== 0) {
                return $timestamp;
            }
        }

        return 0;
    }

    /**
     * Return the list of supported date formats
     *
     * @access public
     * @return array
     */
    public function getDateFormats()
    {
        return array(
            t('m/d/Y'),
            'Y-m-d',
            'Y_m_d',
        );
    }

    /**
     * For a given timestamp, reset the date to midnight
     *
     * @access public
     * @param  integer    $timestamp    Timestamp
     * @return integer
     */
    public function resetDateToMidnight($timestamp)
    {
        return mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp), date('Y', $timestamp));
    }
}