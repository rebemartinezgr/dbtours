<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Sales\Service;

use Dbtours\Booking\Service\BookingManager;
use Dbtours\Calendar\Service\CalendarManager;
use Magento\Sales\Api\Data\OrderItemInterface;
use Dbtours\TourEvent\Helper\Option;

/**
 * Class OrderItem
 */
class OrderItem
{
    /**
     * @var CalendarManager $calendarManager
     */
    private $calendarManager;

    /**
     * @var BookingManager
     */
    private $bookingManager;

    /**
     * @var Option
     */
    private $option;

    /**
     * OrderItem constructor.
     * @param CalendarManager $calendarManager
     * @param BookingManager $bookingManager
     * @param Option $option
     */
    public function __construct(
        CalendarManager $calendarManager,
        BookingManager $bookingManager,
        Option $option
    ) {
        $this->calendarManager  = $calendarManager;
        $this->bookingManager   = $bookingManager;
        $this->option           = $option;
    }

    /**
     * @param OrderItemInterface $orderItem
     */
    public function execute($orderItem)
    {
        try {
            // $tourEventLanguage == null => if item has not tour event options
            // $tourEventLanguage == false => tour event does not exist any more
            $tourEventLanguage = $this->option->getTourEventLanguage($orderItem);
            if ($tourEventLanguage) {
                $calendarEvents = $this->calendarManager->getCalendarEvents($tourEventLanguage, $orderItem);
                $booking        = $this->bookingManager->getBooking($tourEventLanguage, $orderItem);
                $guide          = $tourEventLanguage->getAvailableGuides();
                if (is_array($guide)) {
                    $guide = $guide[0];
                }
                $this->bookingManager->assignToGuide($booking, $guide);
                $this->calendarManager->assignToGuide($calendarEvents, $guide);
            }
        } catch (\Exception $e) {
            /** notify error for order Item */
        }
    }
}
