<?php
declare(strict_types=1);
/**
 * @author Interactiv4 Team
 * @copyright Copyright © Interactiv4 (https://www.interactiv4.com)
 */

namespace Dbtours\TourEvent\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dbtours\TourEvent\Service\Generator as GeneratorService;

/**
 * Class Generator
 */
class Generator extends Command
{
    /**
     * @var GeneratorService
     */
    private $generatorService;

    /**
     * Generator constructor.
     * @param GeneratorService $generatorService
     */
    public function __construct(
        GeneratorService $generatorService
    ) {
        $this->generatorService = $generatorService;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('db:tourevent:generate')
            ->setDescription('Generate Tour Events')
            ->setAliases(['t:g']);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Running Dbtours\TourEvent\Console\Generator::execute');
        try {
            $this->generatorService->execute();
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
        $output->writeln('Dbtours\TourEvent\Console\Generator::execute finish without Exceptions');
    }
}
