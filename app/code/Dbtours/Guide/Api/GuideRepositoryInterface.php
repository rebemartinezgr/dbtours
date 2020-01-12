<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Api;

/**
 * Interface GuideRepositoryInterface
 */
interface GuideRepositoryInterface
{
    /**
     * Save guide
     *
     * @param \Dbtours\Guide\Api\Data\GuideInterface $guide
     * @return \Dbtours\Guide\Api\Data\GuideInterface
     */
    public function save(\Dbtours\Guide\Api\Data\GuideInterface $guide);

    /**
     * Retrieve guide by ID
     *
     * @param int $guideId
     * @return \Dbtours\Guide\Api\Data\GuideInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($guideId);

    /**
     * Retrieve guide by attribute
     *
     * @param $value
     * @param string|null $attributeCode
     * @return \Dbtours\Guide\Api\Data\GuideInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($value, $attributeCode);

    /**
     * Delete guide
     *
     * @param \Dbtours\Guide\Api\Data\GuideInterface $guide
     * @return boolean
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Dbtours\Guide\Api\Data\GuideInterface $guide);

    /**
     * Delete guide by ID
     *
     * @param int $guide
     * @return boolean
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($guide);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dbtours\Guide\Api\Data\GuideSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function deleteAll();
}
