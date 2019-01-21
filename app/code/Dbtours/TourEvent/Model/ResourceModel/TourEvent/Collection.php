<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model\ResourceModel\TourEvent;

use Dbtours\TourEvent\Model\TourEvent as ModelTourEvent;
use Dbtours\TourEvent\Model\ResourceModel\TourEvent as ResourceModelTourEvent;
use Dbtours\TourEvent\Model\TourEvent;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
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
     * Delete all Tour Events
     *
     * @throws CouldNotDeleteException
     * @throws CouldNotSaveException
     */
    public function deleteAll()
    {
        $this->walk('delete');
    }
}
