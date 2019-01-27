<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Model\ResourceModel;

use Dbtours\Booking\Api\Data\BookingInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Booking
 */
class Booking extends AbstractDb
{
    /**
     * Initialize Resource Model
     */
    protected function _construct()
    {
        $this->_init(BookingInterface::TABLE, BookingInterface::ID);
    }
}