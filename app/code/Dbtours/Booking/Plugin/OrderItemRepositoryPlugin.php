<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Plugin;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Model\Order;
use Dbtours\Booking\Api\BookingRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection as ItemCollection;

/**
 * Class OrderItemRepositoryPlugin
 */
class OrderItemRepositoryPlugin
{
    /**
     * @var OrderItemExtensionFactory
     */
    private $extensionFactory;

    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;

    /**
     * OrderRepositoryPlugin constructor.
     * @param OrderItemExtensionFactory $extensionFactory
     * @param BookingRepositoryInterface $bookingRepository
     */
    public function __construct(
        OrderItemExtensionFactory $extensionFactory,
        BookingRepositoryInterface $bookingRepository
    ) {
        $this->extensionFactory     = $extensionFactory;
        $this->bookingRepository    = $bookingRepository;
    }

    /**
     * @param Order $subject
     * @param ItemCollection $orderItemCollection
     * @return ItemCollection
     */
    public function afterGetItemsCollection(Order $subject, ItemCollection $orderItemCollection)
    {
        foreach ($orderItemCollection as $orderItem) {
            $extensionAttributes = $orderItem->getExtensionAttributes() ?: $this->extensionFactory->create();
            try {
                $booking = $this->bookingRepository->get($orderItem->getItemId(), 'order_item_id');
                $extensionAttributes->setBooking($booking);
                $orderItem->setExtensionAttributes($extensionAttributes);
            } catch (NoSuchEntityException $e) {
                continue;
            }
        }

        return $orderItemCollection;
    }
}
