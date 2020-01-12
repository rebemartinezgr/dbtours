<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model\CalendarEvent;

use Dbtours\Base\Helper\Date;
use Dbtours\Calendar\Api\Data\CalendarEventInterface;
use Dbtours\Calendar\Model\ResourceModel\CalendarEvent\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var Date
     */
    private $helperDate;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $calendarEventCollectionFactory
     * @param Date $helperDate
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $calendarEventCollectionFactory,
        Date $helperDate,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $calendarEventCollectionFactory->create();
        $this->helperDate = $helperDate;
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
            /** @var CalendarEventInterface $calendarEvent */
            foreach ($items as $calendarEvent) {
                $calendarEvent->setData('id', $calendarEvent->getId());
                $calendarEvent->setStartTime($this->helperDate->convertFromDBTimeZone($calendarEvent->getStartTime()));
                $calendarEvent->setFinishTime($this->helperDate->convertFromDBTimeZone($calendarEvent->getFinishTime()));
                $this->_loadedData[$calendarEvent->getId()] = $calendarEvent->getData();
            }
            return $this->_loadedData;
        }
    }
}
