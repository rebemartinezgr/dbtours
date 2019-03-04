<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Sales\Service\Order;

use Dbtours\Booking\Service\BookingManager;
use Dbtours\Calendar\Service\CalendarManager;
use Dbtours\TourEvent\Helper\Option;
use Dbtours\Base\Logger\Logger;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class NewItem
 */
class NewItem
{
    /**
     * @var Logger
     */
    private $logger;

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
     * @param Logger $logger
     */
    public function __construct(
        CalendarManager $calendarManager,
        BookingManager $bookingManager,
        Option $option,
        Logger $logger
    ) {
        $this->calendarManager = $calendarManager;
        $this->bookingManager  = $bookingManager;
        $this->option          = $option;
        $this->logger          = $logger;
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
            if ($tourEventLanguage === false) {
                throw new LocalizedException(
                    __(
                        "OrderItem %1 has been created without related booking",
                        $orderItem->getItemId()
                    )
                );
            }
            if ($tourEventLanguage) {
                $booking = $this->bookingManager->addNewBooking($tourEventLanguage, $orderItem);
                if (!$booking->getGuideId()) {
                    throw new LocalizedException(
                        __(
                            "Booking Id %1 has been created unassigned to any guide",
                            $booking->getEntityId()
                        )
                    );
                }
                $this->calendarManager->addCalendarEvents($booking);
            }
        } catch (\Exception $e) {
            $this->logger->error("Dbtours\Sales\Service\Order\Item\NewItem::execute() : " . $e->getMessage());
            /** TODO notify error */
        }
    }
}
