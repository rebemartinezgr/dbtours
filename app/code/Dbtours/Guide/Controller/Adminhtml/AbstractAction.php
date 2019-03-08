<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Guide\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

/**
 * Class AbstractAction
 */
abstract class AbstractAction extends Action
{

    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @var string
     */
    protected $resource = 'Dbtours_Guide::manage_guide';

    /**
     * Store constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    // @codingStandardsIgnoreStart

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _isAllowed()
    {
        // @codingStandardsIgnoreEnd
        return $this->_authorization->isAllowed($this->resource);
    }

    /**
     * Unify data by fieldset
     *
     * @param $data
     * @return array
     */
    protected function prepareDataFieldset($data)
    {
        $dataArray = [];
        foreach ($data as $fieldset => $values) {
            if (is_array($values)) {
                $dataArray = array_merge_recursive($dataArray, $values);
                continue;
            }
            $dataArray = array_merge_recursive($dataArray, [$fieldset => $values]);
        }

        return $dataArray;
    }
}
