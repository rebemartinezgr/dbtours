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
use Dbtours\Booking\Api\BookingRepositoryInterface;
use Dbtours\Booking\Controller\Adminhtml\Booking as ControllerBooking;
use Dbtours\Booking\Model\Booking;

/**
 * Class Delete
 */
class Delete extends ControllerBooking
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;

    /**
     * Delete constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param BookingRepositoryInterface $bookingRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        BookingRepositoryInterface $bookingRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * @return ResultInterface
     */
    public function execute() : ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $bookingId = $this->getRequest()->getParam(ControllerBooking::PARAM_CRUD_ID);
        if ($bookingId) {
            try {
                /** @var Booking $booking */
                $booking = $this->bookingRepository->getById(intval($bookingId));
                // delete
                $this->bookingRepository->delete($booking);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This booking no longer exists.'));
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', [ControllerBooking::PARAM_CRUD_ID => $bookingId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This booking could not be deleted.'));
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', [ControllerBooking::PARAM_CRUD_ID => $bookingId]);
            }
            // display success message
            $this->messageManager->addSuccessMessage(__('You have deleted the booking successfully.'));

            // go to grid
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a booking to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
