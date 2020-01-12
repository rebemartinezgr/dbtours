<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Calendar\Controller\Adminhtml\CalendarEvent;

use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Dbtours\Calendar\Controller\Adminhtml\CalendarEvent as ControllerCalendarEvent;

/**
 * Class NewAction
 */
class NewAction extends ControllerCalendarEvent
{
    /**
     * Create new calendar event
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
