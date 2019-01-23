<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model\ResourceModel\TourEventLanguage;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface as TourEventLanguage;
use Dbtours\TourEvent\Model\ResourceModel\TourEventLanguage as ResourceModelTourEventLanguage;
use Dbtours\TourEvent\Model\TourEventLanguage as ModelTourEventLanguage;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(ModelTourEventLanguage::class, ResourceModelTourEventLanguage::class);
    }

    /**
     * @param $productId
     * @return $this
     */
    public function addProductIdFilter($productId)
    {
        $this->addFieldToFilter(TourEventLanguage::PRODUCT_ID, $productId)
            ->setOrder(TourEventLanguage::START_TIME, self::SORT_ORDER_ASC);

        return $this;
    }

    /**
     * @param $tourEvent
     * @return $this
     */
    public function addTourEventFilter($tourEvent)
    {
        $this->addFieldToFilter(TourEventLanguage::TOUR_EVENT_ID, $tourEvent);

        return $this;
    }

    /**
     * @param $languageCode
     * @return $this
     */
    public function addLanguageFilter($languageCode)
    {
        $this->addFieldToFilter(TourEventLanguage::LANGUAGE_CODE, $languageCode);

        return $this;
    }

    /**
     * @return $this
     */
    public function addDateTimesLanguageAvailability()
    {
        $this
            ->addFieldToSelect(
                [
                    TourEventLanguage::TOUR_EVENT_ID,
                    TourEventLanguage::LANGUAGE_CODE,
                    TourEventLanguage::AVAILABLE
                ]
            )->removeFieldFromSelect('entity_id')
            ->addExpressionFieldToSelect(
                'time',
                'DATE_FORMAT({{start_time}}, \'%H:%i\')',
                'start_time'
            )->addExpressionFieldToSelect(
                'date',
                'DATE({{start_time}})',
                'start_time'
            );

        return $this;
    }
}
