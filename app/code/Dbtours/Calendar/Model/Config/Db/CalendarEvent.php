<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Model\Config\Db;

use Dbtours\Calendar\Api\Config\Db\CalendarEventInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class CalendarEvent
 */
class CalendarEvent implements CalendarEventInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * CalendarEvent constructor.
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
