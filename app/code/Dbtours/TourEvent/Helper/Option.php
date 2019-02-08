<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Helper;

use Dbtours\Catalog\Model\Product\Option\Type\TourEvent;
use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface as TourEventLanguage;
use Dbtours\TourEvent\Model\TourEventLanguageRepository;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Option
 */
class Option
{
    /**
     * @var TourEventLanguageRepository
     */
    private $tourEventLanguageRepository;

    /**
     * BookingManager constructor.
     * @param TourEventLanguageRepository $tourEventLanguageRepository
     */
    public function __construct(
        TourEventLanguageRepository $tourEventLanguageRepository

    ) {
        $this->tourEventLanguageRepository = $tourEventLanguageRepository;
    }

    /**
     * @param array $option
     * @return bool
     */
    public function hasTourEventOption($option)
    {
        return isset($option['option_type']) && $option['option_type'] == TourEvent::OPTION_TYPE_NAME;
    }

    /**
     * @param $optionValue
     * @return TourEventLanguage
     * @throws NoSuchEntityException
     */
    public function getTourEventLanguageFromOption($optionValue)
    {
        $value             = json_decode($optionValue, true);
        $tourEventId       = $value[TourEventLanguage::TOUR_EVENT_ID];
        $languageCode      = $value[TourEventLanguage::LANGUAGE_CODE];
        $tourEventLanguage = $this->tourEventLanguageRepository->getByIdAndLanguage($tourEventId, $languageCode);

        return $tourEventLanguage;
    }

    /**
     * @param $orderItem
     * @return TourEventLanguage|null
     */
    public function getTourEventLanguage($orderItem)
    {
        $tourEventLanguage = null;
        $options           = $orderItem->getProductOptions();
        if (!isset($options['options'])) {
            return $tourEventLanguage;
        }

        foreach ($options['options'] as $option) {
            if (!$this->hasTourEventOption($option)) {
                continue;
            }
            try {
                $tourEventLanguage = $this->getTourEventLanguageFromOption($option['option_value']);
            } catch (\Exception $e) {
                return false;
            }
        }
        return $tourEventLanguage;
    }
}
