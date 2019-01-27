<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Api\Data;

/**
 * Interface BookingSearchResultsInterface
 */
interface BookingSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Dbtours\Booking\Api\Data\BookingInterface[]
     */
    public function getItems();

    /**
     * @param \Dbtours\Booking\Api\Data\BookingInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
