<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */
namespace Dbtours\Calendar\Observer;

use Dbtours\Calendar\Service\CalendarManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class NewBookingObserver
 */
class NewBookingObserver implements ObserverInterface
{
    /**
     * @var CalendarManager $calendarManager
     */
    protected $calendarManager;

    /**
     * @param CalendarManager $calendarManager
     */
    public function __construct(
        CalendarManager $calendarManager
    ) {
        $this->calendarManager = $calendarManager;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $this->calendarManager->addCalendarEventsFromOrder($order);
    }
}
