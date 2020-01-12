<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Catalog\Model\Source;

use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Products
 */
class Products implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * Products constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->productRepository     = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return array|null
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options  = [['label' => 'All Products', 'value' => '']];
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $list           = $this->productRepository->getList($searchCriteria);
            /** @var TourEventLanguageInterface $tourEventLanguage */
            foreach ($list->getItems() as $product) {
                $this->options[] = [
                    'label' => $product->getName(),
                    'value' => $product->getId()
                ];
            }

        }
        return $this->options;
    }
}
