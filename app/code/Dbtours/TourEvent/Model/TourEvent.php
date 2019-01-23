<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model;

use Dbtours\TourEvent\Api\Data\TourEventExtensionInterface;
use Dbtours\TourEvent\Api\Data\TourEventInterface;
use Dbtours\TourEvent\Model\ResourceModel\TourEvent as ResourceModelTourEvent;

use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class TourEvent
 */
class TourEvent extends AbstractExtensibleModel implements TourEventInterface
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModelTourEvent::class);
    }

    /**
     * @inheritdoc
     */
    public function getProductId()
    {
        return $this->_getData(self::PRODUCT_ID);
    }

    /**
     * @inheritdoc
     */
    public function setProductId($productId)
    {
        $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @inheritdoc
     */
    public function getStartTime()
    {
        return $this->_getData(self::START);
    }

    /**
     * @inheritdoc
     */
    public function setStartTime($startTime)
    {
        $this->setData(self::START, $startTime);
    }

    /**
     * @inheritdoc
     */
    public function getFinishTime()
    {
        return $this->_getData(self::FINISH);
    }

    /**
     * @inheritdoc
     */
    public function setFinishTime($finishTime)
    {
        $this->setData(self::FINISH, $finishTime);
    }

    /**
     * @inheritdoc
     */
    public function getBlockedBefore()
    {
        return $this->_getData(self::BLOCKED_BEFORE);
    }

    /**
     * @inheritdoc
     */
    public function setBlockedBefore($blockedBefore)
    {
        $this->setData(self::BLOCKED_BEFORE, $blockedBefore);
    }

    /**
     * @inheritdoc
     */
    public function getBlockedAfter()
    {
        return $this->_getData(self::BLOCKED_AFTER);
    }

    /**
     * @inheritdoc
     */
    public function setBlockedAfter($blockedAfter)
    {
        $this->setData(self::BLOCKED_AFTER, $blockedAfter);
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(TourEventExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}