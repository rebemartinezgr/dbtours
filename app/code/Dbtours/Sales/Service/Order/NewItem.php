<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Sales\Service\Order;

use Dbtours\Booking\Service\BookingManager;
use Dbtours\Calendar\Service\CalendarManager;
use Dbtours\Sales\Service\Order\Status as OrderStatus;
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
     * @var Status
     */
    private $orderStatus;

    /**
     * NewItem constructor.
     * @param CalendarManager $calendarManager
     * @param BookingManager $bookingManager
     * @param Option $option
     * @param Logger $logger
     * @param OrderStatus $orderStatus
     */
    public function __construct(
        CalendarManager $calendarManager,
        BookingManager $bookingManager,
        Option $option,
        Logger $logger,
        OrderStatus $orderStatus
    ) {
        $this->calendarManager = $calendarManager;
        $this->bookingManager  = $bookingManager;
        $this->option          = $option;
        $this->logger          = $logger;
        $this->orderStatus     = $orderStatus;
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
            $this->logger->error(__CLASS__ ."::" . __METHOD__ . " : " . $e->getMessage());
            $this->orderStatus->setUnassignedStatus($orderItem->getOrder(), $e->getMessage());
        }
    }
}
