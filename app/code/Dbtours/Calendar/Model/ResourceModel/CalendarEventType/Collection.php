<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model\ResourceModel\CalendarEventType;

use Dbtours\Calendar\Model\CalendarEventType as ModelCalendarEventType;
use Dbtours\Calendar\Model\ResourceModel\CalendarEventType as ResourceModelCalendarEventType;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(ModelCalendarEventType::class, ResourceModelCalendarEventType::class);
    }
}
