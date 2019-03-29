<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Calendar\Ui\Component\Listing\Column;

use Dbtours\Calendar\Api\Data\CalendarEventTypeInterface;
use Dbtours\Calendar\Model\ResourceModel\CalendarEventType\Collection;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class EventType
 */
class EventType implements OptionSourceInterface
{
    /**
     * @var
     */
    protected $options;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * EventType constructor.
     * @param Collection $calendarEventTypeCollection
     */
    public function __construct(
        Collection $calendarEventTypeCollection
    ) {
        $this->collection   = $calendarEventTypeCollection;
    }

    /**
     * @return array|null
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [['label' => 'Select an event type', 'value' => '']];

            foreach ($this->collection->getItems() as $id => $item) {
                /** @var    CalendarEventTypeInterface $item */
                $this->options[] = [
                    'label' => $item->getCode(),
                    'value' => $id
                ];
            }
        }
        return $this->options;
    }
}
