<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model;

use Dbtours\Calendar\Api\Data\CalendarEventInterface;
use Dbtours\Calendar\Api\Data\CalendarEventSearchResultsInterface;
use Dbtours\Calendar\Api\Data\CalendarEventSearchResultsInterfaceFactory;
use Dbtours\Calendar\Api\CalendarEventRepositoryInterface;
use Dbtours\Calendar\Model\CalendarEventFactory;
use Dbtours\Calendar\Model\ResourceModel\CalendarEvent\Collection;
use Dbtours\Calendar\Model\ResourceModel\CalendarEvent\CollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;
use Exception;

/**
 * Class CalendarEventRepository
 */
class CalendarEventRepository implements CalendarEventRepositoryInterface
{
    /**
     * @var CalendarEventFactory $calendarEventFactory
     */
    private $calendarEventFactory;

    /**
     * @var CollectionFactory $calendarEventCollectionFactory
     */
    private $calendarEventCollectionFactory;

    /**
     * @var CalendarEventSearchResultsInterfaceFactory $calendarEventSearchResultsInterfaceFactory
     */
    private $calendarEventSearchResultsInterfaceFactory;

    /**
     * @var CollectionProcessorInterface $collectionProcessor
     */
    private $collectionProcessor;

    /**
     * @var JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    private $extensionAttributesJoinProcessor;

    /**
     * CalendarEventRepository constructor.
     *
     * @param CalendarEventFactory $calendarEventFactory
     * @param CollectionFactory $calendarEventCollectionFactory
     * @param CalendarEventSearchResultsInterfaceFactory $calendarEventSearchResultsInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        CalendarEventFactory $calendarEventFactory,
        CollectionFactory $calendarEventCollectionFactory,
        CalendarEventSearchResultsInterfaceFactory $calendarEventSearchResultsInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        $this->calendarEventFactory = $calendarEventFactory;
        $this->calendarEventCollectionFactory = $calendarEventCollectionFactory;
        $this->calendarEventSearchResultsInterfaceFactory = $calendarEventSearchResultsInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * @inheritdoc
     */
    public function save(CalendarEventInterface $calendarEvent)
    {
        $calendarEvent->getResource()->save($calendarEvent);

        return $calendarEvent;
    }


    /**
     * @inheritdoc
     */
    public function getById($calendarEventId)
    {
        return $this->get($calendarEventId);
    }

    /**
     * @inheritdoc
     */
    public function get($value, $attributeCode = null)
    {
        /** @var CalendarEvent $calendarEvent */
        $calendarEvent = $this->calendarEventFactory->create()->load($value, $attributeCode);

        if (!$calendarEvent->getId()) {
            throw new NoSuchEntityException(__('Unable to find calendarEvent'));
        }

        return $calendarEvent;
    }

    /**
     * @inheritdoc
     */
    public function delete(CalendarEventInterface $calendarEvent)
    {

        $calendarEventId = $calendarEvent->getId();
        try {
            $calendarEvent->getResource()->delete($calendarEvent);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                __('Unable to remove calendarEvent %1', $calendarEventId)
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($calendarEventId)
    {
        $calendarEvent = $this->getById($calendarEventId);

        return $this->delete($calendarEvent);
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->calendarEventCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection, CalendarEventInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var CalendarEventSearchResultsInterface $searchResults */
        $searchResults = $this->calendarEventSearchResultsInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
