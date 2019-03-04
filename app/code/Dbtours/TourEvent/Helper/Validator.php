<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Helper;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Api\TourEventLanguageRepositoryInterface as TourEventLanguageRepository;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;

/**
 * Class TourEvent
 */
class Validator
{
    /**
     * @var TourEventLanguageRepository
     */
    private $tourEventLanguageRepository;

    /**
     * @var State
     */
    private $state;

    /**
     * @var array
     */
    private $validateAreas;

    /**
     * Validator constructor.
     * @param TourEventLanguageRepository $tourEventLanguageRepository
     * @param State $state
     * @param array $data
     */
    public function __construct(
        TourEventLanguageRepository $tourEventLanguageRepository,
        State $state,
        $data = []
    ) {
        $this->tourEventLanguageRepository  = $tourEventLanguageRepository;
        $this->state                        = $state;
        if (!empty($data) && isset($data['validate_area'])) {
            $this->validateAreas            = $data['validate_area'];
        }
    }

    /**
     * @param $tourEventId
     * @param $languageCode
     * @return bool
     */
    public function validateAvailability($tourEventId, $languageCode)
    {
        if (isset($tourEventId) && isset($languageCode)) {
            /** @var TourEventLanguageInterface $tourEventLanguage */
            $tourEventLanguage = $this->getTourEventLanguage($tourEventId, $languageCode);
            return $tourEventLanguage ? (bool)$tourEventLanguage->isAvailable() : false;
        }
        return false;
    }

    /**
     * @param $tourEventId
     * @param $languageCode
     * @return TourEventLanguageInterface | bool
     */
    public function getTourEventLanguage($tourEventId, $languageCode)
    {
        try {
            $tourEventLanguage = $this->tourEventLanguageRepository->getByIdAndLanguage($tourEventId, $languageCode);
            return $tourEventLanguage;
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * @param Item|null $quoteItem
     * @return bool
     */
    public function shouldValidate($quoteItem = null)
    {
        $validateByArea = false;
        try {
            $currentArea = $this->state->getAreaCode();
            if (in_array($currentArea, $this->validateAreas)) {
                $validateByArea = true;
            }
        } catch (LocalizedException $e) {

        }

        if ($quoteItem == null) {
            return $validateByArea;
        }

        return !(!$validateByArea ||
            !$quoteItem ||
            !$quoteItem->getProductId() ||
            !$quoteItem->getQuote() ||
            $quoteItem->getQuote()->getReservedOrderId());
    }
}
