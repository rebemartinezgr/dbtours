<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Booking\Controller\Adminhtml\Booking;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Ui\Component\MassAction\Filter;
use Dbtours\Booking\Api\BookingRepositoryInterface;
use Dbtours\Booking\Controller\Adminhtml\Booking as ControllerBooking;
use Dbtours\Booking\Model\ResourceModel\Booking\CollectionFactory;

/**
 * Class MassDelete
 */
class MassDelete extends ControllerBooking
{
    /**
     * Mass Action Filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var BookingRepositoryInterface
     */
    protected $bookingRepository;

    /**
     * constructor
     *
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param BookingRepositoryInterface $bookingRepository
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        BookingRepositoryInterface $bookingRepository,
        Context $context,
        Registry $coreRegistry
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->bookingRepository = $bookingRepository;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * @return ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): ResultInterface
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $delete = 0;
        if ($collection->count() > 0) {
            foreach ($collection as $item) {
                $this->bookingRepository->delete($item);
                $delete++;
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $delete));
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
