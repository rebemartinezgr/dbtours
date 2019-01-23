<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model\ResourceModel;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class TourEventLanguage
 */
class TourEventLanguage extends AbstractDb
{
    /**
     * Initialize Resource Model
     */
    protected function _construct()
    {
        $this->_init(TourEventLanguageInterface::TABLE, TourEventLanguageInterface::ID);
    }
}