<?php
declare(strict_types=1);
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\TourEvent\Api\Config\Db\TourEvent;

use Dbtours\TourEvent\Api\Config\Db\TourEventInterface;

/**
 * Interface GenerationInterface
 */
interface GenerationInterface extends TourEventInterface
{
    const XML_GROUP_PATH = 'generation';

    /**
     * Check enabled flag of config
     *
     * @param string|integer|null $storeId
     *
     * @return bool
     */
    public function isEnabled($storeId = null): bool;

    /**
     * @param int $storeId
     * @return int
     */
    public function getDaysInAdvance($storeId = null): int;

    /**
     * @param int $storeId
     * @return int
     */
    public function getMinHours($storeId = null): int;
}
