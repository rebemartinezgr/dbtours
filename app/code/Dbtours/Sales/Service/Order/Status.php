<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Sales\Service\Order;

use Dbtours\Sales\Api\Data\Order\Status as StatusInterface;
use Dbtours\Base\Logger\Logger;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderStatusHistoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status as SalesOrderStatus;
use Magento\Sales\Model\Order\Status\HistoryRepository;
use Magento\Sales\Model\ResourceModel\Status\CollectionFactory as StatusCollectionFactory;

/**
 * Class Status
 */
class Status
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var StatusCollection
     */
    protected $statusCollectionFactory;

    /**
     * @var HistoryRepository
     */
    private $historyRepository;

    /**
     * Status constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param StatusCollectionFactory $statusCollectionFactory
     * @param HistoryRepository $historyRepository
     * @param Logger $logger
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        StatusCollectionFactory $statusCollectionFactory,
        HistoryRepository $historyRepository,
        Logger $logger
    ) {
        $this->orderRepository         = $orderRepository;
        $this->statusCollectionFactory = $statusCollectionFactory;
        $this->historyRepository       = $historyRepository;
        $this->logger                  = $logger;
    }

    /**
     * @param OrderInterface $order
     * @param string $status
     * @param string $comment
     */
    public function update($order, $status, $comment = null)
    {
        try {
            $this->updateStatus($order, $status);
            if ($comment) {
                $this->addStatusHistoryComment($order, $comment);
            }
        } catch (\Exception $e) {
            $this->logger->error(__CLASS__ ."::" . __METHOD__ . " : " . $e->getMessage());
        }
    }

    /**
     * @param $order
     * @param null $comment
     */
    public function setUnassignedStatus($order, $comment = null)
    {
        $state  = $order->getState();
        $status = $this->getStatusCode('unassigned', $state);

        $this->update($order, $status, $comment);
    }

    /**
     * @param $order
     * @param null $comment
     */
    public function setPerformedStatus($order, $comment = null)
    {
        $state  = $order->getState();
        $status = $this->getStatusCode('partial_performed', $state);

        $this->update($order, $status, $comment);
    }

    /**
     * @param $order
     * @param null $comment
     */
    public function setPartialPerformedStatus($order, $comment = null)
    {
        $state  = $order->getState();
        $status = $this->getStatusCode('performed', $state);

        $this->update($order, $status, $comment);
    }

    /**
     * @param $newStatus
     * @param $currentState
     * @return string
     */
    private function getStatusCode($newStatus, $currentState)
    {
        switch ($newStatus) {
            case 'unassigned':
                return $currentState == 'new' ? StatusInterface::NEW_UNASSIGNED : StatusInterface::PROCESS_UNASSIGNED;
                break;
            case 'partial_performed':
                return $currentState == 'new' ? StatusInterface::NEW_PARTIAL_PERFORMED :
                    StatusInterface::PROCESS_PARTIAL_PERFORMED;
                break;
            case 'performed':
                return $currentState == 'new' ? StatusInterface::NEW_PERFORMED : StatusInterface::PROCESS_PERFORMED;
                break;
        }
    }

    /**
     * @param string $statusCode
     * @return SalesOrderStatus
     * @throws LocalizedException
     */
    private function getStatusModel($statusCode)
    {
        /** @var SalesOrderStatus $statusModel */
        $statusModel =
            $this->statusCollectionFactory->create()->addFieldToFilter('main_table.status', $statusCode)->getFirstItem(
            );

        if ($statusModel->isEmpty()) {
            throw new LocalizedException(__("Invalid status '%1'", $statusCode));
        }

        return $statusModel;
    }

    /**
     * @param OrderInterface $order
     * @param string $newStatus
     * @throws LocalizedException
     */
    private function updateStatus($order, $newStatus)
    {
        $statusModel = $this->getStatusModel($newStatus);
        $order->setStatus($newStatus);
        $order->setState($statusModel->getState());
        $this->orderRepository->save($order);
    }

    /**
     * @param $order
     * @param $comment
     * @throws CouldNotSaveException
     */
    private function addStatusHistoryComment($order, $comment)
    {
        /** @var OrderStatusHistoryInterface $history */
        $history = $order->addStatusHistoryComment($comment, false);
        $history->setIsVisibleOnFront(false);
        $this->historyRepository->save($history);
    }
}
