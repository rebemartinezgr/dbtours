<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Ui\Component\Listing\Column;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;

/**
 * Class Order
 */
class Order extends Column
{
    private $orderItemRepository;

    /**
     * Order constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     * @param OrderItemRepositoryInterface $orderItemRepository
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        OrderItemRepositoryInterface $orderItemRepository,
        array $components = [],
        array $data = []
    ) {
        $this->orderItemRepository = $orderItemRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    $orderItemId = $item[$fieldName];
                    $item[$fieldName] = $this->getOrderLink($orderItemId);
                }
            }
        }

        return $dataSource;
    }

    /**
     * @param int $orderItemId
     * @return string
     */
    private function getOrderLink($orderItemId)
    {
        /** @var OrderInterface $order */
        $order = $this->getOrder($orderItemId);
        $url = $this->context->getUrl('sales/order/view', ['order_id' => $order->getEntityId()]);
        $html = "<a href='" . $url . "'>";
        $html .= $order->getIncrementId();
        $html .= "</a>";

        return $html;
    }

    /**
     * @param $orderItemId
     * @return OrderInterface
     */
    private function getOrder($orderItemId)
    {

        $orderItem = $this->orderItemRepository->get($orderItemId);

        return $orderItem->getOrder();
    }
}