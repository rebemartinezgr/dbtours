<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */
declare(strict_types=1);

namespace Dbtours\Calendar\Controller\Adminhtml\CalendarEvent;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Dbtours\Calendar\Controller\Adminhtml\CalendarEvent as ControllerCalendarEvent;

/**
 * Class Index
 */
class Index extends ControllerCalendarEvent
{
    /**
     * Constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        parent::__construct($context, $coreRegistry);
    }
    /**
     * Index Action
     *
     * @return ResultInterface
     */
    public function execute() : ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $this->initPage($resultPage);
        $resultPage->getConfig()->getTitle()->prepend(__('CalendarEvents'));

        return $resultPage;
    }
}
