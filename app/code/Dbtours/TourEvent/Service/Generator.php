<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Service;

use Dbtours\Base\Logger\Logger;
use Dbtours\TourEvent\Api\Config\Db\TourEvent\GenerationInterface;

/**
 * Class Generator
 */
class Generator
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
     * Generator constructor.
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
     * @return $this
     */
    public function execute()
    {
        $this->logger->info('Running Tour Event Service Generator');
        if (!$this->generationConfig->isEnabled()) {
            $this->logger->warning('TourEvent Generation is Disabled');
            return $this;
        }
        $daysInAdvance = $this->generationConfig->getDaysInAdvance();
        return $this;
    }
}
