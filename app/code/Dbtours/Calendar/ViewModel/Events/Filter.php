<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\ViewModel\Events;

use Dbtours\Booking\Ui\Component\Listing\Column\Guides;
use Dbtours\Calendar\Ui\Component\Listing\Column\EventType;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Html\SelectFactory;

/**
 * Class Filter
 */
class Filter implements ArgumentInterface
{
    /**
     * @var SelectFactory
     */
    private $selectFactory;

    /**
     * @var EventType
     */
    private $eventTypeSource;

    /**
     * @var Guides
     */
    private $guidesSource;

    /**
     * Filter constructor.
     * @param SelectFactory $selectFactory
     * @param EventType $eventTypeSource
     * @param Guides $guidesSource
     */
    public function __construct(
        SelectFactory $selectFactory,
        EventType $eventTypeSource,
        Guides $guidesSource
    ) {
        $this->selectFactory   = $selectFactory;
        $this->eventTypeSource = $eventTypeSource;
        $this->guidesSource    = $guidesSource;
    }

    /**
     * @return string
     */
    public function getGuideSelect()
    {
        $options = $this->guidesSource->toOptionArray();
        return $this->selectFactory->create()
            ->setOptions($options)
            ->setId('guide-filter')
            ->setClass('admin__control-select')
            ->getHtml();
    }

    /**
     * @return string
     */
    public function getEventTpeSelect()
    {
        $options = $this->eventTypeSource->toOptionArray();
        return $this->selectFactory->create()
            ->setOptions($options)
            ->setId('type-filter')
            ->setClass('admin__control-select')
            ->getHtml();
    }
}
