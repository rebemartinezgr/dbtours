<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Calendar\Controller\Adminhtml\CalendarEvent;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Dbtours\Calendar\Api\CalendarEventRepositoryInterface;
use Dbtours\Calendar\Controller\Adminhtml\CalendarEvent as ControllerCalendarEvent;
use Dbtours\Calendar\Model\CalendarEvent;

/**
 * Class Delete
 */
class Delete extends ControllerCalendarEvent
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CalendarEventRepositoryInterface
     */
    private $calendarEventRepository;

    /**
     * Delete constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param CalendarEventRepositoryInterface $calendarEventRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CalendarEventRepositoryInterface $calendarEventRepository
    ) {
        $this->calendarEventRepository = $calendarEventRepository;
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
        $calendarEventId = $this->getRequest()->getParam(ControllerCalendarEvent::PARAM_CRUD_ID);
        if ($calendarEventId) {
            try {
                /** @var CalendarEvent $calendarEvent */
                $calendarEvent = $this->calendarEventRepository->getById(intval($calendarEventId));
                // delete
                $this->calendarEventRepository->delete($calendarEvent);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This calendar event no longer exists.'));
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', [ControllerCalendarEvent::PARAM_CRUD_ID => $calendarEventId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This calendar event could not be deleted.'));
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', [ControllerCalendarEvent::PARAM_CRUD_ID => $calendarEventId]);
            }
            // display success message
            $this->messageManager->addSuccessMessage(__('You have deleted the calendar event successfully.'));

            // go to grid
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a calendar event to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
