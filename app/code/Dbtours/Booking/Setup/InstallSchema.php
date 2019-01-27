<?php

/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Booking\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    const SALES_BOOKING_TABLE_NAME = 'db_booking';

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $this->createSalesBookingTable($setup);
        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    private function createSalesBookingTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable(self::SALES_BOOKING_TABLE_NAME)
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Entity ID'
        )->addColumn(
            'order_item_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true, 'unsigned' => true, 'length' => 10],
            'Order Item Id'
        )->addColumn(
            'pax',
            Table::TYPE_INTEGER,
            10,
            ['nullable' => true, 'unsigned' => true],
            'Pax'
        )->addColumn(
            'start_time',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Start TourEvent Datetime'
        )->addColumn(
            'finish_time',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Finish TourEvent Datetime'
        )->addColumn(
            'blocked_before',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Min Blocked Before Event'
        )->addColumn(
            'blocked_after',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Min Blocked After Event'
        )->addColumn(
            'guide_id',
            Table::TYPE_INTEGER,
            10,
            ['nullable' => true, 'unsigned' => true, 'length' => 10],
            'Guide ID'
        )->addColumn(
            'language_code',
            Table::TYPE_INTEGER,
            10,
            ['nullable' => true, 'unsigned' => true, 'length' => 10],
            'Language ID'
        )->addColumn(
            'duration',
            Table::TYPE_TEXT,
            255,
            [],
            'Duration'
        )->addColumn(
            'tips',
            Table::TYPE_TEXT,
            null,
            [],
            'Tips'
        )->addColumn(
            'included',
            Table::TYPE_TEXT,
            null,
            [],
            'Included'
        )->addForeignKey(
            $setup->getFkName(
                self::SALES_BOOKING_TABLE_NAME,
                'order_item_id',
                'sales_order_item',
                'item_id'
            ),
            'order_item_id',
            $setup->getTable('sales_order_item'),
            'item_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $setup->getFkName(
                self::SALES_BOOKING_TABLE_NAME,
                'guide_id',
                'db_guide',
                'entity_id'
            ),
            'guide_id',
            $setup->getTable('db_guide'),
            'entity_id',
            Table::ACTION_SET_NULL
        )->setComment(
            'Dbtours Sales Booking'
        );

        $setup->getConnection()->createTable($table);
    }
}
