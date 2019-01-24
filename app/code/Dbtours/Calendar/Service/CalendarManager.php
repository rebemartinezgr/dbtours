<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Service;

use Dbtours\Calendar\Api\CalendarEventRepositoryInterface;
use Dbtours\Calendar\Api\Data\CalendarEventInterface;
use Dbtours\Calendar\Api\Data\CalendarEventInterfaceFactory;
use Dbtours\Catalog\Model\Product\Option\Type\TourEvent;
use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface as TourEventLanguage;
use Dbtours\TourEvent\Model\TourEventLanguageRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\Datetime;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

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
     * @var TourEventLanguageRepository
     */
    private $tourEventLanguageRepository;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * BookingManager constructor.
     * @param CalendarEventRepositoryInterface $calendarEventRepository
     * @param CalendarEventInterfaceFactory $calendarEventFactory
     * @param TourEventLanguageRepository $tourEventLanguageRepository
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        CalendarEventRepositoryInterface $calendarEventRepository,
        CalendarEventInterfaceFactory $calendarEventFactory,
        TourEventLanguageRepository $tourEventLanguageRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->calendarEventRepository      = $calendarEventRepository;
        $this->calendarEventFactory         = $calendarEventFactory;
        $this->tourEventLanguageRepository  = $tourEventLanguageRepository;
        $this->orderRepository              = $orderRepository;
    }

    /**
     * @param OrderInterface $order
     */
    public function addCalendarEventsFromOrder($order)
    {
        $added = false;
        foreach ($order->getItems() as $item) {
            $options = $item->getProductOptions();
            if (!isset($options['options'])) {
                continue;
            }
            foreach ($options['options'] as $option) {
                if (!(isset($option['option_type']) && $option['option_type'] == TourEvent::OPTION_TYPE_NAME)) {
                    continue;
                }
                try {
                    $tourEventLanguage = $this->getTourEventLanguageFromOption($option['option_value']);
                    $guide = $tourEventLanguage->getAvailableGuides();
                    if ($guide) {
                        $guide = $guide[0];
                    }

                    $commonData = [
                        CalendarEventInterface::ORDER_ITEM_ID   => $item->getItemId(),
                        CalendarEventInterface::GUIDE_ID        => $guide
                    ];

                    /** Create calendar event type booking for tour event*/
                    $data = $this->getTourEventTimes($tourEventLanguage);
                    $this->createCalendarEvent(array_merge($data, $commonData));

                    /** Create calendar event type journey before the tour event if needed */
                    if ($tourEventLanguage->getBlockedBefore()) {
                        $data = $this->getBlockedBeforeTimes($tourEventLanguage);
                        $this->createCalendarEvent(array_merge($data, $commonData));
                    }

                    /** Create calendar event type journey after the tour event if needed*/
                    if ($tourEventLanguage->getBlockedAfter()) {
                        $data = $this->getBlockedAfterTimes($tourEventLanguage);
                        $this->createCalendarEvent(array_merge($data, $commonData));
                    }
                } catch (\Exception $e) {
                    $added = false;
                }
            }
            if (!$added) {
                $order->setStatus('pending_assignment');
                $this->orderRepository->save($order);
            }
        }
    }

    /**
     * @param $data
     */
    private function createCalendarEvent($data)
    {
        /** @var CalendarEventInterface $calendarEvent */
        $calendarEvent = $this->calendarEventFactory->create();
        $calendarEvent->setOrderItemId($data[CalendarEventInterface::ORDER_ITEM_ID]);
        $calendarEvent->setStartTime($data[CalendarEventInterface::START]);
        $calendarEvent->setFinishTime($data[CalendarEventInterface::FINISH]);
        $calendarEvent->setGuideId($data[CalendarEventInterface::GUIDE_ID]);
        $this->calendarEventRepository->save($calendarEvent);
    }

    /**
     * @param TourEventLanguage $tourEventLanguage
     * @return array
     */
    private function getTourEventTimes($tourEventLanguage)
    {
        return [
            CalendarEventInterface::START       => $tourEventLanguage->getStartTime(),
            CalendarEventInterface::FINISH      => $tourEventLanguage->getFinishTime(),
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
            CalendarEventInterface::START       => $startTimeBefore,
            CalendarEventInterface::FINISH      => $tourEventLanguage->getStartTime(),
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
            CalendarEventInterface::START       => $tourEventLanguage->getFinishTime(),
            CalendarEventInterface::FINISH      => $finishTimeAfter,
        ];
    }

    /**
     * @param $optionValue
     * @return TourEventLanguage
     * @throws NoSuchEntityException
     */
    private function getTourEventLanguageFromOption($optionValue)
    {
        $value             = json_decode($optionValue, true);
        $tourEventId       = $value[TourEventLanguage::TOUR_EVENT_ID];
        $languageCode      = $value[TourEventLanguage::LANGUAGE_CODE];
        $tourEventLanguage = $this->tourEventLanguageRepository
            ->getByIdAndLanguage($tourEventId, $languageCode);

        return $tourEventLanguage;
    }

    /**
     * @param $tourEventLanguage
     * @return string
     * @throws \Zend_Date_Exception
     */
    private function getStartTimeBlockedBefore($tourEventLanguage)
    {
        $startTime  = $tourEventLanguage->getStartTime();
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
        $finishTime = $tourEventLanguage->getFinishTime();
        $finishTimeAfter = new \Zend_Date($finishTime, Datetime::DATETIME_INTERNAL_FORMAT);
        $finishTimeAfter->addMinute($tourEventLanguage->getBlockedAfter());

        return $finishTimeAfter->toString(Datetime::DATETIME_INTERNAL_FORMAT);
    }
}
