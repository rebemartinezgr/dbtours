<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\ViewModel;

use Dbtours\Base\Helper\Locale;
use Dbtours\Calendar\Model\ResourceModel\CalendarEvent\Collection;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class Events
 */
class Events implements ArgumentInterface
{
    /**
     * @var Collection
     */
    private $eventCollection;

    /**
     * @var Locale
     */
    private $localeHelper;

    /**
     * Events constructor.
     * @param Collection $eventCollection
     * @param Locale $localeHelper
     */
    public function __construct(
        Collection $eventCollection,
        Locale $localeHelper
    ) {
        $this->eventCollection = $eventCollection;
        $this->localeHelper    = $localeHelper;
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
                "content" => $this->getEventContent($item),
                "guide"   => $item->getGuideId(),
                "type"    => $item->getTypeId()

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
        $content = '<p>' . '<b>Type:  </b>' . $event->getCode() . '</p>';

        //Booking
        $content .= $event->getTour() ? '<p>' . '<b>Tour: </b>' . $event->getTour() . '</p>' : '';
        $content .= $event->getLanguageCode() ?
            '<p>' . '<b>Language: </b>' . $this->localeHelper->getFormattedLanguage($event->getLanguageCode()) . '</p>' : '';

        //Guide Info
        $content .= '<p>' .
            '<b>Guide:  </b>' . $event->getFirstname() . " " . $event->getLastname() .
            ' [' . $event->getGuideCode() . ']</p>';

        return $content;
    }

    /**
     * @param $event
     * @return string
     */
    private function getEventTitle($event)
    {
        return ' [' . $event->getGuideCode() . '] ' . $event->getCode();
    }
}
