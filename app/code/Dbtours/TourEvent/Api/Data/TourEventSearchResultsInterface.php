<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Api\Data;

/**
 * Interface TourEventSearchResultsInterface
 */
interface TourEventSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Dbtours\TourEvent\Api\Data\TourEventInterface[]
     */
    public function getItems();

    /**
     * @param \Dbtours\TourEvent\Api\Data\TourEventInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
