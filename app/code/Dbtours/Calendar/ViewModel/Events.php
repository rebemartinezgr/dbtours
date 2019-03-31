<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\ViewModel;

use Dbtours\Calendar\Model\ResourceModel\CalendarEvent\Collection;

/**
 * Class Events
 */
class Events implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var CalendarEventRepository
     */
    private $eventCollection;

    /**
     * Events constructor.
     * @param Collection $eventCollection
     */
    public function __construct(
        Collection $eventCollection
    ) {
        $this->eventCollection = $eventCollection;
    }

    /**
     * @return mixed
     */
    public function getCalendarEvents()
    {
        $result     = [];
        $collection = $this->eventCollection->getFullInfo();
        /** @var  \Dbtours\Calendar\Model\CalendarEvent $item */
        foreach ($collection->getItems() as $item) {
            $result[] = [
                "start"   => $item->getStartTime(),
                "end"     => $item->getFinishTime(),
                "title"   => $this->getEventTitle($item),
                "color"   => $item->getColor(),
                "id"      => $item->getId(),
                "content" => $this->getEventContent($item)
            ];
        }
        return json_encode($result);
    }

    /**
     * @param $event
     * @return string
     */
    private function getEventContent($event)
    {
        //Event
        $content = '<p>' . 'Type: ' . $event->getCode() . '</p>';

        //Booking
        $content .= $event->getTour() ? '<p>' . 'Tour: ' . $event->getTour() . '</p>' : '';
        $content .= $event->getLanguageCode() ? '<p>' . 'Language: ' . $event->getLanguageCode() . '</p>' : '';

        //Guide Info
        $content .= '<p>' . 'Guide: ' . $event->getFirstname() . " " . $event->getLastname() . '</p>';

        return $content;
    }

    /**
     * @param $event
     * @return string
     */
    private function getEventTitle($event)
    {
        $title = "[" . $event->getFirstname() . "] ";
        $title .= $event->getTour() ?? $event->getCode();

        return $title;
    }
}
