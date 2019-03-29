<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model\CalendarEvent;

use Dbtours\Calendar\Model\ResourceModel\CalendarEvent\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $calendarEventCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $calendarEventCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $calendarEventCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        if ($items) {
            foreach ($items as $calendarEvent) {
                $calendarEvent->setData('id', $calendarEvent->getId());
                $this->_loadedData[$calendarEvent->getId()] = $calendarEvent->getData();
            }
            return $this->_loadedData;
        }
    }
}
