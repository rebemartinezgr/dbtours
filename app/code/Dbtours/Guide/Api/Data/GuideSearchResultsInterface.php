<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Api\Data;

/**
 * Interface GuideSearchResultsInterface
 */
interface GuideSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Dbtours\Guide\Api\Data\GuideInterface[]
     */
    public function getItems();

    /**
     * @param \Dbtours\Guide\Api\Data\GuideInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
