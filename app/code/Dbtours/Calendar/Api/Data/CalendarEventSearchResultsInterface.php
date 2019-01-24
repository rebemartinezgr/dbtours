<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Api\Data;

/**
 * Interface CalendarEventSearchResultsInterface
 */
interface CalendarEventSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Dbtours\Calendar\Api\Data\CalendarEventInterface[]
     */
    public function getItems();

    /**
     * @param \Dbtours\Calendar\Api\Data\CalendarEventInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
