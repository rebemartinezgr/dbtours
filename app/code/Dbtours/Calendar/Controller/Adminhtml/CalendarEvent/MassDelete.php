<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Calendar\Controller\Adminhtml\CalendarEvent;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Ui\Component\MassAction\Filter;
use Dbtours\Calendar\Api\CalendarEventRepositoryInterface;
use Dbtours\Calendar\Controller\Adminhtml\CalendarEvent as ControllerCalendarEvent;
use Dbtours\Calendar\Model\ResourceModel\CalendarEvent\CollectionFactory;

/**
 * Class MassDelete
 */
class MassDelete extends ControllerCalendarEvent
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
     * @var CalendarEventRepositoryInterface
     */
    protected $calendarEventRepository;

    /**
     * constructor
     *
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param CalendarEventRepositoryInterface $calendarEventRepository
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        CalendarEventRepositoryInterface $calendarEventRepository,
        Context $context,
        Registry $coreRegistry
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->calendarEventRepository = $calendarEventRepository;
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
                $this->calendarEventRepository->delete($item);
                $delete++;
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $delete));
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
