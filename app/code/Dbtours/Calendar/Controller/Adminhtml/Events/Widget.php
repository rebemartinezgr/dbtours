<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */
declare(strict_types=1);

namespace Dbtours\Calendar\Controller\Adminhtml\Events;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Widget
 */
class Widget extends Action
{
    /**
     * @var string
     */
    protected $resource = 'Dbtours_Calendar::calendar_events';

    /**
     * Init page.
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage) : Page
    {
        $resultPage->setActiveMenu('Dbtours_Base::base')
            ->addBreadcrumb(__('Calendar'), __('Calendar'))
            ->addBreadcrumb(__('Calendar Events'), __('Calendar Events'));

        return $resultPage;
    }

    /**
     * Index Action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $this->initPage($resultPage);
        $resultPage->getConfig()->getTitle()->prepend(__('Calendar Events'));

        return $resultPage;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _isAllowed()
    {
        // @codingStandardsIgnoreEnd
        return $this->_authorization->isAllowed($this->resource);
    }
}
