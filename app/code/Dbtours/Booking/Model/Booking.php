<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Model;

use Dbtours\Booking\Api\Data\BookingExtensionInterface;
use Dbtours\Booking\Api\Data\BookingInterface;
use Dbtours\Booking\Model\ResourceModel\Booking as ResourceModelBooking;

use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Booking
 */
class Booking extends AbstractExtensibleModel implements BookingInterface
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModelBooking::class);
    }

    /**
     * @inheritdoc
     */
    public function getOrderItem()
    {
        return $this->_getData(self::ORDER_ITEM_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderItem($orderItem)
    {
        $this->setData(self::ORDER_ITEM_ID, $orderItem);
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
    public function getLanguage()
    {
        return $this->_getData(self::LANGUAGE);
    }

    /**
     * @inheritdoc
     */
    public function setLanguage($language)
    {
        $this->setData(self::LANGUAGE, $language);
    }

    /**
     * @inheritdoc
     */
    public function getGuideId()
    {
        return $this->_getData(self::GUIDE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setGuideId($guideId)
    {
        $this->setData(self::GUIDE_ID, $guideId);
    }

    /**
     * @inheritdoc
     */
    public function getPax()
    {
        return $this->_getData(self::PAX);
    }

    /**
     * @inheritdoc
     */
    public function setPax($pax)
    {
        $this->setData(self::PAX, $pax);
    }

    /**
     * @inheritdoc
     */
    public function getDuration()
    {
        return $this->_getData(self::DURATION);
    }

    /**
     * @inheritdoc
     */
    public function setDuration($duration)
    {
        $this->setData(self::DURATION, $duration);
    }

    /**
     * @inheritdoc
     */
    public function getTips()
    {
        return $this->_getData(self::TIPS);
    }

    /**
     * @inheritdoc
     */
    public function setTips($tips)
    {
        $this->setData(self::TIPS, $tips);
    }

    /**
     * @inheritdoc
     */
    public function getIncluded()
    {
        return $this->_getData(self::INCLUDED);
    }

    /**
     * @inheritdoc
     */
    public function setIncluded($included)
    {
        $this->setData(self::INCLUDED, $included);
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
    public function setExtensionAttributes(BookingExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}