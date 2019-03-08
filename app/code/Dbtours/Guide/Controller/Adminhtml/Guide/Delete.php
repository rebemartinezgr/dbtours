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
use Dbtours\Guide\Api\GuideRepositoryInterface;
use Dbtours\Guide\Controller\Adminhtml\Guide as ControllerGuide;
use Dbtours\Guide\Model\Guide;

/**
 * Class Delete
 */
class Delete extends ControllerGuide
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var GuideRepositoryInterface
     */
    private $guideRepository;

    /**
     * Delete constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param GuideRepositoryInterface $guideRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        GuideRepositoryInterface $guideRepository
    ) {
        $this->guideRepository = $guideRepository;
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
        $guideId = $this->getRequest()->getParam(ControllerGuide::PARAM_CRUD_ID);
        if ($guideId) {
            try {
                /** @var Guide $guide */
                $guide = $this->guideRepository->getById(intval($guideId));
                // delete
                $this->guideRepository->delete($guide);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This guide no longer exists.'));
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', [ControllerGuide::PARAM_CRUD_ID => $guideId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This guide could not be deleted.'));
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', [ControllerGuide::PARAM_CRUD_ID => $guideId]);
            }
            // display success message
            $this->messageManager->addSuccessMessage(__('You have deleted the guide successfully.'));

            // go to grid
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a guide to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
