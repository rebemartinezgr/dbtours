<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Model\ResourceModel;

use Dbtours\Guide\Api\Data\GuideInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Guide
 */
class Guide extends AbstractDb
{
    /**
     * Initialize Resource Model
     */
    protected function _construct()
    {
        $this->_init(GuideInterface::TABLE, GuideInterface::ID);
    }
}