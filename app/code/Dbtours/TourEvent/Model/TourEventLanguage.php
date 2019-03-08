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
    public function getTourEventId()
    {
        return $this->_getData(self::TOUR_EVENT_ID);
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
    public function getFinishTime()
    {
        return $this->_getData(self::FINISH_TIME);
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
    public function getBlockedAfter()
    {
        return $this->_getData(self::BLOCKED_AFTER);
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
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }
}
