<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Booking\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;
use Dbtours\Guide\Api\Data\GuideInterface;
use Dbtours\Guide\Model\ResourceModel\Guide\CollectionFactory;
use Magento\Framework\Registry;

/**
 * Class Guides
 */
class Guides implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $guideCollectionFactory;

    /**
     * @var array
     */
    private $options;


    /**
     * Guides constructor.
     * @param CollectionFactory $guideCollectionFactory
     * @param Registry $registry
     */
    public function __construct(
        CollectionFactory $guideCollectionFactory
    ) {
        $this->guideCollectionFactory   = $guideCollectionFactory;
    }

    /**
     * @return array|null
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $collection    = $this->guideCollectionFactory->create();
            $this->options = [['label' => 'Select a guide', 'value' => '']];
            /** @var GuideInterface $guide */
            foreach ($collection as $guide) {
                $this->options[] = [
                    'label' => $guide->getFirstName() ." ". $guide->getLastName(),
                    'value' => $guide->getId()
                ];
            }
        }
        return $this->options;
    }
}
