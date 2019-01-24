<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Dbtours\Guide\Setup\InstallSchema as GuideInstallSchema;

/**
 * Class InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    const CALENDAR_EVENT_TABLE_NAME         = 'db_calendar_event';
    const CALENDAR_EVENT_TYPE_TABLE_NAME    = 'db_calendar_event_type';

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $this->createCalendarEventTypeTable($setup);
        $this->createCalendarEventTable($setup);
        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    private function createCalendarEventTypeTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable(self::CALENDAR_EVENT_TYPE_TABLE_NAME)
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Entity ID'
        )->addColumn(
            'code',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Calendar Event Type Code'
        );
        $setup->getConnection()->createTable($table);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    private function createCalendarEventTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable(self::CALENDAR_EVENT_TABLE_NAME)
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Entity ID'
        )->addColumn(
            'guide_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'unsigned' => true],
            'Guide ID'
        )->addColumn(
            'start_time',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => false],
            'Start CalendarEvent Datetime'
        )->addColumn(
            'finish_time',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => false],
            'Finish CalendarEvent Datetime'
        )->addColumn(
            'type_id',
            Table::TYPE_INTEGER,
            10,
            ['nullable' => true,  'unsigned' => true, 'length' => 10],
            'Calendar Event type'
        )->addColumn(
            'order_item_id',
            Table::TYPE_INTEGER,
            10,
            ['nullable' => true,  'unsigned' => true, 'length' => 10],
            'Calendar Order Id'
        )->setComment(
            'Dbtours Calendar Event'
        )->addForeignKey(
            $setup->getFkName(
                self::CALENDAR_EVENT_TABLE_NAME,
                'guide_id',
                GuideInstallSchema::GUIDE_TABLE_NAME,
                'entity_id'
            ),
            'guide_id',
            $setup->getTable(GuideInstallSchema::GUIDE_TABLE_NAME),
            'entity_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $setup->getFkName(
                self::CALENDAR_EVENT_TABLE_NAME,
                'type_id',
                self::CALENDAR_EVENT_TYPE_TABLE_NAME,
                'entity_id'
            ),
            'type_id',
            $setup->getTable(self::CALENDAR_EVENT_TYPE_TABLE_NAME),
            'entity_id',
            Table::ACTION_SET_NULL
        )->addForeignKey(
            $setup->getFkName(
                self::CALENDAR_EVENT_TABLE_NAME,
                'order_item_id',
                'sales_order_item',
                'item_id'
            ),
            'order_item_id',
            $setup->getTable('sales_order_item'),
            'item_id',
            Table::ACTION_SET_NULL
        );

        $setup->getConnection()->createTable($table);
    }
}
