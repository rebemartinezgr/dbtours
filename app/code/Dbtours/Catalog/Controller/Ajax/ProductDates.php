<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Catalog\Controller\Ajax;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Api\TourEventLanguageRepositoryInterface as TourEventLanguageRepository;
use Dbtours\TourEvent\Helper\Locale;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Context;

/**
 * Class Index
 */
class ProductDates extends Action
{
    /**
     * @var TourEventLanguageRepository
     */
    private $tourEventLanguageRepository;

    /**
     * @var Locale
     */
    private $localeHelper;

    /**
     * ProductDates constructor.
     * @param Context $context
     * @param TourEventLanguageRepository $tourEventLanguageRepository
     * @param Locale $localeHelper
     */
    public function __construct(
        Context $context,
        TourEventLanguageRepository $tourEventLanguageRepository,
        Locale $localeHelper
    ) {

        $this->tourEventLanguageRepository = $tourEventLanguageRepository;
        $this->localeHelper                = $localeHelper;
        parent::__construct($context);
    }

    /**
     * @return Json
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        if (!$this->getRequest()->isAjax()) {
            return $resultJson->setData([]);
        }
        $productId      = $this->getRequest()->getParam('productId');
        $tourOptions    = $this->getTourOptions($productId);
        $result = [
            'dates'     => array_keys($tourOptions),
            'options'   => $tourOptions,
            'languages' => $this->getLanguagesOptions($tourOptions)
        ];

        return $resultJson->setData($result);
    }

    /**
     * @param $productId
     * @return array
     */
    private function getTourOptions($productId)
    {
        $result = [];
        if ($productId) {
            try {
                $searchResult = $this->tourEventLanguageRepository->getInfoByProductId($productId);
                /** @var TourEventLanguageInterface $tourEventLanguage */
                foreach ($searchResult->getItems() as $tourEventLanguage) {
                    $tourEventId    = $tourEventLanguage->getTourEventId();
                    $date           = $tourEventLanguage->getDate();
                    $time           = $tourEventLanguage->getTime();
                    $languageCode   = $tourEventLanguage->getLanguageCode();
                    $available      = $tourEventLanguage->getAvailable();

                    $result[$date][$languageCode][$time] = $available ? $tourEventId : 0;
                }
            } catch (\Exception $e) {
                //todo log error
                $result = [];
            }
        }
        return $result;
    }

    /**
     * @param $tourOptions
     * @param $locale
     * @return array
     */
    private function getLanguagesOptions($tourOptions)
    {
        $languageCodes = [];
        array_map(function ($element) use (&$languageCodes) {
            $languageCodes = array_unique(array_merge($languageCodes, array_keys($element)));
            return $languageCodes;
        }, $tourOptions);

        $language = [];
        array_map(function ($languageCode) use (&$language) {
            $language[$languageCode] = $this->localeHelper->getFormattedLanguage($languageCode);
            return $language;
        }, $languageCodes);

        return $language;
    }
}
