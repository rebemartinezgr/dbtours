<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Calendar\Controller\Adminhtml\CalendarEvent;

use Dbtours\Base\Helper\Date;
use Dbtours\Calendar\Api\CalendarEventRepositoryInterface;
use Dbtours\Calendar\Api\Data\CalendarEventInterfaceFactory as CalendarEventFactory;
use Dbtours\Calendar\Controller\Adminhtml\CalendarEvent as ControllerCalendarEvent;
use Dbtours\Calendar\Model\CalendarEvent as ModelCalendarEvent;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;

/**
 * Class Save
 */
class Save extends ControllerCalendarEvent
{
    /**
     * @var CalendarEventRepositoryInterface
     */
    private $calendarEventRepository;

    /**
     * @var CalendarEventFactory
     */
    private $calendarEventFactory;

    /**
     * @var Date
     */
    private $helperDate;

    /**
     * Save constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param CalendarEventRepositoryInterface $calendarEventRepository
     * @param CalendarEventFactory $calendarEventFactory
     * @param Date $helperDate
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CalendarEventRepositoryInterface $calendarEventRepository,
        CalendarEventFactory $calendarEventFactory,
        Date $helperDate
    ) {
        $this->calendarEventRepository = $calendarEventRepository;
        $this->calendarEventFactory    = $calendarEventFactory;
        $this->helperDate              = $helperDate;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save CalendarEvent.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $returnToEdit   = false;

        $calendarEventId = $this->getRequest()->getParam(ControllerCalendarEvent::PARAM_CRUD_ID);

        if ($data = $this->getRequest()->getPostValue()) {
            $data['start_time']  = $this->helperDate->convertToDBTimeZone($data['start_time']);
            $data['finish_time'] = $this->helperDate->convertToDBTimeZone($data['finish_time']);
            if ($calendarEventId) {
                try {
                    /** @var  ModelCalendarEvent $model */
                    $model =
                        $calendarEventId ? $this->calendarEventRepository->getById(intval($calendarEventId)) :
                            $this->calendarEventFactory->create();
                } catch (NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(__('This calendar event no longer exists.'));
                    /** @var Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();

                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (empty($calendarEventId)) {
                $model = $this->calendarEventFactory->create();
            }

            try {
                $model->setData($data);
                $this->calendarEventRepository->save($model);
                $calendarEventId = $model->getId();
                if (!$calendarEventId) {
                    throw new \Exception('New Calendar Event was not saved');
                }
                $this->messageManager->addSuccessMessage(__('The calendar event has been saved.'));
                $this->_getSession()->setFormData(false);

                $returnToEdit = (bool)$this->getRequest()->getParam('back', false);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the calendar event.'));
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_getSession()->setFormData($data);
                $returnToEdit = true;
            }
        }

        if ($returnToEdit) {
            if ($calendarEventId) {
                return $resultRedirect->setPath(
                    '*/*/edit',
                    [static::PARAM_CRUD_ID => $calendarEventId, '_current' => true]
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
