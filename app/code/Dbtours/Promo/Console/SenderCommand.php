<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);
namespace Dbtours\Promo\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dbtours\Promo\Helper\Mailer;

/**
 * Class SenderCommand
 */
class SenderCommand extends Command
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * SenderCommand constructor.
     * @param Mailer $mailer
     */
    public function __construct(
        Mailer $mailer
    ) {
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('db:promo:send')
            ->setDescription('Send Promo Email')
            ->addArgument('email', 1)
            ->addArgument('name', 1)
            ->addArgument('store', 1)
            ->setAliases(['p:s']);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Running' . __CLASS__ . ":" .__METHOD__);
        $email = $input->getOption('email');
        $name = $input->getOption('name');
        $storeId = $input->getOption('store');
        try {
            $this->mailer->notify($name, $email, $storeId);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
        $output->writeln('Running' . __CLASS__ . ":" .__METHOD__ . 'finish without Exceptions');
    }
}
