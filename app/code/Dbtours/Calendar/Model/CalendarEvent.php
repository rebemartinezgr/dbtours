<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model;

use Dbtours\Calendar\Api\Data\CalendarEventExtensionInterface;
use Dbtours\Calendar\Api\Data\CalendarEventInterface;
use Dbtours\Calendar\Model\ResourceModel\CalendarEvent as ResourceModelCalendarEvent;

use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class CalendarEvent
 */
class CalendarEvent extends AbstractExtensibleModel implements CalendarEventInterface
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModelCalendarEvent::class);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->_getData(self::ID) ?: null;
    }

    /**
     * @inheritdoc
     */
    public function setId($entityId)
    {
        $this->setData(self::ID, $entityId);
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
    public function getTypeId()
    {
        return $this->_getData(self::TYPE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setTypeId($typeId)
    {
        $this->setData(self::TYPE_ID, $typeId);
    }

    /**
     * @inheritdoc
     */
    public function getOrderItemId()
    {
        return $this->_getData(self::ORDER_ITEM_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderItemId($orderItemId)
    {
        $this->setData(self::ORDER_ITEM_ID, $orderItemId);
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
    public function setExtensionAttributes(CalendarEventExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}