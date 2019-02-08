<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Service;

use Dbtours\Booking\Api\BookingRepositoryInterface;
use Dbtours\Booking\Api\Data\BookingInterface;
use Dbtours\Booking\Api\Data\BookingInterfaceFactory;
use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface as TourEventLanguage;
use Magento\Sales\Api\Data\OrderItemInterface;

/**
 * Class BookingManager
 */
class BookingManager
{
    /**
     * @var BookingInterfaceFactory
     */
    private $bookingFactory;

    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;

    /**
     * BookingManager constructor.
     * @param BookingInterfaceFactory $bookingFactory
     * @param BookingRepositoryInterface $bookingRepository
     */
    public function __construct(
        BookingInterfaceFactory $bookingFactory,
        BookingRepositoryInterface $bookingRepository
    ) {
        $this->bookingFactory    = $bookingFactory;
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * @param TourEventLanguage $tourEventLanguage
     * @param $orderItem
     * @return BookingInterface
     */
    public function addNewBooking($tourEventLanguage, $orderItem)
    {
        $guide   = $tourEventLanguage->getAvailableGuide();
        $booking = $this->getBooking($tourEventLanguage, $orderItem);
        if ($guide) {
            $booking = $this->assignToGuide($booking, $guide);
        }
        return $booking;
    }

    /**
     * @param TourEventLanguage $tourEventLanguage
     * @param OrderItemInterface $orderItem
     * @return BookingInterface
     */
    public function getBooking($tourEventLanguage, $orderItem)
    {
        /** @var BookingInterface $booking */
        $booking = $this->bookingFactory->create();
        $product = $orderItem->getProduct();
        $booking->setOrderItem($orderItem->getItemId());
        $booking->setTour($orderItem->getSku());
        $booking->setLanguage($tourEventLanguage->getLanguageCode());
        $booking->setStartTime($tourEventLanguage->getStartTime());
        $booking->setFinishTime($tourEventLanguage->getFinishTime());
        $booking->setBlockedBefore($tourEventLanguage->getBlockedBefore());
        $booking->setBlockedAfter($tourEventLanguage->getBlockedAfter());
        $booking->setTips($product->getData('db_tips'));
        $booking->setDuration($product->getData('db_duration'));
        $booking->setIncluded($product->getData('db_included'));

        return $booking;
    }

    /**
     * @param BookingInterface $booking
     * @param int $guideId
     * @return BookingInterface
     */
    public function assignToGuide($booking, $guideId)
    {
        $booking->setGuideId($guideId);
        return $this->bookingRepository->save($booking);
    }
}
