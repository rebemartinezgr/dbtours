<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model\ResourceModel;

use Dbtours\Calendar\Api\Data\CalendarEventInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class CalendarEvent
 */
class CalendarEvent extends AbstractDb
{
    /**
     * Initialize Resource Model
     */
    protected function _construct()
    {
        $this->_init(CalendarEventInterface::TABLE, CalendarEventInterface::ID);
    }
}