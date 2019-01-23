<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Helper;

use Zend_Date;
use Zend_Locale;
use Magento\Framework\Stdlib\Datetime;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Class Locale
 */
class Locale
{
    /**
     * @var ResolverInterface
     */
    private $locale;

    /**
     * Validator constructor.
     * @param ResolverInterface $locale
     */
    public function __construct(
        ResolverInterface $locale

    ) {
        $this->locale = $locale;
    }

    /**
     * @param string $dateTime
     * @return string
     */
    public function getFormattedDateTime($dateTime)
    {
        if (!isset($dateTime)) {
            return '';
        }

        try {
            $locale = $this->locale->getLocale();
            $date   = new Zend_Date($dateTime, Datetime::DATETIME_INTERNAL_FORMAT, $locale);

            return  $date->toString(Zend_Date::DATE_FULL) . ' ' . $date->toString(Zend_Date::TIME_SHORT);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * @param string $languageCode
     * @return string
     */
    public function getFormattedLanguage($languageCode)
    {
        if (!isset($languageCode)) {
            return '';
        }

        try {
            $locale     = $this->locale->getLocale();
            $language   = Zend_Locale::getTranslation($languageCode, 'language', $locale);

            return ucwords($language);
        } catch (\Exception $e) {
            return '';
        }
    }
}
