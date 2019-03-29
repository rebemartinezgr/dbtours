<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model;

use Dbtours\Calendar\Api\Data\CalendarEventTypeExtensionInterface;
use Dbtours\Calendar\Api\Data\CalendarEventTypeInterface;
use Dbtours\Calendar\Model\ResourceModel\CalendarEventType as ResourceModelCalendarEventType;

use Magento\Framework\Model\AbstractExtensibleModel;
/**
 * Class CalendarEventType
 */
class CalendarEventType extends AbstractExtensibleModel implements CalendarEventTypeInterface
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModelCalendarEventType::class);
    }

    /**
     * @inheritdoc
     */
    public function getCode()
    {
        return $this->_getData(self::CODE);
    }

    /**
     * @inheritdoc
     */
    public function setCode($code)
    {
        $this->setData(self::CODE, $code);
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
    public function setExtensionAttributes(CalendarEventTypeExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}

