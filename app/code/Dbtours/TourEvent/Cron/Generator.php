<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Cron;

use Dbtours\Base\Logger\Logger;
use Dbtours\TourEvent\Service\Generator as GeneratorService;

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
     * @var GeneratorService
     */
    private $generatorService;

    /**
     * Generator constructor.
     * @param Logger $logger
     * @param GeneratorService $generatorService
     */
    public function __construct(
        Logger $logger,
        GeneratorService $generatorService
    ) {
        $this->logger           = $logger;
        $this->generatorService = $generatorService;
    }

    /**
     * Method executed when cron runs in server
     */
    public function execute()
    {
        $this->logger->info('Running Dbtours\TourEvent\Cron\Generator::execute');
        try {
            $this->generatorService->execute();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $this->logger->info('Dbtours\TourEvent\Cron\Generator::execute finish without Exceptions');
        return $this;
    }
}
