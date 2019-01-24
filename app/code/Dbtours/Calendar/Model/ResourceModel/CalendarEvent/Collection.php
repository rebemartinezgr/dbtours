<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model\ResourceModel\CalendarEvent;

use Dbtours\Calendar\Model\CalendarEvent as ModelCalendarEvent;
use Dbtours\Calendar\Model\ResourceModel\CalendarEvent as ResourceModelCalendarEvent;
use Dbtours\Calendar\Model\CalendarEvent;
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
        $this->_init(ModelCalendarEvent::class, ResourceModelCalendarEvent::class);
    }
}
