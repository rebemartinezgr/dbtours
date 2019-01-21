<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Helper;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Api\TourEventLanguageRepositoryInterface as TourEventLanguageRepository;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class TourEvent
 */
class Validator
{
    /**
     * @var TourEventLanguageRepository
     */
    protected $tourEventLanguageRepository;

    /**
     * Validator constructor.
     * @param TourEventLanguageRepository $tourEventLanguageRepository
     */
    public function __construct(
        TourEventLanguageRepository $tourEventLanguageRepository

    ) {
        $this->tourEventLanguageRepository = $tourEventLanguageRepository;
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
}
