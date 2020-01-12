<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\TourEvent\Model\Source;

use Dbtours\Booking\Api\Data\BookingInterface;
use Dbtours\Booking\Controller\Adminhtml\Booking;
use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Api\TourEventLanguageRepositoryInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Registry;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;

/**
 * Class TourEvent
 */
class TourEvent implements OptionSourceInterface
{
    /**
     * @var
     */
    protected $options;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var TourEventLanguageRepositoryInterface
     */
    private $tourEventLanguageRepository;

    /**
     * @var OrderItemRepositoryInterface
     */
    private $orderItemRepository;

    /**
     * TourEvent constructor.
     * @param Registry $coreRegistry
     * @param TourEventLanguageRepositoryInterface $tourEventLanguageRepository
     * @param OrderItemRepositoryInterface $orderItemRepository
     */
    public function __construct(
        Registry $coreRegistry,
        TourEventLanguageRepositoryInterface $tourEventLanguageRepository,
        OrderItemRepositoryInterface $orderItemRepository
    ) {
        $this->coreRegistry                = $coreRegistry;
        $this->tourEventLanguageRepository = $tourEventLanguageRepository;
        $this->orderItemRepository         = $orderItemRepository;
    }

    /**
     * @return array|null
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [['label' => 'Select a option to change dates and/or language', 'value' => '']];

            /** @var BookingInterface $booking */
            $booking = $this->coreRegistry->registry(Booking::REGISTRY_NAME);
            if ($booking && $booking->getOrderItem()) {
                /** @var  OrderItemInterface $orderItem */
                $orderItem = $this->orderItemRepository->get($booking->getOrderItem());
                // todo get from registry
                $productId = $orderItem->getProductId();
                if ($productId) {
                    $searchResult = $this->tourEventLanguageRepository->getInfoByProductId($productId);
                    /** @var TourEventLanguageInterface $tourEventLanguage */
                    foreach ($searchResult->getItems() as $tourEventLanguage) {
                        $tourEventId  = $tourEventLanguage->getTourEventId();
                        $date         = $tourEventLanguage->getDate();
                        $time         = $tourEventLanguage->getTime();
                        $languageCode = $tourEventLanguage->getLanguageCode();
                        $available    = $tourEventLanguage->getAvailable() ? '(v)' : '(x)';
                        $value        = $tourEventId . "-" . $languageCode;
                        $label           = $date . " " . $time . " - " . $languageCode . " " . $available;
                        $this->options[] = [
                            'label' => $label,
                            'value' => $value
                        ];
                    }
                }
            }
        }
        return $this->options;
    }
}
