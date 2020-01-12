<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Catalog\Block\Adminhtml\Product\View\Options\Type;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Api\TourEventLanguageRepositoryInterface;
use Magento\Catalog\Block\Product\View\Options\AbstractOptions;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Framework\View\Element\Html\Select;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class TourEvent
 */
class TourEvent extends AbstractOptions
{
    private $tourEventLanguageRepository;

    private $tourEventOptions;

    /**
     * TourEvent constructor.
     * @param Context $context
     * @param PricingHelper $pricingHelper
     * @param CatalogHelper $catalogData
     * @param TourEventLanguageRepositoryInterface $tourEventLanguageRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        PricingHelper $pricingHelper,
        CatalogHelper $catalogData,
        TourEventLanguageRepositoryInterface $tourEventLanguageRepository,
        array $data = []
    ) {
        $this->tourEventLanguageRepository = $tourEventLanguageRepository;
        parent::__construct($context, $pricingHelper, $catalogData, $data);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getValuesHtml()
    {
        $_option = $this->getOption();
        $this->setSkipJsReloadPrice(1);
        $require = $_option->getIsRequire() ? ' required' : '';
        /** @var  Select $select */
        $select = $this->getLayout()->createBlock(
            Select::class
        )->setData(
            [
                'id'    => 'select_' . $_option->getId(),
                'class' => $require . ' product-custom-option admin__control-select'
            ]
        );
        $select->setName('options[' . $_option->getId() . '][tour_event_language]')->addOption(
            '',
            __('-- Please  Select --')
        );

        foreach ($this->getOptions() as $k => $value) {
            $params['data-language-code'] = $value['language_code'] ?? $value['language_code'];
            $params['data-tour-event-id'] = $value['tour_event_id'] ?? $value['tour_event_id'];
            $select->addOption(
                $k,
                $value['label'],
                $params
            );
        }

        $configValue = $this->getValue();
        if ($configValue) {
            $select->setValue($configValue);
        }
        return $select->getHtml();
    }

    /**
     * @return string
     */
    private function getValue()
    {
        $_option     = $this->getOption();
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        if ($configValue) {
            $value = $configValue['tour_event_language'];
        }
        return $value ?? '';
    }

    /**
     * @return array
     */
    private function getOptions()
    {
        return $this->getTourEventOptions();
    }

    /**
     * @return array
     */
    private function getTourEventOptions()
    {
        $productId = $this->getProduct()->getId();
        if ($this->tourEventOptions == null) {
            $searchResult = $this->tourEventLanguageRepository->getInfoByProductId($productId);
            /** @var TourEventLanguageInterface $tourEventLanguage */
            foreach ($searchResult->getItems() as $tourEventLanguage) {
                $tourEventId  = $tourEventLanguage->getTourEventId();
                $date         = $tourEventLanguage->getDate();
                $time         = $tourEventLanguage->getTime();
                $languageCode = $tourEventLanguage->getLanguageCode();
                $available    = $tourEventLanguage->getAvailable() ? '(v)' : '(x)';

                $label = $date . " " . $time . " - " . $languageCode . " " . $available;
                $result[$tourEventId . "-" . $languageCode] = [
                    'label'         => $label,
                    'language_code' => $languageCode,
                    'tour_event_id' => $tourEventId
                ];
            }
            $this->tourEventOptions = $result ?? [];
        }
        return $this->tourEventOptions;
    }
}
