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
    const TABLE         = 'db_tour_event_language';
    const ID            = 'entity_id';
    const TOUR_EVENT_ID = 'tour_event_id';
    const LANGUAGE_ID   = 'language_id';
    const AVAILABLE     = 'available';

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
