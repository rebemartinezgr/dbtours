<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model;

use Dbtours\TourEvent\Api\Data\TourEventInterface;
use Dbtours\TourEvent\Api\Data\TourEventSearchResultsInterface;
use Dbtours\TourEvent\Api\Data\TourEventSearchResultsInterfaceFactory;
use Dbtours\TourEvent\Api\TourEventRepositoryInterface;
use Dbtours\TourEvent\Model\TourEventFactory;
use Dbtours\TourEvent\Model\ResourceModel\TourEvent\Collection;
use Dbtours\TourEvent\Model\ResourceModel\TourEvent\CollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;
use Exception;

/**
 * Class TourEventRepository
 */
class TourEventRepository implements TourEventRepositoryInterface
{
    /**
     * @var TourEventFactory $tourEventFactory
     */
    private $tourEventFactory;

    /**
     * @var CollectionFactory $tourEventCollectionFactory
     */
    private $tourEventCollectionFactory;

    /**
     * @var TourEventSearchResultsInterfaceFactory $tourEventSearchResultsInterfaceFactory
     */
    private $tourEventSearchResultsInterfaceFactory;

    /**
     * @var CollectionProcessorInterface $collectionProcessor
     */
    private $collectionProcessor;

    /**
     * @var JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    private $extensionAttributesJoinProcessor;

    /**
     * TourEventRepository constructor.
     *
     * @param TourEventFactory $tourEventFactory
     * @param CollectionFactory $tourEventCollectionFactory
     * @param TourEventSearchResultsInterfaceFactory $tourEventSearchResultsInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        TourEventFactory $tourEventFactory,
        CollectionFactory $tourEventCollectionFactory,
        TourEventSearchResultsInterfaceFactory $tourEventSearchResultsInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        $this->tourEventFactory = $tourEventFactory;
        $this->tourEventCollectionFactory = $tourEventCollectionFactory;
        $this->tourEventSearchResultsInterfaceFactory = $tourEventSearchResultsInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * @inheritdoc
     */
    public function save(TourEventInterface $tourEvent)
    {
        $tourEvent->getResource()->save($tourEvent);

        return $tourEvent;
    }


    /**
     * @inheritdoc
     */
    public function getById($tourEventId)
    {
        return $this->get($tourEventId);
    }

    /**
     * @inheritdoc
     */
    public function get($value, $attributeCode = null)
    {
        /** @var TourEvent $tourEvent */
        $tourEvent = $this->tourEventFactory->create()->load($value, $attributeCode);

        if (!$tourEvent->getId()) {
            throw new NoSuchEntityException(__('Unable to find tourEvent'));
        }

        return $tourEvent;
    }

    /**
     * @inheritdoc
     */
    public function delete(TourEventInterface $tourEvent)
    {

        $tourEventId = $tourEvent->getId();
        try {
            $tourEvent->getResource()->delete($tourEvent);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                __('Unable to remove tourEvent %1', $tourEventId)
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($tourEventId)
    {
        $tourEvent = $this->getById($tourEventId);

        return $this->delete($tourEvent);
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->tourEventCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection, TourEventInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var TourEventSearchResultsInterface $searchResults */
        $searchResults = $this->tourEventSearchResultsInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}