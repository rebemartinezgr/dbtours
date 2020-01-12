<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Model\Config\Db;

use Dbtours\TourEvent\Api\Config\Db\TourEventInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class TourEvent
 */
class TourEvent implements TourEventInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * TourEvent constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function getXmlBasePath()
    {
        return self::XML_BASE_PATH;
    }
}
