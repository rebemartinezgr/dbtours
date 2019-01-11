<?php
declare(strict_types=1);

/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */
namespace Dbtours\TourEvent\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Catalog recurring setup
 */
class Recurring implements InstallSchemaInterface
{
    const TOUR_EVENT_LANGUAGE_VIEW_NAME = 'db_tour_event_language';

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $this->createTourEventLanguageView($setup);
        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    private function createTourEventLanguageView(SchemaSetupInterface $setup)
    {
        $sql1 = 'DROP VIEW IF EXISTS ' . self::TOUR_EVENT_LANGUAGE_VIEW_NAME;
        $sql2 = 'CREATE VIEW ' . self::TOUR_EVENT_LANGUAGE_VIEW_NAME . ' AS 
            SELECT
            te.product_id, 
            te.entity_id tour_event_id,
            te.start_time, 
            te.finish_time,  
            l.entity_id as language_id, 
            l.code,
            MAX(IF (ce.entity_id, 0, 1)) AS available
            FROM db_tour_event te
            INNER JOIN db_language l
            INNER JOIN db_guide_language gl on l.entity_id = gl.language_id
            LEFT JOIN db_calendar_event ce on gl.guide_id = ce.guide_id and 
            ((te.start_time <= ce.start_time AND ce.start_time < te.finish_time) 
            OR (te.start_time < ce.finish_time AND ce.finish_time <= te.finish_time)
            OR (ce.start_time <= te.start_time AND te.finish_time <= ce.finish_time))
            GROUP BY tour_event_id, language_id';

        $setup->getConnection()->query($sql1);
        $setup->getConnection()->query($sql2);
    }
}
