<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */
declare(strict_types=1);

namespace Dbtours\TourEvent\Controller\Adminhtml\Generation;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 */
class Index extends Action
{
    /**
     * @var string
     */
    protected $resource = 'Dbtours_TourEvent::tourevent_generation';

    /**
     * Init page.
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage) : Page
    {
        $resultPage->setActiveMenu('Dbtours_Base::base')
            ->addBreadcrumb(__('Tour Events'), __('Tour Events'))
            ->addBreadcrumb(__('Tour Event Generation'), __('Tour Event Generation'));

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
        $resultPage->getConfig()->getTitle()->prepend(__('Tour Event Generation'));

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
