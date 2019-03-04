<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Sales\Observer;

use Dbtours\Sales\Service\Order\CancelItem as OrderItem;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

/**
 * Class CancelObserver
 */
class CancelObserver implements ObserverInterface
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
     * @event sales_model_service_quote_submit_success
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var  OrderInterface $order */
        $order  = $observer->getEvent()->getOrder();
        /** @var  OrderItemInterface $orderItem */
        foreach ($order->getAllItems() as $orderItem) {
            $this->orderItemService->execute($orderItem);
        }
    }
}
