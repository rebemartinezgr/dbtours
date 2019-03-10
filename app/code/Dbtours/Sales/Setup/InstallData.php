<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */
declare(strict_types=1);

namespace Dbtours\Sales\Setup;

use Exception;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Model\Order\Status;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\Status as StatusResource;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;

/**
 * Class InstallData
 */
class InstallData implements InstallDataInterface
{
    /**
     * Status Factory
     *
     * @var StatusFactory
     */
    protected $statusFactory;

    /**
     * Status Resource Factory
     *
     * @var StatusResourceFactory
     */
    protected $statusResourceFactory;

    /**
     * InstallData constructor
     *
     * @param StatusFactory $statusFactory
     * @param StatusResourceFactory $statusResourceFactory
     */
    public function __construct(
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory
    )
    {
        $this->statusFactory         = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->addNewStatus();
    }

    /**
     * Create new order status and assign it to the state
     *
     * @return void
     *
     * @throws Exception
     */
    protected function addNewStatus()
    {
        $statuses = [
            [
                'code'  => 'db_new_unassigned',
                'label' => 'Unassigned',
                'state' => 'new',
            ],
            [
                'code'  => 'db_process_unassigned',
                'label' => 'Unassigned',
                'state' => 'processing',
            ],
            [
                'code'  => 'db_new_partial_performed',
                'label' => 'Partial Performed',
                'state' => 'new',
            ],
            [
                'code'  => 'db_process_partial_performed',
                'label' => 'Partial Performed',
                'state' => 'processing',
            ],
            [
                'code'  => 'db_new_performed',
                'label' => 'Performed',
                'state' => 'new',
            ],
            [
                'code'  => 'db_process_performed',
                'label' => 'Performed',
                'state' => 'processing',
            ]
        ];

        foreach ($statuses as $status) {
            $code  = $status['code'];
            $label = $status['label'];
            $state = $status['state'];
            /** @var StatusResource $statusResource */
            $statusResource = $this->statusResourceFactory->create();
            /** @var Status $status */
            $status = $this->statusFactory->create();
            $status->setData(
                [
                    'status' => $code,
                    'label'  => $label,
                ]
            );

            try {
                $statusResource->save($status);
                $status->assignState($state, false, true);
            } catch (AlreadyExistsException $exception) {
            }
        }
    }
}
