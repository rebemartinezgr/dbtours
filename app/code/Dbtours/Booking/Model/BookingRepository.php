<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Model;

use Dbtours\Booking\Api\Data\BookingInterface;
use Dbtours\Booking\Api\Data\BookingSearchResultsInterface;
use Dbtours\Booking\Api\Data\BookingSearchResultsInterfaceFactory;
use Dbtours\Booking\Api\BookingRepositoryInterface;
use Dbtours\Booking\Model\BookingFactory;
use Dbtours\Booking\Model\ResourceModel\Booking\Collection;
use Dbtours\Booking\Model\ResourceModel\Booking\CollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;
use Exception;

/**
 * Class BookingRepository
 */
class BookingRepository implements BookingRepositoryInterface
{
    /**
     * @var BookingFactory $tourEventFactory
     */
    private $tourEventFactory;

    /**
     * @var CollectionFactory $tourEventCollectionFactory
     */
    private $tourEventCollectionFactory;

    /**
     * @var BookingSearchResultsInterfaceFactory $tourEventSearchResultsInterfaceFactory
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
     * BookingRepository constructor.
     *
     * @param BookingFactory $tourEventFactory
     * @param CollectionFactory $tourEventCollectionFactory
     * @param BookingSearchResultsInterfaceFactory $tourEventSearchResultsInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        BookingFactory $tourEventFactory,
        CollectionFactory $tourEventCollectionFactory,
        BookingSearchResultsInterfaceFactory $tourEventSearchResultsInterfaceFactory,
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
    public function save(BookingInterface $tourEvent)
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
        /** @var Booking $tourEvent */
        $tourEvent = $this->tourEventFactory->create()->load($value, $attributeCode);

        if (!$tourEvent->getId()) {
            throw new NoSuchEntityException(__('Unable to find tourEvent'));
        }

        return $tourEvent;
    }

    /**
     * @inheritdoc
     */
    public function delete(BookingInterface $tourEvent)
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

        $this->extensionAttributesJoinProcessor->process($collection, BookingInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var BookingSearchResultsInterface $searchResults */
        $searchResults = $this->tourEventSearchResultsInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function deleteAll()
    {
        /** @var Collection $collection */
        $collection = $this->tourEventCollectionFactory->create();
        $collection->deleteAll();
    }
}
