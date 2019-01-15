<?php
declare(strict_types=1);

/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */
namespace Dbtours\TourEvent\Service;

use Dbtours\Base\Logger\Logger;
use Dbtours\TourEvent\Api\Config\Db\TourEvent\GenerationInterface;
use Dbtours\TourEvent\Api\TourEventRepositoryInterface;
use Dbtours\TourEvent\Api\Data\TourEventInterface;
use Dbtours\TourEvent\Api\Data\TourEventInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Framework\Stdlib\Datetime;

/**
 * Class Generator
 */
class Generator
{
    const WEEK_ATTRIBUTES_MAP = [
        'Mon' => 'db_monday_times',
        'Tue' => 'db_tuesday_times',
        'Wed' => 'db_wednesday_times',
        'Thu' => 'db_thursday_times',
        'Fri' => 'db_friday_times',
        'Sat' => 'db_saturday_times',
        'Sun' => 'db_sunday_times'
    ];

    const TIME_ATTRIBUTES = [
        'db_duration_min',
        'db_blocked_before',
        'db_blocked_after'
    ];

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var GenerationInterface
     */
    private $generationConfig;

    /**
     * @var TourEventRepositoryInterface
     */
    private $tourEventRepository;

    /**
     * @var ProductCollection
     */
    private $productCollection;

    /**
     * @var TourEventInterfaceFactory
     */
    private $tourEventFactory;

    /**
     * Generator constructor.
     * @param Logger $logger
     * @param GenerationInterface $generationConfig
     * @param TourEventRepositoryInterface $tourEventRepository
     * @param ProductCollection $productCollection
     * @param TourEventInterfaceFactory $tourEventFactory
     */
    public function __construct(
        Logger $logger,
        GenerationInterface $generationConfig,
        TourEventRepositoryInterface $tourEventRepository,
        ProductCollection $productCollection,
        TourEventInterfaceFactory $tourEventFactory
    ) {
        $this->logger               = $logger;
        $this->generationConfig     =  $generationConfig;
        $this->tourEventRepository  = $tourEventRepository;
        $this->productCollection    = $productCollection;
        $this->tourEventFactory    = $tourEventFactory;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $this->logger->info('Running Tour Event Service Generator');
        if (!$this->generationConfig->isEnabled()) {
            $this->logger->warning('TourEvent Generation is Disabled');
            return $this;
        }

        $this->cleanTourEvents();
        $this->generateTourEvents();

        return $this;
    }

    /**
     * Clear existing unbooked TourEvents
     */
    private function cleanTourEvents()
    {
        try {
            $this->tourEventRepository->deleteAll();
        } catch (\Exception $e) {
            $this->logger->error('Generator::cleanTourEvents ' . $e->getMessage());
        }
    }

    /**
     * Generate new TourEvents for each product
     */
    private function generateTourEvents()
    {
        $intervalDates = $this->getIntervalDatesByWeekDay();

        /** @var ProductInterface $product */
        foreach ($this->getProductCollection() as $product) {
            if (!$product->getDbDurationMin()) {
                continue;
            }
            $tourEventsDates = $this->getTourEventsDates($product, $intervalDates);
            if (!empty($tourEventsDates)) {
                $tourEventsData = $this->fillTourEventsData($product, $tourEventsDates);
                $this->saveTourEvents($tourEventsData);
            }
        }
    }

    /**
     * @return Collection
     */
    private function getProductCollection()
    {
        $productCollection = $this->productCollection->create();
        $selectAttributes = array_merge(self::WEEK_ATTRIBUTES_MAP, self::TIME_ATTRIBUTES);
        $productCollection->addAttributeToSelect($selectAttributes);

        return $productCollection;
    }

    /**
     * @param ProductInterface $product
     * @param array $intervalDates
     * @return array
     */
    private function getTourEventsDates($product, $intervalDates)
    {
        $result = [];
        foreach (self::WEEK_ATTRIBUTES_MAP as $k => $weekAttributeCode) {
            $startTimes = $product->getData($weekAttributeCode);
            if (!$startTimes) {
                continue;
            }
            $duration       = $product->getDbDurationMin();
            $partialResult  = $this->getProductDateTimes($intervalDates[$k], $startTimes, $duration);
            $result         = array_merge($result, $partialResult);
        }

        return $result;
    }

    /**
     * Fill tour event data with product info
     *
     * @param $tourEventsDates
     * @param $product
     * @return array
     */
    private function fillTourEventsData($product, $tourEventsDates)
    {
        return array_map(function ($element) use ($product) {
            /** @var \Magento\UrlRewrite\Service\V1\Data\UrlRewrite $url */
            $element[TourEventInterface::PRODUCT_ID]        = $product->getId();
            $element[TourEventInterface::BLOCKED_BEFORE]    = $product->getDbBlockedBefore();
            $element[TourEventInterface::BLOCKED_AFTER]     = $product->getDbBlockedAfter();

            return $element;
        }, $tourEventsDates);
    }

    /**
     * Save Tour Events
     *
     * @param $tourEventsData
     */
    private function saveTourEvents($tourEventsData)
    {
        foreach ($tourEventsData as $tourEventData) {
            $tourEvent = $this->tourEventFactory->create();
            $tourEvent->setData($tourEventData);
            $this->tourEventRepository->save($tourEvent);
        }
    }

    /**
     * Get start and finish date times values for given dates
     *
     * @param $dates
     * @param $startTimes
     * @param $duration
     * @return array
     */
    private function getProductDateTimes($dates, $startTimes, $duration)
    {
        $result = [];
        $startTimes = explode(',', $startTimes);
        /** @var \Zend_Date $date */
        foreach ($dates as $date) {
            foreach ($startTimes as $startTime) {
                $startTime = trim($startTime);
                $result[] = [
                    TourEventInterface::START   => $this->getStartDatetime($date, $startTime),
                    TourEventInterface::FINISH  => $this->getFinishDatetime($date, $duration)
                ];
            }
        }

        return $result;
    }

    /**
     * @param $date
     * @param $startTime
     * @return string
     */
    private function getStartDatetime($date, $startTime)
    {
        $from = $date
            ->setTime($startTime, 'HH:mm')
            ->toString(Datetime::DATETIME_INTERNAL_FORMAT);

        return $from;
    }

    /**
     * @param $date
     * @param $duration
     * @return string
     */
    private function getFinishDatetime($date, $duration)
    {
        $to = $date
            ->addMinute($duration)
            ->toString(Datetime::DATETIME_INTERNAL_FORMAT);

        return $to;
    }

    /**
     * @return array
     * @throws \Zend_Date_Exception
     */
    private function getIntervalDatesByWeekDay()
    {
        $daysInAdvance  = $this->generationConfig->getDaysInAdvance();
        $result         = [];
        $from           = new \Zend_Date();
        $to             = new \Zend_Date();
        $from->setTime(0);
        $to->addDay($daysInAdvance);
        $to = $to->setTime(0);

        while ($from->getTimestamp() <= $to->getTimestamp()) {
            $key            = $from->toString(\Zend_Date::WEEKDAY_NAME);
            $value          = new \Zend_Date($from->getDate());
            $result[$key][] = $value;
            $from->addDay(1);
        }

        return $result;
    }
}
