<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Observer;

use Dbtours\Booking\Api\Data\BookingInterface;
use Dbtours\Booking\Service\BookingManager;
use Dbtours\Calendar\Service\CalendarManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class BookingObserver
 */
class BookingObserver implements ObserverInterface
{
    /**
     * @var CalendarManager
     */
    private $calendarManager;

    /**
     * @var BookingManager
     */
    private $bookingManager;

    /**
     * BookingObserver constructor.
     * @param CalendarManager $calendarManager
     * @param BookingManager $bookingManager
     */
    public function __construct(
        CalendarManager $calendarManager,
        BookingManager $bookingManager
    ) {
        $this->calendarManager = $calendarManager;
        $this->bookingManager  = $bookingManager;
    }

    /**
     * @event dbtours_booking_save_after
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $data = $observer->getData();
        if (isset($data['object'])) {
            /** @var  BookingInterface $booking */
            $booking = $data['object'];
            if ($booking->getId() &&
                !$booking->isObjectNew() &&
                $this->bookingManager->shouldAdjustCalendar($booking)) {
                $this->calendarManager->adjustCalendarEvents($booking);
            }
        }
    }
}
