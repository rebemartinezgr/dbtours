<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Sales\Observer;

use Dbtours\Sales\Service\OrderItem;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

/**
 * Class OrderItemObserver
 */
class OrderItemObserver implements ObserverInterface
{
    /**
     * @var OrderItem
     */
    private $orderItemService;

    /**
     * NewBooking constructor.
     * @param OrderItem $orderItemService
     */
    public function __construct(
        OrderItem $orderItemService
    ) {
        $this->orderItemService = $orderItemService;
    }

    /**
     * @event sales_order_item_save_after
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var  OrderItemInterface $orderItem */
        $orderItem      = $observer->getEvent()->getItem();

        /** TODO: difference when is new item than existing one */
        $this->orderItemService->execute($orderItem);
    }
}
