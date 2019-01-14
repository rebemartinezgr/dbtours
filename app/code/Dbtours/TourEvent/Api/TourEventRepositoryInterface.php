<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Api;

/**
 * Interface TourEventRepositoryInterface
 */
interface TourEventRepositoryInterface
{
    /**
     * Save tour event
     *
     * @param \Dbtours\TourEvent\Api\Data\TourEventInterface $tourEvent
     * @return \Dbtours\TourEvent\Api\Data\TourEventInterface
     */
    public function save(\Dbtours\TourEvent\Api\Data\TourEventInterface $tourEvent);

    /**
     * Retrieve tour event by ID
     *
     * @param int $tourEventId
     * @return \Dbtours\TourEvent\Api\Data\TourEventInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($tourEventId);

    /**
     * Retrieve tour event by attribute
     *
     * @param $value
     * @param string|null $attributeCode
     * @return \Dbtours\TourEvent\Api\Data\TourEventInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($value, $attributeCode);

    /**
     * Delete tour event
     *
     * @param \Dbtours\TourEvent\Api\Data\TourEventInterface $tourEvent
     * @return boolean
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Dbtours\TourEvent\Api\Data\TourEventInterface $tourEvent);

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
     * @return \Dbtours\TourEvent\Api\Data\TourEventSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function deleteAll();
}
