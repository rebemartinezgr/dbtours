<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Catalog\Model\Quote\Item;

use Dbtours\TourEvent\Helper\Validator;
use Magento\Framework\Phrase;
use Magento\Quote\Model\Quote\Item;

/**
 * Class AvailabilityValidator
 */
class AvailabilityValidator
{
    private $tourEventLanguageValidator;

    /**
     * AvailabilityValidator constructor.
     * @param Validator $tourEventLanguageValidator
     */
    public function __construct(
        Validator $tourEventLanguageValidator
    ) {
        $this->tourEventLanguageValidator = $tourEventLanguageValidator;
    }

    /**
     * Add error information to Quote Item
     * @param Item $quoteItem
     * @return void
     */
    private function addErrorInfoToQuote($quoteItem)
    {
        $quoteItem->addErrorInfo(
            'catalogavailability',
            null,
            new Phrase('Selected options is not longer available. Please try another option')
        );

        $quoteItem->getQuote()->addErrorInfo(
            'error',
            'catalogavailability',
            null,
            new Phrase('Selected options is not longer available. Please try another option')
        );
    }

    /**
     * @param Item $quoteItem
     */
    public function validate(Item $quoteItem)
    {
        if (!$this->tourEventLanguageValidator->shouldValidate($quoteItem)) {
            return;
        }
        foreach ($quoteItem->getOptions() as $option) {
            $itemOptions = json_decode($option['value'], true);
            if (isset($itemOptions['tour_event_id']) && isset($itemOptions['language_code'])) {
                $valid = $this->tourEventLanguageValidator->validateAvailability(
                    $itemOptions['tour_event_id'],
                    $itemOptions['language_code']
                );
                if (!$valid) {
                    $this->addErrorInfoToQuote($quoteItem);
                }
            }
        }
    }
}
