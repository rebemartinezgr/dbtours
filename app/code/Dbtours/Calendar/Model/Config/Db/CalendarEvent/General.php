<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model\Config\Db\CalendarEvent;

use Dbtours\Calendar\Api\Config\Db\CalendarEvent\GeneralInterface;
use Dbtours\Calendar\Model\Config\Db\CalendarEvent;

/**
 * Class General
 */
class General extends CalendarEvent implements GeneralInterface
{
    /**
     * @return string
     */
    public function getXmlBaseGroupPath()
    {
        return  $this->getXmlBasePath() . '/' . $this->getXmlGroupPath() . '/';
    }

    /**
     * @inheritdoc
     */
    public function getXmlGroupPath()
    {
        return self::XML_GROUP_PATH;
    }

    /**
     * @inheritdoc
     */
    public function getBookingCalendarEvent($storeId = null): int
    {
        return (int)$this->scopeConfig->getValue($this->getXmlBaseGroupPath() . 'booking_event_type');
    }
}
