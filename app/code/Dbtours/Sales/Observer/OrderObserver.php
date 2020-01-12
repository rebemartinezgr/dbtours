<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Sales\Observer;

use Dbtours\Sales\Service\Order\NewItem as NewOrderItemService;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

/**
 * Class OrderObserver
 */
class OrderObserver implements ObserverInterface
{
    /**
     * @var NewOrderItemService
     */
    private $newOrderItemService;

    /**
     * OrderObserver constructor.
     * @param NewOrderItemService $newOrderItemService
     */
    public function __construct(
        NewOrderItemService $newOrderItemService
    ) {
        $this->newOrderItemService = $newOrderItemService;
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
            $this->newOrderItemService->execute($orderItem);
        }
    }
}
