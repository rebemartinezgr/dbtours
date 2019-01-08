<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model\Config\Db\TourEvent;

use Dbtours\TourEvent\Api\Config\Db\TourEvent\GenerationInterface;
use Dbtours\TourEvent\Model\Config\Db\TourEvent;

/**
 * Class Generation
 */
class Generation extends TourEvent implements GenerationInterface
{
    /**
     * @return string
     */
    public function getXmlBaseGroupPath()
    {
        return  $this->getXmlBasePath() . '/' . $this->getXmlGroupPath() . '/';
    }

    /**
     * @inheritdoc
     */
    public function getXmlGroupPath()
    {
        return self::XML_GROUP_PATH;
    }

    /**
     * @inheritdoc
     */
    public function isEnabled($storeId = null): bool
    {
        return (bool)$this->scopeConfig->getValue($this->getXmlBaseGroupPath() . 'enabled');
    }
    /**
     * @inheritdoc
     */
    public function getDaysInAdvance($storeId = null): int
    {
        return (int)$this->scopeConfig->getValue($this->getXmlBaseGroupPath() . 'day_in_advance');
    }
}
