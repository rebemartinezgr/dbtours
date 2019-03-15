<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Block\Adminhtml\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Dbtours\Catalog\Model\Source\Products;
use Magento\Framework\Registry;

/**
 * Class Form
 */
class Form extends Generic
{
    private $productSource;

    /**
     * Form constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Products $productsSource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Products $productsSource,
        array $data = []
    ) {
        $this->productSource = $productsSource;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws LocalizedException
     */
    // @codingStandardsIgnoreStart
    protected function _prepareForm()
    {
        // @codingStandardsIgnoreEnd
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id'      => 'edit_form',
                    'action'  => $this->getUrl('*/*/generate'),
                    'method'  => 'post',
                    'enctype' => 'multipart/form-data',
                ],
            ]
        );
        $data = [
            'name' => 'product_id',
            'values' => $this->productSource->toOptionArray()
        ];
        $form->addField('id', 'multiselect', $data);
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
