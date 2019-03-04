<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Model\ResourceModel\Guide;

use Dbtours\Guide\Model\Guide as ModelGuide;
use Dbtours\Guide\Model\ResourceModel\Guide as ResourceModelGuide;
use Dbtours\Guide\Model\Guide;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{

    protected $_idFieldName = 'entity_id';

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(ModelGuide::class, ResourceModelGuide::class);
    }

    /**
     * @param $guideIds
     */
    public function addGuideFilter($guideIds)
    {
        $this->addFieldToFilter($this->_idFieldName, ['in' => $guideIds]);
    }
}
