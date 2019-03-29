<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model\ResourceModel;

use Dbtours\Calendar\Api\Data\CalendarEventTypeInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class CalendarEventType
 */
class CalendarEventType extends AbstractDb
{
    /**
     * Initialize Resource Model
     */
    protected function _construct()
    {
        $this->_init(CalendarEventTypeInterface::TABLE, CalendarEventTypeInterface::ID);
    }
}