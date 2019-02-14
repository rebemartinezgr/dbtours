<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Booking\Controller\Adminhtml\Booking;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Dbtours\Booking\Controller\Adminhtml\Booking as ControllerBooking;
use Dbtours\Booking\Api\BookingRepositoryInterface;
use Dbtours\Booking\Model\Booking as ModelBooking;
use Dbtours\Booking\Api\Data\BookingInterfaceFactory as BookingFactory;

/**
 * Class Save
 */
class Save extends ControllerBooking
{
    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;

    /**
     * @var BookingFactory
     */
    private $bookingFactory;

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
     * Save Booking.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $returnToEdit   = false;

        $bookingId = $this->getRequest()->getParam(ControllerBooking::PARAM_CRUD_ID);

        if ($data = $this->getRequest()->getPostValue()) {
            if ($bookingId) {
                try {
                    /** @var  ModelBooking $model */
                    $model = $bookingId ?
                        $this->bookingRepository->getById(intval($bookingId))
                        : $this->bookingFactory->create();
                } catch (NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(__('This booking no longer exists.'));
                    /** @var Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();

                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (empty($bookingId)) {
                $model = $this->bookingFactory->create();
//                if (empty($data['entity_id'])) {
//                    unset($data['entity_id']);
//                }
            }

            try {
                $dataArray     = $this->prepareDataFieldset($data);
                $model->setData($dataArray);
                $this->bookingRepository->save($model);
                $bookingId = $model->getId();
                $this->messageManager->addSuccessMessage(__('The booking has been saved.'));

                $this->_getSession()->setFormData(false);

                $returnToEdit = (bool)$this->getRequest()->getParam('back', false);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the booking.'));
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_getSession()->setFormData($data);
                $returnToEdit = true;
            }
        }

        if ($returnToEdit) {
            if ($bookingId) {
                return $resultRedirect->setPath(
                    '*/*/edit',
                    [static::PARAM_CRUD_ID => $bookingId, '_current' => true]
                );
            }
            return $resultRedirect->setPath(
                '*/*/new',
                ['_current' => true]
            );
        }

        return $resultRedirect->setPath('*/*');
    }
}
