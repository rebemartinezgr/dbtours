<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Model;

use Dbtours\Guide\Api\Data\GuideInterface;
use Dbtours\Guide\Api\Data\GuideSearchResultsInterface;
use Dbtours\Guide\Api\Data\GuideSearchResultsInterfaceFactory;
use Dbtours\Guide\Api\GuideRepositoryInterface;
use Dbtours\Guide\Model\GuideFactory;
use Dbtours\Guide\Model\ResourceModel\Guide\Collection;
use Dbtours\Guide\Model\ResourceModel\Guide\CollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;
use Exception;

/**
 * Class GuideRepository
 */
class GuideRepository implements GuideRepositoryInterface
{
    /**
     * @var GuideFactory $guideFactory
     */
    private $guideFactory;

    /**
     * @var CollectionFactory $guideCollectionFactory
     */
    private $guideCollectionFactory;

    /**
     * @var GuideSearchResultsInterfaceFactory $guideSearchResultsInterfaceFactory
     */
    private $guideSearchResultsInterfaceFactory;

    /**
     * @var CollectionProcessorInterface $collectionProcessor
     */
    private $collectionProcessor;

    /**
     * @var JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    private $extensionAttributesJoinProcessor;

    /**
     * GuideRepository constructor.
     *
     * @param GuideFactory $guideFactory
     * @param CollectionFactory $guideCollectionFactory
     * @param GuideSearchResultsInterfaceFactory $guideSearchResultsInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        GuideFactory $guideFactory,
        CollectionFactory $guideCollectionFactory,
        GuideSearchResultsInterfaceFactory $guideSearchResultsInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        $this->guideFactory = $guideFactory;
        $this->guideCollectionFactory = $guideCollectionFactory;
        $this->guideSearchResultsInterfaceFactory = $guideSearchResultsInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * @inheritdoc
     */
    public function save(GuideInterface $guide)
    {
        $guide->getResource()->save($guide);

        return $guide;
    }


    /**
     * @inheritdoc
     */
    public function getById($guideId)
    {
        return $this->get($guideId);
    }

    /**
     * @inheritdoc
     */
    public function get($value, $attributeCode = null)
    {
        /** @var Guide $guide */
        $guide = $this->guideFactory->create()->load($value, $attributeCode);

        if (!$guide->getId()) {
            throw new NoSuchEntityException(__('Unable to find guide'));
        }

        return $guide;
    }

    /**
     * @inheritdoc
     */
    public function delete(GuideInterface $guide)
    {

        $guideId = $guide->getId();
        try {
            $guide->getResource()->delete($guide);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                __('Unable to remove guide %1', $guideId)
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($guideId)
    {
        $guide = $this->getById($guideId);

        return $this->delete($guide);
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->guideCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection, GuideInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var GuideSearchResultsInterface $searchResults */
        $searchResults = $this->guideSearchResultsInterfaceFactory->create();
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
        $collection = $this->guideCollectionFactory->create();
        $collection->deleteAll();
    }
}
