<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    const GUIDE_TABLE_NAME          = 'db_guide';
    const LANGUAGE_TABLE_NAME       = 'db_language';
    const GUIDE_LANGUAGE_TABLE_NAME = 'db_guide_language';

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $this->createGuideTable($setup);
        $this->createGuideLanguageTable($setup);
        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    private function createGuideTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable(self::GUIDE_TABLE_NAME)
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Entity ID'
        )->addColumn(
            'email',
            Table::TYPE_TEXT,
            255,
            [],
            'Email Guide'
        )->addColumn(
            'code',
            Table::TYPE_TEXT,
            255,
            [],
            'Code'
        )->addColumn(
            'firstname',
            Table::TYPE_TEXT,
            255,
            [],
            'Firstname Guide'
        )->addColumn(
            'lastname',
            Table::TYPE_TEXT,
            255,
            [],
            'Lastname Guide'
        )->addColumn(
            'telephone',
            Table::TYPE_TEXT,
            255,
            [],
            'Telephone Guide'
        )->addColumn(
            'priority',
            Table::TYPE_INTEGER,
            8,
            [],
            'Priority'
        )->addIndex(
            $setup->getIdxName(
                self::GUIDE_TABLE_NAME,
                ['code'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['code'],
            ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
        )->setComment(
            'Dbtours Guide'
        );

        $setup->getConnection()->createTable($table);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    private function createGuideLanguageTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable(self::GUIDE_LANGUAGE_TABLE_NAME)
        )->addColumn(
            'guide_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'unsigned' => true],
            'Guide ID'
        )->addColumn(
            'language_code',
            Table::TYPE_TEXT,
            100,
            ['nullable' => false],
            'Code Language'
        )->setComment(
            'Dbtours Guide-Language'
        )->addIndex(
            $setup->getIdxName(
                self::GUIDE_LANGUAGE_TABLE_NAME,
                ['guide_id', 'language_code'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['guide_id', 'language_code'],
            ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addForeignKey(
            $setup->getFkName(
                self::GUIDE_LANGUAGE_TABLE_NAME,
                'guide_id',
                self::GUIDE_TABLE_NAME,
                'entity_id'
            ),
            'guide_id',
            $setup->getTable(self::GUIDE_TABLE_NAME),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $setup->getConnection()->createTable($table);
    }
}
