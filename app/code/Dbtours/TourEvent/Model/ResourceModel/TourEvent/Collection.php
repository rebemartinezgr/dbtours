<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model\ResourceModel\TourEvent;

use Dbtours\TourEvent\Model\TourEvent as ModelTourEvent;
use Dbtours\TourEvent\Model\ResourceModel\TourEvent as ResourceModelTourEvent;
use Dbtours\TourEvent\Model\TourEvent;
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
        $this->_init(ModelTourEvent::class, ResourceModelTourEvent::class);
    }

    /**
     * Delete all Unbooked Tour Events
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function deleteUnbooked()
    {
        $this->addFieldToFilter(TourEvent::IS_BOOKED, 0);
        $this->walk('delete');
    }
}
