<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Api\Data\TourEventLanguageSearchResultsInterfaceFactory;
use Dbtours\TourEvent\Api\Data\TourEventSearchResultsInterface;
use Dbtours\TourEvent\Api\TourEventLanguageRepositoryInterface;
use Dbtours\TourEvent\Model\ResourceModel\TourEventLanguage\Collection;
use Dbtours\TourEvent\Model\ResourceModel\TourEventLanguage\CollectionFactory;
use Dbtours\TourEvent\Model\TourEventLanguageFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class TourEventLanguageRepository
 */
class TourEventLanguageRepository implements TourEventLanguageRepositoryInterface
{
    /**
     * @var TourEventLanguageFactory $tourEventLanguageFactory
     */
    private $tourEventLanguageFactory;

    /**
     * @var CollectionFactory $tourEventLanguageCollectionFactory
     */
    private $tourEventLanguageCollectionFactory;

    /**
     * @var TourEventLanguageSearchResultsInterfaceFactory $tourEventLanguageSearchResultsInterfaceFactory
     */
    private $tourEventLanguageSearchResultsInterfaceFactory;

    /**
     * @var CollectionProcessorInterface $collectionProcessor
     */
    private $collectionProcessor;

    /**
     * @var JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    private $extensionAttributesJoinProcessor;

    /**
     * TourEventLanguageRepository constructor.
     *
     * @param TourEventLanguageFactory $tourEventLanguageFactory
     * @param CollectionFactory $tourEventLanguageCollectionFactory
     * @param TourEventLanguageSearchResultsInterfaceFactory $tourEventLanguageSearchResultsInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        TourEventLanguageFactory $tourEventLanguageFactory,
        CollectionFactory $tourEventLanguageCollectionFactory,
        TourEventLanguageSearchResultsInterfaceFactory $tourEventLanguageSearchResultsInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        $this->tourEventLanguageFactory                       = $tourEventLanguageFactory;
        $this->tourEventLanguageCollectionFactory             = $tourEventLanguageCollectionFactory;
        $this->tourEventLanguageSearchResultsInterfaceFactory = $tourEventLanguageSearchResultsInterfaceFactory;
        $this->collectionProcessor                            = $collectionProcessor;
        $this->extensionAttributesJoinProcessor               = $extensionAttributesJoinProcessor;
    }

    /**
     * @inheritdoc
     */
    public function get($value, $attributeCode = null)
    {
        /** @var TourEventLanguage $tourEventLanguage */
        $tourEventLanguage = $this->tourEventLanguageFactory->create()->load($value, $attributeCode);

        if (!$tourEventLanguage->getProductId() || !$tourEventLanguage->getTourEventId()) {
            throw new NoSuchEntityException(__('Unable to find tourEventLanguage'));
        }

        return $tourEventLanguage;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->tourEventLanguageCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection, TourEventLanguageInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var TourEventSearchResultsInterface $searchResults */
        $searchResults = $this->tourEventLanguageSearchResultsInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function getInfoByProductId($productId)
    {
        /** @var Collection $collection */
        $collection = $this->tourEventLanguageCollectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection, TourEventLanguageInterface::class);
        $collection->addProductIdFilter($productId)->addDateTimesLanguageAvailability();
        /** @var TourEventSearchResultsInterface $searchResults */
        $searchResults = $this->tourEventLanguageSearchResultsInterfaceFactory->create();
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function getByIdAndLanguage($tourEventId, $languageCode)
    {
        /** @var Collection $collection */
        $collection = $this->tourEventLanguageCollectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection, TourEventLanguageInterface::class);
        $collection->addTourEventFilter($tourEventId)
            ->addLanguageFilter($languageCode);
        /** @var TourEventLanguage $tourEventLanguage */
        $tourEventLanguage = $collection->getFirstItem();

        if (!$tourEventLanguage->getProductId() || !$tourEventLanguage->getTourEventId()) {
            throw new NoSuchEntityException(__('Unable to find tourEventLanguage'));
        }

        return $tourEventLanguage;
    }
}
