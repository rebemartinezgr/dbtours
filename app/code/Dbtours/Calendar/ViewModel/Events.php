<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\ViewModel;

use Dbtours\Base\Helper\Locale;
use Dbtours\Calendar\Model\ResourceModel\CalendarEvent\Collection;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
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
     * @var
     */
    private $orderItemRepository;

    /**
     * @var UrlInterface
     */
    private $backendUrl;

    /**
     * Events constructor.
     * @param Collection $eventCollection
     * @param Locale $localeHelper
     * @param OrderItemRepositoryInterface $orderItemRepository
     * @param UrlInterface $backendUrl
     */
    public function __construct(
        Collection $eventCollection,
        Locale $localeHelper,
        OrderItemRepositoryInterface $orderItemRepository,
        UrlInterface $backendUrl
    ) {
        $this->eventCollection      = $eventCollection;
        $this->localeHelper         = $localeHelper;
        $this->orderItemRepository  = $orderItemRepository;
        $this->backendUrl           = $backendUrl;
    }

    /**
     * @return mixed
     */
    public function getCalendarEvents()
    {
        $result     = [];
        $collection = $this->eventCollection->getFullInfo();
        /** @var  \Dbtours\Calendar\Model\CalendarEvent $item */
        foreach ($collection as $item) {
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
        $bookingUrl = $this->getBookingLink($event->getBookingId());
        $content .= $bookingUrl ? '<p>' . '<b>Booking: </b>' . $bookingUrl . '</p>' : '';

        //Tour
        $content .= $event->getTour() ? '<p>' . '<b>Tour: </b>' . $event->getTour() . '</p>' : '';
        $content .= $event->getLanguageCode() ?
            '<p>' . '<b>Language: </b>' . $this->localeHelper->getFormattedLanguage($event->getLanguageCode()) . '</p>' : '';

        //Guide Info
        $content .= '<p>' .
            '<b>Guide:  </b>' . $event->getFirstname() . " " . $event->getLastname() .
            ' [' . $event->getGuideCode() . ']</p>';

        // Order
        $orderUrl = $this->getOrderLink($event->getOrderItemId());
        $content .= $orderUrl ? '<p>' . '<b>Order: </b>' . $orderUrl . '</p>' : '';

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

    /**
     * @param int $orderItemId
     * @return string
     */
    private function getOrderLink($orderItemId)
    {
        if (!$orderItemId) {
            return '';
        }
        /** @var OrderInterface $order */
        $order = $this->getOrder($orderItemId);
        $url = $this->backendUrl->getUrl('sales/order/view', ['order_id' => $order->getEntityId()]);
        $html = '<a href=\"' . $url . '\" target=\"_blank\" >';
        $html .= $order->getIncrementId();
        $html .= "</a>";

        return $html;
    }

    /**
     * @param int $bookingId
     * @return string
     */
    private function getBookingLink($bookingId)
    {
        if (!$bookingId) {
            return '';
        }
        $url = $this->backendUrl->getUrl('booking/booking/edit', ['id' => $bookingId]);
        $html = '<a href=\"' . $url . '\" target=\"_blank\" >';
        $html .= $bookingId;
        $html .= "</a>";

        return $html;
    }

    /**
     * @param $orderItemId
     * @return OrderInterface
     */
    private function getOrder($orderItemId)
    {
        $orderItem = $this->orderItemRepository->get($orderItemId);

        return $orderItem->getOrder();
    }
}
