<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

/**
 * Interface BookingInterface
 */
interface BookingInterface extends CustomAttributesDataInterface
{
    const TABLE             = 'db_booking';
    const ID                = 'entity_id';
    const ORDER_ITEM_ID     = 'order_item_id';
    const TOUR              = 'tour';
    const START             = 'start_time';
    const FINISH            = 'finish_time';
    const BLOCKED_AFTER     = 'blocked_after';
    const BLOCKED_BEFORE    = 'blocked_before';
    const LANGUAGE          = 'language_code';
    const GUIDE_ID          = 'guide_id';
    const PAX               = 'pax';
    const DURATION          = 'duration';
    const TIPS              = 'tips';
    const INCLUDED          = 'included';

    /**
     * Retrieve Id
     *
     * @return int
     */
    public function getId();

    /**
     * Set Id
     *
     * @param  $entityId
     * @return $this
     */
    public function setId($entityId);

    /**
     * Retrieve Order Item
     *
     * @return string
     */
    public function getOrderItem();

    /**
     * Set Order Item
     *
     * @param string $orderItem
     */
    public function setOrderItem($orderItem);

    /**
     * Retrieve Tour
     *
     * @return string
     */
    public function getTour();

    /**
     * Set Tour
     *
     * @param string $tour
     */
    public function setTour($tour);

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
     * Retrieve Blocked Before
     *
     * @return int
     */
    public function getBlockedBefore();

    /**
     * Set Block Before
     *
     * @param $blockedBefore
     */
    public function setBlockedBefore($blockedBefore);

    /**
     * Retrieve Blocked After
     *
     * @return int
     */
    public function getBlockedAfter();

    /**
     * Set Block After
     *
     * @param $blockedAfter
     */
    public function setBlockedAfter($blockedAfter);

    /**
     * Retrieve Language
     *
     * @return string
     */
    public function getLanguage();

    /**
     * Set Language
     *
     * @param string $language
     */
    public function setLanguage($language);

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
     * Retrieve Pax
     *
     * @return int
     */
    public function getPax();

    /**
     * Set Pax
     *
     * @param int $pax
     */
    public function setPax($pax);

    /**
     * Retrieve Duration
     *
     * @return string
     */
    public function getDuration();

    /**
     * Set Duration
     *
     * @param string $duration
     */
    public function setDuration($duration);

    /**
     * Retrieve Duration
     *
     * @return string
     */
    public function getTips();

    /**
     * Set Tips
     *
     * @param string $tips
     */
    public function setTips($tips);

    /**
     * Retrieve Included
     *
     * @return string
     */
    public function getIncluded();

    /**
     * Set Included
     *
     * @param string $included
     */
    public function setIncluded($included);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Dbtours\Booking\Api\Data\BookingExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Dbtours\Booking\Api\Data\BookingExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Dbtours\Booking\Api\Data\BookingExtensionInterface
                                           $extensionAttributes);
}
