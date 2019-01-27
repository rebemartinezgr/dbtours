<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Service;

use Dbtours\Calendar\Api\CalendarEventRepositoryInterface;
use Dbtours\Calendar\Api\Data\CalendarEventInterface;
use Dbtours\Calendar\Api\Data\CalendarEventInterfaceFactory;
use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface as TourEventLanguage;
use Magento\Framework\Stdlib\Datetime;
use Magento\Sales\Api\Data\OrderItemInterface;

/**
 * Class CalendarManager
 */
class CalendarManager
{
    /**
     * @var CalendarEventRepositoryInterface
     */
    private $calendarEventRepository;

    /**
     * @var CalendarEventInterfaceFactory
     */
    private $calendarEventFactory;

    /**
     * BookingManager constructor.
     * @param CalendarEventRepositoryInterface $calendarEventRepository
     * @param CalendarEventInterfaceFactory $calendarEventFactory
     */
    public function __construct(
        CalendarEventRepositoryInterface $calendarEventRepository,
        CalendarEventInterfaceFactory $calendarEventFactory
    ) {
        $this->calendarEventRepository = $calendarEventRepository;
        $this->calendarEventFactory    = $calendarEventFactory;
    }

    /**
     * @param TourEventLanguage $tourEventLanguage
     * @param OrderItemInterface $orderItem
     * @return array
     * @throws \Zend_Date_Exception
     */
    public function getCalendarEvents($tourEventLanguage, $orderItem)
    {
        $newCalendarEvents = [];
        if (!$tourEventLanguage) {
            return $newCalendarEvents;
        }
        $commonData = [
            CalendarEventInterface::ORDER_ITEM_ID => $orderItem->getItemId(),
        ];

        /** Create calendar event type booking for tour event*/
        $data                = $this->getTourEventTimes($tourEventLanguage);
        $newCalendarEvents[] = $this->createCalendarEvent(array_merge($data, $commonData));

        /** Create calendar event type journey before the tour event if needed */
        if ($tourEventLanguage->getBlockedBefore()) {
            $data                = $this->getBlockedBeforeTimes($tourEventLanguage);
            $newCalendarEvents[] = $this->createCalendarEvent(array_merge($data, $commonData));
        }

        /** Create calendar event type journey after the tour event if needed*/
        if ($tourEventLanguage->getBlockedAfter()) {
            $data                = $this->getBlockedAfterTimes($tourEventLanguage);
            $newCalendarEvents[] = $this->createCalendarEvent(array_merge($data, $commonData));
        }

        return $newCalendarEvents;
    }

    /**
     * @param array $calendarEvents
     * @param $guideId
     */
    public function assignToGuide(array $calendarEvents, $guideId)
    {
        /** @var  CalendarEventInterface $calendarEvent */
        foreach ($calendarEvents as $calendarEvent) {
            $calendarEvent->setGuideId($guideId);
            $this->calendarEventRepository->save($calendarEvent);
        }
    }

    /**
     * @param $data
     * @return CalendarEventInterface
     */
    private function createCalendarEvent($data)
    {
        /** @var CalendarEventInterface $calendarEvent */
        $calendarEvent = $this->calendarEventFactory->create();
        $calendarEvent->setOrderItemId($data[CalendarEventInterface::ORDER_ITEM_ID] ?? '');
        $calendarEvent->setStartTime($data[CalendarEventInterface::START] ?? '');
        $calendarEvent->setFinishTime($data[CalendarEventInterface::FINISH] ?? '');

        return $calendarEvent;
    }

    /**
     * @param TourEventLanguage $tourEventLanguage
     * @return array
     */
    private function getTourEventTimes($tourEventLanguage)
    {
        return [
            CalendarEventInterface::START  => $tourEventLanguage->getStartTime(),
            CalendarEventInterface::FINISH => $tourEventLanguage->getFinishTime(),
        ];
    }

    /**
     * @param TourEventLanguage $tourEventLanguage
     * @return array
     * @throws \Zend_Date_Exception
     */
    private function getBlockedBeforeTimes($tourEventLanguage)
    {
        $startTimeBefore = $this->getStartTimeBlockedBefore($tourEventLanguage);
        return [
            CalendarEventInterface::START  => $startTimeBefore,
            CalendarEventInterface::FINISH => $tourEventLanguage->getStartTime(),
        ];
    }

    /**
     * @param TourEventLanguage $tourEventLanguage
     * @return array
     * @throws \Zend_Date_Exception
     */
    private function getBlockedAfterTimes($tourEventLanguage)
    {
        $finishTimeAfter = $this->getFinishTimeBlockedAfter($tourEventLanguage);
        return [
            CalendarEventInterface::START  => $tourEventLanguage->getFinishTime(),
            CalendarEventInterface::FINISH => $finishTimeAfter,
        ];
    }

    /**
     * @param $tourEventLanguage
     * @return string
     * @throws \Zend_Date_Exception
     */
    private function getStartTimeBlockedBefore($tourEventLanguage)
    {
        $startTime       = $tourEventLanguage->getStartTime();
        $startTimeBefore = new \Zend_Date($startTime, Datetime::DATETIME_INTERNAL_FORMAT);
        $startTimeBefore->subMinute($tourEventLanguage->getBlockedBefore());

        return $startTimeBefore->toString(Datetime::DATETIME_INTERNAL_FORMAT);
    }

    /**
     * @param $tourEventLanguage
     * @return string
     * @throws \Zend_Date_Exception
     */
    private function getFinishTimeBlockedAfter($tourEventLanguage)
    {
        $finishTime      = $tourEventLanguage->getFinishTime();
        $finishTimeAfter = new \Zend_Date($finishTime, Datetime::DATETIME_INTERNAL_FORMAT);
        $finishTimeAfter->addMinute($tourEventLanguage->getBlockedAfter());

        return $finishTimeAfter->toString(Datetime::DATETIME_INTERNAL_FORMAT);
    }
}
