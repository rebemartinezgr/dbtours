<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Guide\Controller\Adminhtml\Guide;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Dbtours\Guide\Controller\Adminhtml\Guide as ControllerGuide;
use Dbtours\Guide\Api\GuideRepositoryInterface;
use Dbtours\Guide\Model\Guide as ModelGuide;
use Dbtours\Guide\Api\Data\GuideInterfaceFactory as GuideFactory;

/**
 * Class Save
 */
class Save extends ControllerGuide
{
    /**
     * @var GuideRepositoryInterface
     */
    private $guideRepository;

    /**
     * @var GuideFactory
     */
    private $guideFactory;

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
     * Save Guide.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $returnToEdit   = false;

        $guideId = $this->getRequest()->getParam(ControllerGuide::PARAM_CRUD_ID);

        if ($data = $this->getRequest()->getPostValue()) {
            if ($guideId) {
                try {
                    /** @var  ModelGuide $model */
                    $model = $guideId ?
                        $this->guideRepository->getById(intval($guideId))
                        : $this->guideFactory->create();
                } catch (NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(__('This guide no longer exists.'));
                    /** @var Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();

                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (empty($guideId)) {
                $model = $this->guideFactory->create();
            }

            try {
                $model->setData($data);
                $this->guideRepository->save($model);
                $guideId = $model->getId();
                if (!$guideId) {
                    throw new \Exception('Guide was not saved');
                }
                $this->messageManager->addSuccessMessage(__('The guide has been saved.'));

                $this->_getSession()->setFormData(false);

                $returnToEdit = (bool)$this->getRequest()->getParam('back', false);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the guide.'));
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_getSession()->setFormData($data);
                $returnToEdit = true;
            }
        }

        if ($returnToEdit) {
            if ($guideId) {
                return $resultRedirect->setPath(
                    '*/*/edit',
                    [static::PARAM_CRUD_ID => $guideId, '_current' => true]
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
