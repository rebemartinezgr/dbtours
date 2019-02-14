<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Booking\Controller\Adminhtml\Booking;

use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Dbtours\Booking\Controller\Adminhtml\Booking as ControllerBooking;

/**
 * Class NewAction
 */
class NewAction extends ControllerBooking
{
    /**
     * Create new booking
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
