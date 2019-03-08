<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Guide\Controller\Adminhtml\Guide;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Ui\Component\MassAction\Filter;
use Dbtours\Guide\Api\GuideRepositoryInterface;
use Dbtours\Guide\Controller\Adminhtml\Guide as ControllerGuide;
use Dbtours\Guide\Model\ResourceModel\Guide\CollectionFactory;

/**
 * Class MassDelete
 */
class MassDelete extends ControllerGuide
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
     * @var GuideRepositoryInterface
     */
    protected $guideRepository;

    /**
     * constructor
     *
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param GuideRepositoryInterface $guideRepository
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        GuideRepositoryInterface $guideRepository,
        Context $context,
        Registry $coreRegistry
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->guideRepository = $guideRepository;
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
                $this->guideRepository->delete($item);
                $delete++;
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $delete));
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
