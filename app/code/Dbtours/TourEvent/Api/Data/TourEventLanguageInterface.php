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
    const LANGUAGE_ID       = 'language_id';
    const LANGUAGE_CODE     = 'language_code';
    const AVAILABLE         = 'available';
    const AVAILABLE_GUIDES  = 'available_guides';

    /**
     * @return mixed
     */
    public function getProductId();

    /**
     * @param $productId
     * @return mixed
     */
    public function setProductId($productId);

    /**
     * Retrieve Tour Event Id
     *
     * @return int
     */
    public function getTourEventId();

    /**
     * Set Tour Event Id
     *
     * @param int $tourEventId
     */
    public function setTourEventId($tourEventId);

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
     * Retrieve Language Id
     *
     * @return int
     */
    public function getLanguageId();

    /**
     * Set Language Id
     *
     * @param int $languageId
     */
    public function setLanguageId($languageId);

    /**
     * Retrieve Language Code
     *
     * @return int
     */
    public function getLanguageCode();

    /**
     * Set Language Code
     *
     * @param int $languageCode
     */
    public function setLanguageCode($languageCode);

    /**
     * Retrieve Available flag
     *
     * @return int
     */
    public function getAvailable();

    /**
     * Set Available flag
     *
     * @param int $available
     */
    public function setAvailable($available);

    /**
     * Retrieve Available Guides
     *
     * @return int
     */
    public function getAvailableGuides();

    /**
     * Set Available Guides
     *
     * @param int $availableGuides
     */
    public function setAvailableGuides($availableGuides);
    
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Dbtours\TourEvent\Api\Data\TourEventLanguageExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Dbtours\TourEvent\Api\Data\TourEventLanguageExtensionInterface
                                           $extensionAttributes);
}
