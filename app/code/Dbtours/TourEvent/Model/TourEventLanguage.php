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
    public function getAvailable()
    {
        return $this->_getData(self::AVAILABLE);
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
