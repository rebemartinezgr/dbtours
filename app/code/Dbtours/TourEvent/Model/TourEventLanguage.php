<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model;

use Dbtours\TourEvent\Api\Data\TourEventLanguageExtensionInterface;
use Dbtours\TourEvent\Api\Data\TourEventLanguageInterface;
use Dbtours\TourEvent\Model\ResourceModel\TourEventLanguage as ResourceModelTourEventLanguage;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class TourEventLanguage
 */
class TourEventLanguage extends AbstractExtensibleModel implements TourEventLanguageInterface
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModelTourEventLanguage::class);
    }

    /**
     * @inheritdoc
     */
    public function getProductId()
    {
        return $this->_getData(self::PRODUCT_ID);
    }

    /**
     * @inheritdoc
     */
    public function setProductId($productId)
    {
        $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @inheritdoc
     */
    public function getTourEventId()
    {
        return $this->_getData(self::TOUR_EVENT_ID);
    }

    /**
     * @inheritdoc
     */
    public function setTourEventId($tourEventId)
    {
        $this->setData(self::TOUR_EVENT_ID, $tourEventId);
    }

    /**
     * @inheritdoc
     */
    public function getStartTime()
    {
        return $this->_getData(self::START_TIME);
    }

    /**
     * @inheritdoc
     */
    public function setStartTime($startTime)
    {
        $this->setData(self::START_TIME, $startTime);
    }

    /**
     * @inheritdoc
     */
    public function getFinishTime()
    {
        return $this->_getData(self::FINISH_TIME);
    }

    /**
     * @inheritdoc
     */
    public function setFinishTime($finishTime)
    {
        $this->setData(self::FINISH_TIME, $finishTime);
    }

    /**
     * @inheritdoc
     */
    public function getBlockedBefore()
    {
        return $this->_getData(self::BLOCKED_BEFORE);
    }

    /**
     * @inheritdoc
     */
    public function setBlockedBefore($blockedBefore)
    {
        $this->setData(self::BLOCKED_BEFORE, $blockedBefore);
    }

    /**
     * @inheritdoc
     */
    public function getBlockedAfter()
    {
        return $this->_getData(self::BLOCKED_AFTER);
    }

    /**
     * @inheritdoc
     */
    public function setBlockedAfter($blockedAfter)
    {
        $this->setData(self::BLOCKED_AFTER, $blockedAfter);
    }

    /**
     * @inheritdoc
     */
    public function getLanguageId()
    {
        return $this->_getData(self::LANGUAGE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setLanguageId($languageId)
    {
        $this->setData(self::LANGUAGE_ID, $languageId);
    }

    /**
     * @inheritdoc
     */
    public function setLanguageCode($languageCode)
    {
        $this->setData(self::LANGUAGE_CODE, $languageCode);
    }

    /**
     * @inheritdoc
     */
    public function getLanguageCode()
    {
        return $this->_getData(self::LANGUAGE_CODE);
    }

    /**
     * @inheritdoc
     */
    public function isAvailable()
    {
        return (bool)$this->_getData(self::AVAILABLE);
    }

    /**
     * @inheritdoc
     */
    public function setAvailable($available)
    {
        $this->setData(self::AVAILABLE, $available);
    }

    /**
     * @inheritdoc
     */
    public function getAvailableGuide()
    {
        $guide = $this->_getData(self::AVAILABLE_GUIDES)
            ? explode(",", $this->_getData(self::AVAILABLE_GUIDES))
            : null;
        if ($guide && is_array($guide)) {
            $guide = $guide[0];
        }
        return $guide;
    }

    /**
     * @inheritdoc
     */
    public function setAvailableGuides($availableGuides)
    {
        $this->setData(self::AVAILABLE_GUIDES, $availableGuides);
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(TourEventLanguageExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
