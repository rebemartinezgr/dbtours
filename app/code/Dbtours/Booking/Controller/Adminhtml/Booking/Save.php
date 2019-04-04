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
use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface as TourEventLanguage;
use Dbtours\TourEvent\Api\TourEventLanguageRepositoryInterface;

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
     * @var TourEventLanguageRepositoryInterface
     */
    private $tourEventLanguageRepository;

    /**
     * Save constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param BookingRepositoryInterface $bookingRepository
     * @param BookingFactory $bookingFactory
     * @param TourEventLanguageRepositoryInterface $tourEventLanguageRepository
     *
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        BookingRepositoryInterface $bookingRepository,
        BookingFactory $bookingFactory,
        TourEventLanguageRepositoryInterface $tourEventLanguageRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->bookingFactory    = $bookingFactory;
         $this->tourEventLanguageRepository = $tourEventLanguageRepository;
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
            }

            try {
                $dataArray  = $this->prepareDataFieldset($data);
                $model = $this->prepareData($model, $dataArray);
                $this->bookingRepository->save($model);
                $bookingId = $model->getId();
                if (!$bookingId) {
                    throw new \Exception('Booking was not saved');
                }
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

    /**
     * @param ModelBooking $booking
     * @param $data
     * @return ModelBooking
     * @throws NoSuchEntityException
     */
    private function prepareData($booking, $data)
    {
        $data[ModelBooking::GUIDE_ID] = $data[ModelBooking::GUIDE_ID] ?: null;
        $booking->setData($data);

        if (isset($data['toureventlanguage']) && strpos($data['toureventlanguage'], "-")) {
            $value = explode("-", $data['toureventlanguage']);
            if (is_array($value) && count($value) == 2) {
                $languageCode   =  $value[1];
                $tourEventId    = $value[0];
                /** @var  TourEventLanguage $tourEventLanguage */
                $tourEventLanguage = $this->tourEventLanguageRepository
                    ->getByIdAndLanguage($tourEventId, $languageCode);
                $booking->setLanguage($tourEventLanguage->getLanguageCode());
                $booking->setStartTime($tourEventLanguage->getStartTime());
                $booking->setFinishTime($tourEventLanguage->getFinishTime());
                $booking->setBlockedBefore($tourEventLanguage->getBlockedBefore());
                $booking->setBlockedAfter($tourEventLanguage->getBlockedAfter());
            }

        }
        return $booking;
    }
}
