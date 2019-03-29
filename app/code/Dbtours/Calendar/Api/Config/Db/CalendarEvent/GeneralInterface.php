<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Api\Config\Db\CalendarEvent;

use Dbtours\Calendar\Api\Config\Db\CalendarEventInterface;

/**
 * Interface GeneralInterface
 */
interface GeneralInterface extends CalendarEventInterface
{
    const XML_GROUP_PATH = 'general';

    /**
     * @param int $storeId
     * @return int
     */
    public function getBookingCalendarEvent($storeId = null): int;
}
