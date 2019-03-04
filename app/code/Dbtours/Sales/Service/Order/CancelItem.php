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
 * Class CancelItem
 */
class CancelItem
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
            $extensionAttributes = $orderItem->getExtensionAttributes();
            if ($extensionAttributes) {
                $booking = $extensionAttributes->getBooking();
                $this->bookingManager->cancelBooking($booking);
            }
        } catch (\Exception $e) {
            $this->logger->error("Dbtours\Sales\Service\Order\Item\CancelItem::execute() : " . $e->getMessage());
            /** TODO notify error */
        }
    }
}
