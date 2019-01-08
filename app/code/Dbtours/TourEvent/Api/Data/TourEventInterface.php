<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

/**
 * Interface TourEventInterface
 */
interface TourEventInterface extends CustomAttributesDataInterface
{
    const TABLE         = 'db_tour_event';
    const ID            = 'entity_id';
    const PRODUCT_ID    = 'product_id';
    const START         = 'start_time';
    const FINISH        = 'finish_time';

    /**
     * Retrieve Product Id
     *
     * @return int
     */
    public function getProductId();

    /**
     * Set Product Id
     *
     * @param int $productId
     */
    public function setProductId($productId);

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
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Dbtours\TourEvent\Api\Data\TourEventExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Dbtours\TourEvent\Api\Data\TourEventExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Dbtours\TourEvent\Api\Data\TourEventExtensionInterface
                                           $extensionAttributes);
}
