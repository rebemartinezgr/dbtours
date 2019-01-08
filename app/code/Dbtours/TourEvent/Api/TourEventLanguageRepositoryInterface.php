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
     * Save tour event
     *
     * @param \Dbtours\TourEvent\Api\Data\TourEventLanguageInterface $tourEvent
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageInterface
     */
    public function save(\Dbtours\TourEvent\Api\Data\TourEventLanguageInterface $tourEvent);

    /**
     * Retrieve tour event by ID
     *
     * @param int $tourEventId
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($tourEventId);

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
     * Delete tour event
     *
     * @param \Dbtours\TourEvent\Api\Data\TourEventLanguageInterface $tourEvent
     * @return boolean
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Dbtours\TourEvent\Api\Data\TourEventLanguageInterface $tourEvent);

    /**
     * Delete tour event by ID
     *
     * @param int $tourEvent
     * @return boolean
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($tourEvent);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dbtours\TourEvent\Api\Data\TourEventLanguageSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
