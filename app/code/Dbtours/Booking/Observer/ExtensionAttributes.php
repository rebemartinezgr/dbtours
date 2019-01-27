<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Dbtours\Booking\Api\BookingRepositoryInterface;
use Dbtours\Booking\Api\Data\BookingInterfaceFactory;
use Dbtours\Booking\Api\Data\BookingInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

/**
 * Class ExtensionAttributes
 */
class ExtensionAttributes implements ObserverInterface
{

    /**
     * @var BookingInterfaceFactory
     */
    private $bookingFactory;

    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;

    /**
     * ExtensionAttributes constructor.
     * @param BookingInterfaceFactory $bookingFactory
     * @param BookingRepositoryInterface $bookingRepository
     */
    public function __construct(
        BookingInterfaceFactory $bookingFactory,
        BookingRepositoryInterface $bookingRepository
    ) {
        $this->bookingFactory       = $bookingFactory;
        $this->bookingRepository    = $bookingRepository;
    }

    /**
     * @event sales_order_save_after
     * @param Observer $observer
     * @return bool|void
     */
    public function execute(Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getEvent()->getOrder();
        /** @var OrderItemInterface $item */
        foreach ($order->getAllItems() as $item) {
            try {
                $booking = $this->bookingRepository->get($item->getItemId(), BookingInterface::ORDER_ITEM_ID);
            } catch (NoSuchEntityException $e) {
                $booking = $this->bookingFactory->create();
            }

            try {
                $booking->setOrderItem($item->getItemId());
                $booking->setGuideId('1');
                $booking->setFinishTime('2018-05-09');
                $booking->setStartTime('2018-05-09');
                $booking->setLanguage('ca');

                $this->bookingRepository->save($booking);
            } catch (\Exception $e) {
                return false;
            }
        }
    }
}
