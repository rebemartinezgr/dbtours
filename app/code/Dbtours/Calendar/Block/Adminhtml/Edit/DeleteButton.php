<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Calendar\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Dbtours\Calendar\Controller\Adminhtml\CalendarEvent as ControllerCalendarEvent;

/**
 * Class DeleteButton
 */
class DeleteButton extends AbstractGenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData() : array
    {
        $data = [];
        if ($this->getId()) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this calendarEvent?')
                    . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl() : string
    {
        return $this->getUrl('*/*/delete', [ControllerCalendarEvent::PARAM_CRUD_ID => $this->getId()]);
    }

    /**
     * @return int | null
     */
    public function getId()
    {
        $calendarEvent = $this->registry->registry(ControllerCalendarEvent::REGISTRY_NAME);
        return $calendarEvent ? $calendarEvent->getId() : null;
    }
}
