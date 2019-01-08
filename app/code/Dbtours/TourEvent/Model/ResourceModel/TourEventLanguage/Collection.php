<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model\ResourceModel\TourEventLanguage;

use Dbtours\TourEvent\Model\TourEventLanguage as ModelTourEventLanguage;
use Dbtours\TourEvent\Model\ResourceModel\TourEventLanguage as ResourceModelTourEventLanguage;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(ModelTourEventLanguage::class, ResourceModelTourEventLanguage::class);
    }
}