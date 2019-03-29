<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Calendar\Controller\Adminhtml\CalendarEvent;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Dbtours\Calendar\Controller\Adminhtml\CalendarEvent as ControllerCalendarEvent;
use Dbtours\Calendar\Api\Data\CalendarEventInterface as ModelCalendarEvent;
use Dbtours\Calendar\Api\Data\CalendarEventInterfaceFactory as CalendarEventFactory;
use Dbtours\Calendar\Api\CalendarEventRepositoryInterface;

/**
 * Class Edit
 */
class Edit extends ControllerCalendarEvent
{

    /**
     * @var CalendarEventFactory
     */
    private $calendarEventFactory;

    /**
     * @var CalendarEventRepositoryInterface
     */
    private $calendarEventRepository;

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param CalendarEventRepositoryInterface $calendarEventRepository
     * @param CalendarEventFactory $calendarEventFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CalendarEventRepositoryInterface $calendarEventRepository,
        CalendarEventFactory $calendarEventFactory
    ) {
        $this->calendarEventRepository = $calendarEventRepository;
        $this->calendarEventFactory    = $calendarEventFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $calendarEventId = $this->getRequest()->getParam(static::PARAM_CRUD_ID);

        if ($calendarEventId) {
            try {
                /** @var  ModelCalendarEvent $model */
                $model = $calendarEventId ?
                    $this->calendarEventRepository->getById(intval($calendarEventId)) :
                    $this->calendarEventFactory->create();
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This calendar event no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data) && isset($model)) {
            $model->setData($data);
        }

        if (isset($model)) {
            $this->coreRegistry->register(static::REGISTRY_NAME, $model);
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage)->addBreadcrumb(
            $calendarEventId ? __('Edit CalendarEvent') : __('New CalendarEvent'),
            $calendarEventId ? __('Edit CalendarEvent') : __('New CalendarEvent')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('CalendarEvent'));
        $resultPage->getConfig()->getTitle()->prepend(
            (isset($model)) ? __("Edit CalendarEvent: %1", $model->getId()) : __('New CalendarEvent')
        );

        return $resultPage;
    }
}
