<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Api\Data\TourEventSearchResultsInterface;
use Dbtours\TourEvent\Api\Data\TourEventLanguageSearchResultsInterfaceFactory;
use Dbtours\TourEvent\Api\TourEventLanguageRepositoryInterface;
use Dbtours\TourEvent\Model\TourEventLanguageFactory;
use Dbtours\TourEvent\Model\ResourceModel\TourEventLanguage\Collection;
use Dbtours\TourEvent\Model\ResourceModel\TourEventLanguage\CollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;
use Exception;

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
        $this->tourEventLanguageFactory = $tourEventLanguageFactory;
        $this->tourEventLanguageCollectionFactory = $tourEventLanguageCollectionFactory;
        $this->tourEventLanguageSearchResultsInterfaceFactory = $tourEventLanguageSearchResultsInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * @inheritdoc
     */
    public function save(TourEventLanguageInterface $tourEventLanguage)
    {
        $tourEventLanguage->getResource()->save($tourEventLanguage);

        return $tourEventLanguage;
    }


    /**
     * @inheritdoc
     */
    public function getById($tourEventLanguageId)
    {
        return $this->get($tourEventLanguageId);
    }

    /**
     * @inheritdoc
     */
    public function get($value, $attributeCode = null)
    {
        /** @var TourEvent $tourEventLanguage */
        $tourEventLanguage = $this->tourEventLanguageFactory->create()->load($value, $attributeCode);

        if (!$tourEventLanguage->getId()) {
            throw new NoSuchEntityException(__('Unable to find tourEventLanguage'));
        }

        return $tourEventLanguage;
    }

    /**
     * @inheritdoc
     */
    public function delete(TourEventLanguageInterface $tourEventLanguage)
    {

        $tourEventLanguageId = $tourEventLanguage->getId();
        try {
            $tourEventLanguage->getResource()->delete($tourEventLanguage);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                __('Unable to remove tourEventLanguage %1', $tourEventLanguageId)
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($tourEventLanguageId)
    {
        $tourEventLanguage = $this->getById($tourEventLanguageId);

        return $this->delete($tourEventLanguage);
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
        $collection
            ->addProductIdFilter($productId)
            ->addDateTimesLanguageAvailability();
        /** @var TourEventSearchResultsInterface $searchResults */
        $searchResults = $this->tourEventLanguageSearchResultsInterfaceFactory->create();
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}