<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Base\Helper;

use Zend_Date;

/**
 * Class Date
 */
class Date
{
    /**
     * @param $date
     * @return string|Zend_Date
     * @throws \Zend_Date_Exception
     */
    public function convertToDBTimeZone($date)
    {
        $date = new Zend_Date($date);
        $date->setTimezone('Europe/Madrid');
        $date = $date->get(\Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT);

        return $date;
    }

    /**
     * @param $date
     * @return string|Zend_Date
     * @throws \Zend_Date_Exception
     */
    public function convertFromDBTimeZone($date)
    {
        $newDate = new Zend_Date($date);
        $newDate->setTimezone('Europe/Madrid');
        $offSet = $newDate->getGmtOffset();

        $date = new Zend_Date($date);
        $date->addSecond($offSet);
        $date = $date->get(\Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT);

        return $date;
    }
}
