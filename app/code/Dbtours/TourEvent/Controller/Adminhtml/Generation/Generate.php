<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Controller\Adminhtml\Generation;

use Dbtours\TourEvent\Service\Generator;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 */
class Generate extends Action
{
    /**
     * @var string
     */
    private $resource = 'Dbtours_TourEvent::tourevent_generation';

    /**
     * @var Generator
     */
    private $generator;

    /**
     * Generate constructor.
     *
     * @param Context $context
     * @param Generator $generator
     */
    public function __construct(
        Context $context,
        Generator $generator
    ) {
        $this->generator = $generator;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('product_id', null);
        $id = is_array($id) ? implode(",", $id) : $id;
        try {
            $this->generator->execute($id);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurs, please try again'));
            return $resultRedirect->setPath('*/*/');
        }
        $this->messageManager->addSuccessMessage(__('The tour events have been generated successfully'));
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    // @codingStandardsIgnoreStart
    protected function _isAllowed()
    {
        // @codingStandardsIgnoreEnd
        return $this->_authorization->isAllowed($this->resource);
    }
}
