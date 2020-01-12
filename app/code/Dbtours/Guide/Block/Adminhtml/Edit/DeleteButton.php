<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Guide\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Dbtours\Guide\Controller\Adminhtml\Guide as ControllerGuide;

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
                    . __('Are you sure you want to delete this guide?')
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
        return $this->getUrl('*/*/delete', [ControllerGuide::PARAM_CRUD_ID => $this->getId()]);
    }

    /**
     * @return int | null
     */
    public function getId()
    {
        $guide = $this->registry->registry(ControllerGuide::REGISTRY_NAME);
        return $guide ? $guide->getId() : null;
    }
}
