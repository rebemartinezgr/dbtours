<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Api;

/**
 * Interface CalendarEventRepositoryInterface
 */
interface CalendarEventRepositoryInterface
{
    /**
     * Save calendar event
     *
     * @param \Dbtours\Calendar\Api\Data\CalendarEventInterface $calendarEvent
     * @return \Dbtours\Calendar\Api\Data\CalendarEventInterface
     */
    public function save(\Dbtours\Calendar\Api\Data\CalendarEventInterface $calendarEvent);

    /**
     * Retrieve calendar event by ID
     *
     * @param int $calendarEventId
     * @return \Dbtours\Calendar\Api\Data\CalendarEventInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($calendarEventId);

    /**
     * Retrieve calendar event by attribute
     *
     * @param $value
     * @param string|null $attributeCode
     * @return \Dbtours\Calendar\Api\Data\CalendarEventInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($value, $attributeCode);

    /**
     * Delete calendar event
     *
     * @param \Dbtours\Calendar\Api\Data\CalendarEventInterface $calendarEvent
     * @return boolean
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Dbtours\Calendar\Api\Data\CalendarEventInterface $calendarEvent);

    /**
     * Delete calendar event by ID
     *
     * @param int $calendarEvent
     * @return boolean
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($calendarEvent);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dbtours\Calendar\Api\Data\CalendarEventSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $orderItemId
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteByOrderItemId($orderItemId);
}
