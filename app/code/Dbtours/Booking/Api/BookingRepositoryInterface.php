<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Api;

/**
 * Interface BookingRepositoryInterface
 */
interface BookingRepositoryInterface
{
    /**
     * Save tour event
     *
     * @param \Dbtours\Booking\Api\Data\BookingInterface $tourEvent
     * @return \Dbtours\Booking\Api\Data\BookingInterface
     */
    public function save(\Dbtours\Booking\Api\Data\BookingInterface $tourEvent);

    /**
     * Retrieve tour event by ID
     *
     * @param int $tourEventId
     * @return \Dbtours\Booking\Api\Data\BookingInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($tourEventId);

    /**
     * Retrieve tour event by attribute
     *
     * @param $value
     * @param string|null $attributeCode
     * @return \Dbtours\Booking\Api\Data\BookingInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($value, $attributeCode);

    /**
     * Delete tour event
     *
     * @param \Dbtours\Booking\Api\Data\BookingInterface $tourEvent
     * @return boolean
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Dbtours\Booking\Api\Data\BookingInterface $tourEvent);

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
     * @return \Dbtours\Booking\Api\Data\BookingSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function deleteAll();
}
