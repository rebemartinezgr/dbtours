<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Catalog\Model\Product\Option\Type;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Api\TourEventLanguageRepositoryInterface as TourEventLanguageRepository;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\StringUtils;

/**
 * Class TourEvent
 */
class TourEvent extends \Magento\Catalog\Model\Product\Option\Type\DefaultType
{
    const OPTION_GROUP_NAME = 'tourevent';

    const OPTION_TYPE_NAME = 'tourevent';

    /**
     * Magento string lib
     *
     * @var StringUtils
     */
    protected $string;

    /**
     * @var Escaper
     */
    protected $escaper = null;

    /**
     * @var TourEventLanguageRepository
     */
    protected $tourEventLanguageRepository;

    /**
     * TourEvent constructor.
     * @param Session $checkoutSession
     * @param ScopeConfigInterface $scopeConfig
     * @param Escaper $escaper
     * @param StringUtils $string
     * @param TourEventLanguageRepository $tourEventLanguageRepository
     * @param array $data
     */
    public function __construct(
        Session $checkoutSession,
        ScopeConfigInterface $scopeConfig,
        Escaper $escaper,
        StringUtils $string,
        TourEventLanguageRepository $tourEventLanguageRepository,
        array $data = []
    )
    {
        $this->escaper                     = $escaper;
        $this->string                      = $string;
        $this->tourEventLanguageRepository = $tourEventLanguageRepository;
        parent::__construct($checkoutSession, $scopeConfig, $data);
    }

    /**
     * Validate user input for tour event
     *
     * @param array $values All product option values, i.e. array (option_id => mixed, option_id => mixed...)
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validateUserValue($values)
    {
        parent::validateUserValue($values);

        $this->setIsValid(false);
        $option = $this->getOption();

        if (!isset($values[$option->getId()]) && $option->getIsRequire() && !$this->getSkipCheckRequiredOption()) {
            throw new LocalizedException(__('Please specify product\'s required option(s).'));
        } elseif (isset($values[$option->getId()]) && !$this->validateAvailability($values[$option->getId()])) {
            throw new LocalizedException(__('Selected option(s) are not longer available.'));
        }
        $this->setUserValue($values[$option->getId()]);
        $this->setIsValid(true);
        return $this;
    }

    /**
     * @param $value
     * @return bool
     */
    private function validateAvailability($value)
    {
        if (isset($value['language_code']) && isset($value['tour_event_id'])) {
            /** @var TourEventLanguageInterface $tourEventLanguage */
            $tourEventLanguage = $this->getTourEventLanguage($value);
            return $tourEventLanguage ? (bool)$tourEventLanguage->getAvailable() : false;
        }
        return false;
    }

    /**
     * @param $value
     * @return bool|TourEventLanguageInterface
     */
    private function getTourEventLanguage($value)
    {
        try {
            $tourEventLanguage = $this->tourEventLanguageRepository->getByIdAndLanguage(
                $value[TourEventLanguageInterface::TOUR_EVENT_ID],
                $value[TourEventLanguageInterface::LANGUAGE_CODE]
            );
            return $tourEventLanguage;
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * Prepare option value for cart
     *
     * @return string|null Prepared option value
     */
    public function prepareForCart()
    {
        if ($this->getIsValid() && ($this->getUserValue() !== '')) {
            $value             = $this->getUserValue();
            $tourEventLanguage = $this->getTourEventLanguage($value);
            if ($tourEventLanguage) {
                $value[TourEventLanguageInterface::START_TIME] = $tourEventLanguage->getStartTime();
            }
            return json_encode($value);
        } else {
            return null;
        }
    }

    /**
     * Return formatted option value for quote option
     *
     * @param string $value Prepared for cart option value
     * @return string
     */
    public function getFormattedOptionValue($value)
    {
        $value          = json_decode($value, true);
        $startTime      = isset($value[TourEventLanguageInterface::START_TIME]) ?
            $value[TourEventLanguageInterface::START_TIME] : '';
        $languageCode   = isset($value[TourEventLanguageInterface::LANGUAGE_CODE]) ?
            $value[TourEventLanguageInterface::LANGUAGE_CODE] : '';
        $formattedValue = $startTime . ' - ' . $languageCode;
        // TODO format date&time
        // TODO load language Label
        return $this->escaper->escapeHtml($formattedValue);
    }
}
