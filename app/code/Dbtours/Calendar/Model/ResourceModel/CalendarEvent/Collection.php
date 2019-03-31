<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model\ResourceModel\CalendarEvent;

use Dbtours\Booking\Api\Data\BookingInterface;
use Dbtours\Calendar\Api\Data\CalendarEventTypeInterface;
use Dbtours\Guide\Api\Data\GuideInterface;
use Dbtours\Calendar\Model\CalendarEvent as ModelCalendarEvent;
use Dbtours\Calendar\Model\ResourceModel\CalendarEvent as ResourceModelCalendarEvent;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dbtours\Calendar\Api\Data\CalendarEventInterface;

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

    public function getFullInfo()
    {
        $eventTableAlias   = 'main_table';
        $bookingTableAlias = 'booking';
        $eventTypeAlias    = 'event_type';
        $guideAlias        = 'guide';

        $this->getSelect()->joinLeft(
            [$bookingTableAlias => BookingInterface::TABLE],
            $eventTableAlias .
            '.' .
            CalendarEventInterface::ORDER_ITEM_ID .
            '=' .
            $bookingTableAlias .
            '.' .
            BookingInterface::ORDER_ITEM_ID,
            [
                CalendarEventInterface::GUIDE_ID      => $eventTableAlias . '.' . CalendarEventInterface::GUIDE_ID,
                CalendarEventInterface::START         => $eventTableAlias . '.' . CalendarEventInterface::START,
                CalendarEventInterface::FINISH        => $eventTableAlias . '.' . CalendarEventInterface::FINISH,
                CalendarEventInterface::ORDER_ITEM_ID => $eventTableAlias . '.' . CalendarEventInterface::ORDER_ITEM_ID,
                CalendarEventInterface::TYPE_ID       => $eventTableAlias . '.' . CalendarEventInterface::TYPE_ID,
                BookingInterface::TOUR            => $bookingTableAlias . '.' . BookingInterface::TOUR,
                BookingInterface::LANGUAGE        => $bookingTableAlias . '.' . BookingInterface::LANGUAGE
            ]
        )->joinLeft(
            [$eventTypeAlias => CalendarEventTypeInterface::TABLE],
            $eventTableAlias .
            '.' .
            CalendarEventInterface::TYPE_ID .
            '=' .
            $eventTypeAlias .
            '.' .
            CalendarEventTypeInterface::ID,
            [
                CalendarEventTypeInterface::CODE => $eventTypeAlias . '.' . CalendarEventTypeInterface::CODE,
                'color' => $eventTypeAlias . '.' . CalendarEventTypeInterface::COLOR
            ]
        )->joinLeft(
            [$guideAlias => GuideInterface::TABLE],
            $eventTableAlias .
            '.' .
            CalendarEventInterface::GUIDE_ID .
            '=' .
            $guideAlias .
            '.' .
            GuideInterface::ID,
            [
                GuideInterface::FIRSTNAME => GuideInterface::FIRSTNAME,
                GuideInterface::LASTNAME => GuideInterface::LASTNAME
            ]
        );

        return $this;
    }
}
