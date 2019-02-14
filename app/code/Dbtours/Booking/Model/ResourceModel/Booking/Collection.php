<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Model\ResourceModel\Booking;

use Dbtours\Booking\Model\Booking as ModelBooking;
use Dbtours\Booking\Model\ResourceModel\Booking as ResourceModelBooking;
use Dbtours\Booking\Model\Booking;
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
        $this->_init(ModelBooking::class, ResourceModelBooking::class);
    }
}
