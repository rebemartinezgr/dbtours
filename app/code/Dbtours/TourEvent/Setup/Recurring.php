<?php
declare(strict_types=1);

/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Setup;

use Dbtours\TourEvent\Api\Config\Db\TourEvent\GenerationInterface;
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
     * @var GenerationInterface
     */
    private $generationInterface;

    public function __construct(
        GenerationInterface $generationInterface
    ) {
        $this->generationInterface = $generationInterface;
    }

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
        $minHours        = $this->getMinHours();
        $tourEventStart  = 'te.start_time - INTERVAL COALESCE(blocked_before,0) MINUTE';
        $tourEventFinish = 'te.finish_time + INTERVAL COALESCE(blocked_after,0) MINUTE';
        $sql1            = 'DROP VIEW IF EXISTS ' . self::TOUR_EVENT_LANGUAGE_VIEW_NAME;
        $sql2            = 'CREATE VIEW ' . self::TOUR_EVENT_LANGUAGE_VIEW_NAME . ' AS 
            SELECT
            te.product_id, 
            te.entity_id tour_event_id,
            te.start_time, 
            te.finish_time, 
            te.blocked_before,
            te.blocked_after,
            gl.language_code AS language_code,
            GROUP_CONCAT(IF (ce.entity_id, null, gl.guide_id)) AS available_guides,
            MAX(IF (ce.entity_id, 0, 1)) AS available
            FROM db_tour_event te
            INNER JOIN db_guide_language gl
            LEFT JOIN db_calendar_event ce on gl.guide_id = ce.guide_id and 
            ((' . $tourEventStart . ' <= ce.start_time AND ce.start_time < ' . $tourEventFinish . ') 
            OR (' . $tourEventStart . ' < ce.finish_time AND ce.finish_time <= ' . $tourEventFinish . ')
            OR (ce.start_time <= ' . $tourEventStart . ' AND ' . $tourEventFinish . ' <= ce.finish_time))
            WHERE ' . $tourEventStart . ' >= NOW() + INTERVAL ' . $minHours . ' HOUR
            GROUP BY tour_event_id, language_code';

        $setup->getConnection()->query($sql1);
        $setup->getConnection()->query($sql2);
    }

    /**
     * @return int
     */
    private function getMinHours()
    {
        return $this->generationInterface->getMinHours();
    }
}
