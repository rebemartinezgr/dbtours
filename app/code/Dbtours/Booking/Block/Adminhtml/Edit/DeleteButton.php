<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Booking\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Dbtours\Booking\Controller\Adminhtml\Booking as ControllerBooking;

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
                    . __('Are you sure you want to delete this booking?')
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
        return $this->getUrl('*/*/delete', [ControllerBooking::PARAM_CRUD_ID => $this->getId()]);
    }

    /**
     * @return int | null
     */
    public function getId()
    {
        $booking = $this->registry->registry(ControllerBooking::REGISTRY_NAME);
        return $booking ? $booking->getId() : null;
    }
}
