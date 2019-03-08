<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Guide\Controller\Adminhtml\Guide;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Dbtours\Guide\Controller\Adminhtml\Guide as ControllerGuide;
use Dbtours\Guide\Api\Data\GuideInterface as ModelGuide;
use Dbtours\Guide\Api\Data\GuideInterfaceFactory as GuideFactory;
use Dbtours\Guide\Api\GuideRepositoryInterface;

/**
 * Class Edit
 */
class Edit extends ControllerGuide
{

    /**
     * @var GuideFactory
     */
    private $guideFactory;

    /**
     * @var GuideRepositoryInterface
     */
    private $guideRepository;

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param GuideRepositoryInterface $guideRepository
     * @param GuideFactory $guideFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        GuideRepositoryInterface $guideRepository,
        GuideFactory $guideFactory
    ) {
        $this->guideRepository = $guideRepository;
        $this->guideFactory    = $guideFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $guideId = $this->getRequest()->getParam(static::PARAM_CRUD_ID);

        if ($guideId) {
            try {
                /** @var  ModelGuide $model */
                $model = $guideId ?
                    $this->guideRepository->getById(intval($guideId)) :
                    $this->guideFactory->create();
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This guide no longer exists.'));
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
            $guideId ? __('Edit Guide') : __('New Guide'),
            $guideId ? __('Edit Guide') : __('New Guide')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Guide'));
        $resultPage->getConfig()->getTitle()->prepend(
            (isset($model)) ? __("Edit Guide: %1", $model->getId()) : __('New Guide')
        );

        return $resultPage;
    }
}
