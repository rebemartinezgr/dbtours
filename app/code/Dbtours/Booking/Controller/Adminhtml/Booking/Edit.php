<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Booking\Controller\Adminhtml\Booking;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Dbtours\Booking\Controller\Adminhtml\Booking as ControllerBooking;
use Dbtours\Booking\Api\Data\BookingInterface as ModelBooking;
use Dbtours\Booking\Api\Data\BookingInterfaceFactory as BookingFactory;
use Dbtours\Booking\Api\BookingRepositoryInterface;

/**
 * Class Edit
 */
class Edit extends ControllerBooking
{

    /**
     * @var BookingFactory
     */
    private $bookingFactory;

    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param BookingRepositoryInterface $bookingRepository
     * @param BookingFactory $bookingFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        BookingRepositoryInterface $bookingRepository,
        BookingFactory $bookingFactory
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->bookingFactory    = $bookingFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $bookingId = $this->getRequest()->getParam(static::PARAM_CRUD_ID);

        if ($bookingId) {
            try {
                /** @var  ModelBooking $model */
                $model = $bookingId ?
                    $this->bookingRepository->getById(intval($bookingId)) :
                    $this->bookingFactory->create();
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This booking no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data) && isset($model)) {
            $dataArray = $this->prepareDataFieldset($data);
            $model->setData($dataArray);
        }

        if (isset($model)) {
            $this->coreRegistry->register(static::REGISTRY_NAME, $model);
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage)->addBreadcrumb(
            $bookingId ? __('Edit Booking') : __('New Booking'),
            $bookingId ? __('Edit Booking') : __('New Booking')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Booking'));
        $resultPage->getConfig()->getTitle()->prepend(
            (isset($model)) ? __("Edit Booking: %1", $model->getId()) : __('New Booking')
        );

        return $resultPage;
    }
}
