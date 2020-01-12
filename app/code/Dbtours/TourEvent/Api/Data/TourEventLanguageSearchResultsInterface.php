<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Api\Data;

/**
 * Interface TourEventLanguageSearchResultsInterface
 */
interface TourEventLanguageSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageInterface[]
     */
    public function getItems();

    /**
     * @param \Dbtours\TourEvent\Api\Data\TourEventLanguageInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
