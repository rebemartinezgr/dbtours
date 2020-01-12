<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Guide\Controller\Adminhtml\Guide;

use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Dbtours\Guide\Controller\Adminhtml\Guide as ControllerGuide;

/**
 * Class NewAction
 */
class NewAction extends ControllerGuide
{
    /**
     * Create new guide
     *
     * @return ResultInterface
     */
    public function execute() : ResultInterface
    {
        /** @var Forward $resultForward */
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);

        return $resultForward->forward('edit');
    }
}
