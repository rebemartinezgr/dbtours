<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

/**
 * Interface CalendarEventInterface
 */
interface CalendarEventInterface extends CustomAttributesDataInterface
{
    const TABLE             = 'db_calendar_event';
    const ID                = 'entity_id';
    const GUIDE_ID          = 'guide_id';
    const START             = 'start_time';
    const FINISH            = 'finish_time';
    const TYPE_ID           = 'type_id';
    const ORDER_ITEM_ID     = 'order_item_id';

    /**
     * Retrieve Guide Id
     *
     * @return int
     */
    public function getGuideId();

    /**
     * Set Guide Id
     *
     * @param int $guideId
     */
    public function setGuideId($guideId);

    /**
     * Retrieve Start Time
     *
     * @return string
     */
    public function getStartTime();

    /**
     * Set Start Time
     *
     * @param string $startTime
     */
    public function setStartTime($startTime);

    /**
     * Retrieve Finish Time
     *
     * @return string
     */
    public function getFinishTime();

    /**
     * Set Finish Time
     *
     * @param string $finishTime
     */
    public function setFinishTime($finishTime);

    /**
     * Retrieve Type Id
     *
     * @return int
     */
    public function getTypeId();

    /**
     * Set Type Id
     *
     * @param int $typeId
     */
    public function setTypeId($typeId);

    /**
     * Retrieve Order Id
     *
     * @return int
     */
    public function getOrderItemId();

    /**
     * Set Order Id
     *
     * @param int $orderId
     */
    public function setOrderItemId($orderItemId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Dbtours\Calendar\Api\Data\CalendarEventExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Dbtours\Calendar\Api\Data\CalendarEventExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Dbtours\Calendar\Api\Data\CalendarEventExtensionInterface
                                           $extensionAttributes);
}
