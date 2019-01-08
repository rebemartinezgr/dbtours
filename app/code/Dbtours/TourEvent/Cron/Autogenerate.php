<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Cron;

use Dbtours\Base\Logger\Logger;
use Dbtours\TourEvent\Api\Config\Db\TourEvent\GenerationInterface;

/**
 * Class Autogenerate
 */
class Autogenerate
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var GenerationInterface
     */
    private $generationConfig;

    /**
     * Autogenerate constructor.
     * @param Logger $logger
     * @param GenerationInterface $generationConfig
     */
    public function __construct(
        Logger $logger,
        GenerationInterface $generationConfig
    ) {
        $this->logger           = $logger;
        $this->generationConfig =  $generationConfig;
    }

    /**
     * Method executed when cron runs in server
     */
    public function execute()
    {
        $this->logger->error('Running Cron from Test class');
        if (!$this->generationConfig->isEnabled()) {
            $this->logger->error('TourEvent Generation is Disabled');
            return $this;
        }
        $daysInAdvance = $this->generationConfig->getDaysInAdvance();
        return $this;
    }
}
