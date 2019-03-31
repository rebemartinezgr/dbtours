<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Service;

use Dbtours\Booking\Api\Data\BookingInterface;
use Dbtours\Calendar\Api\CalendarEventRepositoryInterface;
use Dbtours\Calendar\Api\Config\Db\CalendarEvent\GeneralInterface;
use Dbtours\Calendar\Api\Data\CalendarEventInterface;
use Dbtours\Calendar\Api\Data\CalendarEventInterfaceFactory;
use Dbtours\Calendar\Api\Data\TourEventLanguageInterface as TourEventLanguage;
use Magento\Framework\Stdlib\Datetime;

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
     * @var GeneralInterface
     */
    private $generalConfig;

    /**
     * CalendarManager constructor.
     * @param CalendarEventRepositoryInterface $calendarEventRepository
     * @param CalendarEventInterfaceFactory $calendarEventFactory
     * @param GeneralInterface $generalConfig
     */
    public function __construct(
        CalendarEventRepositoryInterface $calendarEventRepository,
        CalendarEventInterfaceFactory $calendarEventFactory,
        GeneralInterface $generalConfig
    ) {
        $this->calendarEventRepository = $calendarEventRepository;
        $this->calendarEventFactory    = $calendarEventFactory;
        $this->generalConfig           = $generalConfig;
    }

    /**
     * @param BookingInterface $booking
     * @throws \Zend_Date_Exception
     */
    public function addCalendarEvents($booking)
    {
        if ($booking->getGuideId()) {
            $calendarEvents = $this->getCalendarEvents($booking, $booking->getOrderItem());
            $this->assignToGuide($calendarEvents, $booking->getGuideId());
        }
    }

    /**
     * @param BookingInterface $booking
     */
    public function adjustCalendarEvents($booking)
    {
        try {
            $orderItem = $booking->getOrderItem();
            if ($orderItem) {
                // todo CONTROL ONE TRANSACTION
                $this->calendarEventRepository->deleteByOrderItemId($orderItem);
                if (!$booking->isDeleted()) {
                    $this->addCalendarEvents($booking);
                }
            }
        } catch (\Exception $e) {
            // todo log
        }
    }

    /**
     * @param TourEventLanguage | BookingInterface $object
     * @param int $orderItemId
     * @return array
     * @throws \Zend_Date_Exception
     */
    public function getCalendarEvents($object, $orderItemId)
    {
        $newCalendarEvents = [];
        if (!$object) {
            return $newCalendarEvents;
        }

        $commonData = [
            CalendarEventInterface::ORDER_ITEM_ID => $orderItemId,
        ];

        /** Create calendar event type booking for tour event*/
        $data                = $this->getTourEventTimes($object);
        $newCalendarEvents[] = $this->createCalendarEvent(array_merge($data, $commonData));

        /** Create calendar event type journey before the tour event if needed */
        if ($object->getBlockedBefore()) {
            $data                = $this->getBlockedBeforeTimes($object);
            $newCalendarEvents[] = $this->createCalendarEvent(array_merge($data, $commonData));
        }

        /** Create calendar event type journey after the tour event if needed*/
        if ($object->getBlockedAfter()) {
            $data                = $this->getBlockedAfterTimes($object);
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
        $calendarEvent->setTypeId($data[CalendarEventInterface::TYPE_ID] ?? null);

        return $calendarEvent;
    }

    /**
     * @param TourEventLanguage | BookingInterface $object
     * @return array
     */
    private function getTourEventTimes($object)
    {
        return [
            CalendarEventInterface::START  => $object->getStartTime(),
            CalendarEventInterface::FINISH => $object->getFinishTime(),
            CalendarEventInterface::TYPE_ID => $this->generalConfig->getBookingCalendarEvent()
        ];
    }

    /**
     * @param TourEventLanguage | BookingInterface $object
     * @return array
     * @throws \Zend_Date_Exception
     */
    private function getBlockedBeforeTimes($object)
    {
        $startTimeBefore = $this->getStartTimeBlockedBefore($object);
        return [
            CalendarEventInterface::START  => $startTimeBefore,
            CalendarEventInterface::FINISH => $object->getStartTime(),
            CalendarEventInterface::TYPE_ID => $this->generalConfig->getTransferCalendarEvent()
        ];
    }

    /**
     * @param TourEventLanguage | BookingInterface $object
     * @return array
     * @throws \Zend_Date_Exception
     */
    private function getBlockedAfterTimes($object)
    {
        $finishTimeAfter = $this->getFinishTimeBlockedAfter($object);
        return [
            CalendarEventInterface::START  => $object->getFinishTime(),
            CalendarEventInterface::FINISH => $finishTimeAfter,
            CalendarEventInterface::TYPE_ID => $this->generalConfig->getTransferCalendarEvent()
        ];
    }

    /**
     * @param $object
     * @return string
     * @throws \Zend_Date_Exception
     */
    private function getStartTimeBlockedBefore($object)
    {
        $startTime       = $object->getStartTime();
        $startTimeBefore = new \Zend_Date($startTime, Datetime::DATETIME_INTERNAL_FORMAT);
        $startTimeBefore->subMinute($object->getBlockedBefore());

        return $startTimeBefore->toString(Datetime::DATETIME_INTERNAL_FORMAT);
    }

    /**
     * @param $object
     * @return string
     * @throws \Zend_Date_Exception
     */
    private function getFinishTimeBlockedAfter($object)
    {
        $finishTime      = $object->getFinishTime();
        $finishTimeAfter = new \Zend_Date($finishTime, Datetime::DATETIME_INTERNAL_FORMAT);
        $finishTimeAfter->addMinute($object->getBlockedAfter());

        return $finishTimeAfter->toString(Datetime::DATETIME_INTERNAL_FORMAT);
    }
}
