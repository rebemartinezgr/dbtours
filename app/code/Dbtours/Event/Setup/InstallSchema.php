<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Event\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    const EVENT_TOUR_TABLE_NAME = 'db_event_tour';

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        $setup->startSetup();
        $this->createEventTourTable($setup);
        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    private function createEventTourTable(SchemaSetupInterface $setup)
    {
        $tablePromoImages = $setup->getConnection()->newTable(
            $setup->getTable(self::EVENT_TOUR_TABLE_NAME)
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Entity ID'
        )->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Related Product ID'
        )->addColumn(
            'start_at',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => false],
            'Start Event Datetime'
        )->addColumn(
            'finish_at',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => false],
            'Finish Event Datetime'
        )->addIndex(
            $setup->getIdxName(self::EVENT_TOUR_TABLE_NAME, ['product_id']),
            ['product_id']
        )->addForeignKey(
            $setup->getFkName(self::EVENT_TOUR_TABLE_NAME,
                              'product_id',
                              'catalog_product_entity',
                              'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Dbtours Event Tour'
        );

        $setup->getConnection()->createTable($tablePromoImages);
    }
}
