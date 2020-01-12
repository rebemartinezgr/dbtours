<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Calendar\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

/**
 * Interface CalendarEventTypeInterface
 */
interface CalendarEventTypeInterface extends CustomAttributesDataInterface
{
    const TABLE = 'db_calendar_event_type';
    const ID    = 'entity_id';
    const CODE  = 'code';
    const COLOR = 'hexa_color';

    /**
     * Retrieve Code
     *
     * @return string
     */
    public function getCode();

    /**
     * Set Code
     *
     * @param string $code
     */
    public function setCode($code);

    /**
     * Retrieve Color
     *
     * @return string
     */
    public function getColor();

    /**
     * Set Color
     *
     * @param string $color
     */
    public function setColor($color);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Dbtours\Calendar\Api\Data\CalendarEventTypeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Dbtours\Calendar\Api\Data\CalendarEventTypeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Dbtours\Calendar\Api\Data\CalendarEventTypeExtensionInterface
                                           $extensionAttributes);
}
