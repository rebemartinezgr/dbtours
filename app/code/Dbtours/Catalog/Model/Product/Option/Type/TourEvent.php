<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Catalog\Model\Product\Option\Type;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Helper\Validator;
use Dbtours\TourEvent\Helper\Locale;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
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
    private $string;

    /**
     * @var Escaper
     */
    private $escaper = null;

    /**
     * @var Validator
     */
    private $tourEventLanguageValidator;

    /**
     * @var Locale
     */
    private $localeHelper;

    /**
     * TourEvent constructor.
     * @param Session $checkoutSession
     * @param ScopeConfigInterface $scopeConfig
     * @param Escaper $escaper
     * @param StringUtils $string
     * @param Validator $tourEventLanguageValidator
     * @param Locale $localeHelper
     * @param array $data
     */
    public function __construct(
        Session $checkoutSession,
        ScopeConfigInterface $scopeConfig,
        Escaper $escaper,
        StringUtils $string,
        Validator $tourEventLanguageValidator,
        Locale $localeHelper,
        array $data = []
    ) {
        $this->escaper                      = $escaper;
        $this->string                       = $string;
        $this->tourEventLanguageValidator   = $tourEventLanguageValidator;
        $this->localeHelper                 = $localeHelper;
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
        } elseif (isset($values[$option->getId()])) {
            $tourEventLanguage = $this->tourEventLanguageValidator->getTourEventLanguage(
                $values[$option->getId()]['tour_event_id'],
                $values[$option->getId()]['language_code']
            );
            if (!$tourEventLanguage || !$tourEventLanguage->isAvailable()) {
                throw new LocalizedException(__('Selected option(s) are not longer available.'));
            }
        }

        if (isset($tourEventLanguage) && $tourEventLanguage->getStartTime()) {
            $values[$option->getId()][TourEventLanguageInterface::START_TIME] = $tourEventLanguage->getStartTime();
        }
        $this->setUserValue($values[$option->getId()]);
        $this->setIsValid(true);
        return $this;
    }

    /**
     * Prepare option value for cart
     *
     * @return string|null Prepared option value
     */
    public function prepareForCart()
    {
        if ($this->getIsValid() && ($this->getUserValue() !== '')) {
            $value  = $this->getUserValue();
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
        $datetime       = $this->localeHelper->getFormattedDateTime($value[TourEventLanguageInterface::START_TIME]);
        $language       = $this->localeHelper->getFormattedLanguage($value[TourEventLanguageInterface::LANGUAGE_CODE]);
        $formattedValue = $datetime . ' - ' . $language;

        return $this->escaper->escapeHtml($formattedValue);
    }
}
