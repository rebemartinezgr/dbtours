<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

/**
 * Interface TourEventLanguageInterface
 */
interface TourEventLanguageInterface extends CustomAttributesDataInterface
{
    const ID                = 'entity_id';
    const TABLE             = 'db_tour_event_language';
    const PRODUCT_ID        = 'product_id';
    const TOUR_EVENT_ID     = 'tour_event_id';
    const START_TIME        = 'start_time';
    const FINISH_TIME       = 'finish_time';
    const BLOCKED_AFTER     = 'blocked_after';
    const BLOCKED_BEFORE    = 'blocked_before';
    const LANGUAGE_CODE     = 'language_code';
    const AVAILABLE         = 'available';
    const AVAILABLE_GUIDES  = 'available_guides';

    /**
     * @return mixed
     */
    public function getProductId();

    /**
     * Retrieve Tour Event Id
     *
     * @return int
     */
    public function getTourEventId();

    /**
     * Retrieve Start Time
     *
     * @return string
     */
    public function getStartTime();

    /**
     * Retrieve Finish Time
     *
     * @return string
     */
    public function getFinishTime();

    /**
     * Retrieve Blocked Before
     *
     * @return string
     */
    public function getBlockedBefore();

    /**
     * Retrieve Blocked After
     *
     * @return string
     */
    public function getBlockedAfter();

    /**
     * Retrieve Language Code
     *
     * @return int
     */
    public function getLanguageCode();

    /**
     * Retrieve Available flag
     *
     * @return int
     */
    public function isAvailable();

    /**
     * Retrieve Available Guides
     *
     * @return int
     */
    public function getAvailableGuide();

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageExtensionInterface|null
     */
    public function getExtensionAttributes();
}
