<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Event\Model\Config\Db;

use Dbtours\Event\Api\Config\Db\EventInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Event
 */
class Event implements EventInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Event constructor.
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
