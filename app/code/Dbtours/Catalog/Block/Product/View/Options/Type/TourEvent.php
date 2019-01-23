<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */
namespace Dbtours\Catalog\Block\Product\View\Options\Type;

use Magento\Catalog\Block\Product\View\Options\Type\Date;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Catalog\Model\Product\Option\Type\Date as MagentoDate;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Class TourEvent
 */
class TourEvent extends Date
{
    const PRODUCT_DATE_OPTION_URL_PATH = 'dbcatalog/ajax/ProductDates';

    /**
     * @var ResolverInterface
     */
    private $locale;

    /**
     * TourEvent constructor.
     * @param Context $context
     * @param PricingHelper $pricingHelper
     * @param CatalogHelper $catalogData
     * @param MagentoDate $catalogProductOptionTypeDate
     * @param ResolverInterface $locale
     * @param array $data
     */
    public function __construct(
        Context $context,
        PricingHelper $pricingHelper,
        CatalogHelper $catalogData,
        MagentoDate $catalogProductOptionTypeDate,
        ResolverInterface $locale,
        array $data = []
    ) {
        $this->locale = $locale;
        parent::__construct($context, $pricingHelper, $catalogData, $catalogProductOptionTypeDate, $data);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCalendarDateHtml()
    {
        $yearStart  = $this->_catalogProductOptionTypeDate->getYearStart();
        $yearEnd    = $this->_catalogProductOptionTypeDate->getYearEnd();
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        /** Escape RTL characters which are present in some locales and corrupt formatting */
        $escapedDateFormat = preg_replace('/[^MmDdYy\/\.\-]/', '', $dateFormat);
        $calendar = $this->getLayout()->createBlock(
            \Magento\Framework\View\Element\Template::class
        )->setTemplate('Dbtours_Catalog::catalog/product/view/element/html/tour-event.phtml')
            ->setId(
                'options_' . $this->getOption()->getId() . '_tourevent'
            )->setName(
                'options[' . $this->getOption()->getId() . ']'
            )->setClass(
                'product-custom-option datetime-picker input-text'
            )->setDateFormat(
                $escapedDateFormat
            )->setYearsRange(
                $yearStart . ':' . $yearEnd
            )->setProductId(
                $this->getProduct()->getId()
            )->setDataUrl(
                $this->getDatesUrl()
            );

        return $calendar->toHtml();
    }

    /**
     * @return string
     */
    public function getDatesUrl()
    {
        return $this->getUrl(self::PRODUCT_DATE_OPTION_URL_PATH);
    }
}
