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
     * @var BookingFactory $bookingFactory
     */
    private $bookingFactory;

    /**
     * @var CollectionFactory $bookingCollectionFactory
     */
    private $bookingCollectionFactory;

    /**
     * @var BookingSearchResultsInterfaceFactory $bookingSearchResultsInterfaceFactory
     */
    private $bookingSearchResultsInterfaceFactory;

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
     * @param BookingFactory $bookingFactory
     * @param CollectionFactory $bookingCollectionFactory
     * @param BookingSearchResultsInterfaceFactory $bookingSearchResultsInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        BookingFactory $bookingFactory,
        CollectionFactory $bookingCollectionFactory,
        BookingSearchResultsInterfaceFactory $bookingSearchResultsInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        $this->bookingFactory = $bookingFactory;
        $this->bookingCollectionFactory = $bookingCollectionFactory;
        $this->bookingSearchResultsInterfaceFactory = $bookingSearchResultsInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * @inheritdoc
     */
    public function save(BookingInterface $booking)
    {
        $booking->getResource()->save($booking);

        return $booking;
    }


    /**
     * @inheritdoc
     */
    public function getById($bookingId)
    {
        return $this->get($bookingId);
    }

    /**
     * @inheritdoc
     */
    public function get($value, $attributeCode = null)
    {
        /** @var Booking $booking */
        $booking = $this->bookingFactory->create()->load($value, $attributeCode);

        if (!$booking->getId()) {
            throw new NoSuchEntityException(__('Unable to find booking'));
        }

        return $booking;
    }

    /**
     * @inheritdoc
     */
    public function delete(BookingInterface $booking)
    {

        $bookingId = $booking->getId();
        try {
            $booking->getResource()->delete($booking);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                __('Unable to remove booking %1', $bookingId)
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($bookingId)
    {
        $booking = $this->getById($bookingId);

        return $this->delete($booking);
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->bookingCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection, BookingInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var BookingSearchResultsInterface $searchResults */
        $searchResults = $this->bookingSearchResultsInterfaceFactory->create();
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
        $collection = $this->bookingCollectionFactory->create();
        $collection->deleteAll();
    }
}
