<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Sales\Api\Data\Order;

/**
 * Class Status
 */
class Status
{
    const NEW_UNASSIGNED            = 'db_new_unassigned';
    const PROCESS_UNASSIGNED        = 'db_process_unassigned';
    const NEW_PARTIAL_PERFORMED     = 'db_new_partial_performed';
    const PROCESS_PARTIAL_PERFORMED = 'db_process_partial_performed';
    const NEW_PERFORMED             = 'db_new_performed';
    const PROCESS_PERFORMED         = 'db_process_performed';

}
