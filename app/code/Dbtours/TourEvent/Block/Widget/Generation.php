<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Block\Widget;

/**
 * Class Generation
 */
class Generation extends \Magento\Backend\Block\Widget\Form\Container
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml';
        $this->_blockGroup = 'Dbtours_TourEvent';

        $this->_headerText = __('Tour Event Generation');
        parent::_construct();

        $this->removeButton('back');
        $this->removeButton('reset');
        $this->updateButton('save', 'label', 'Generate');
    }
}
