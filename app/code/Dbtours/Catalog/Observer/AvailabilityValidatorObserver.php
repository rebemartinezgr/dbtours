<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */
namespace Dbtours\Catalog\Observer;

use Dbtours\Catalog\Model\Quote\Item\AvailabilityValidator;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item;

/**
 * Class AvailabilityValidatorObserver
 */
class AvailabilityValidatorObserver implements ObserverInterface
{
    /**
     * @var AvailabilityValidator $availabilityValidator
     */
    protected $availabilityValidator;

    /**
     * @param AvailabilityValidator $availabilityValidator
     */
    public function __construct(
        AvailabilityValidator $availabilityValidator
    ) {
        $this->availabilityValidator = $availabilityValidator;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /* @var $quoteItem Item */
        $quoteItem = $observer->getEvent()->getQuoteItem();
        $this->availabilityValidator->validate($quoteItem);
    }
}
