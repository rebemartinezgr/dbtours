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
     * Save booking
     *
     * @param \Dbtours\Booking\Api\Data\BookingInterface $booking
     * @return \Dbtours\Booking\Api\Data\BookingInterface
     */
    public function save(\Dbtours\Booking\Api\Data\BookingInterface $booking);

    /**
     * Retrieve booking by ID
     *
     * @param int $bookingId
     * @return \Dbtours\Booking\Api\Data\BookingInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($bookingId);

    /**
     * Retrieve booking by attribute
     *
     * @param $value
     * @param string|null $attributeCode
     * @return \Dbtours\Booking\Api\Data\BookingInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($value, $attributeCode);

    /**
     * Delete booking
     *
     * @param \Dbtours\Booking\Api\Data\BookingInterface $booking
     * @return boolean
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Dbtours\Booking\Api\Data\BookingInterface $booking);

    /**
     * Delete booking by ID
     *
     * @param int $booking
     * @return boolean
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($booking);

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
