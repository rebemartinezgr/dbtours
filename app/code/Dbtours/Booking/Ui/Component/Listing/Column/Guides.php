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
use Dbtours\Booking\Controller\Adminhtml\Booking as ControllerBooking;
use Magento\Framework\Registry;
use Dbtours\Booking\Service\BookingManager;

/**
 * Class Guides
 */
class Guides implements OptionSourceInterface
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var CollectionFactory
     */
    private $guideCollectionFactory;

    /**
     * @var array
     */
    private $options;

    /**
     * @var BookingManager
     */
    private $bookingManager;

    /**
     * Guides constructor.
     * @param CollectionFactory $guideCollectionFactory
     * @param Registry $registry
     * @param BookingManager $bookingManager
     */
    public function __construct(
        CollectionFactory $guideCollectionFactory,
        Registry $registry,
        BookingManager $bookingManager
    ) {
        $this->guideCollectionFactory   = $guideCollectionFactory;
        $this->registry                 = $registry;
        $this->bookingManager           = $bookingManager;
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
