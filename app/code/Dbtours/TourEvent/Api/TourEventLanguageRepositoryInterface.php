<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Api;

/**
 * Interface TourEventLanguageRepositoryInterface
 */
interface TourEventLanguageRepositoryInterface
{
    /**
     * Retrieve tour event by attribute
     *
     * @param $value
     * @param string|null $attributeCode
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($value, $attributeCode);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $productId
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageSearchResultsInterface
     */
    public function getInfoByProductId($productId);

    /**
     * @param $tourEventId
     * @param $languageCode
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByIdAndLanguage($tourEventId, $languageCode);
}
