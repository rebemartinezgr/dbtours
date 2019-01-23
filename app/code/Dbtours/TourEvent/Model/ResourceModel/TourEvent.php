<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model\ResourceModel;

use Dbtours\TourEvent\Api\Data\TourEventInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class TourEvent
 */
class TourEvent extends AbstractDb
{
    /**
     * Initialize Resource Model
     */
    protected function _construct()
    {
        $this->_init(TourEventInterface::TABLE, TourEventInterface::ID);
    }
}