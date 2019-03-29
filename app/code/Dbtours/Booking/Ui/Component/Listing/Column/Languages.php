<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Booking\Ui\Component\Listing\Column;

use Dbtours\TourEvent\Helper\Locale;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Languages
 */
class Languages implements OptionSourceInterface
{
    /**
     * @var
     */
    protected $options;

    /**
     * @var Locale
     */
    private $helper;

    /**
     * Guides constructor.
     * @param Locale $helper
     */
    public function __construct(
        Locale $helper
    ) {
        $this->helper   = $helper;
    }

    /**
     * @return array|null
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [['label' => 'Select a language', 'value' => '']];
            foreach ($this->helper->getLanguagesList() as $code => $language) {
                $this->options[] = [
                    'label' => $language,
                    'value' => $code
                ];
            }
        }
        return $this->options;
    }
}
