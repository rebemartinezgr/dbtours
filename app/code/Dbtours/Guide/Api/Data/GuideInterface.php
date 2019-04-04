<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

/**
 * Interface GuideInterface
 */
interface GuideInterface extends CustomAttributesDataInterface
{
    const TABLE     = 'db_guide';
    const ID        = 'entity_id';
    const FIRSTNAME = 'firstname';
    const LASTNAME  = 'lastname';
    const TELEPHONE = 'telephone';
    const EMAIL     = 'email';
    const PRIORITY  = 'priority';
    const CODE      = 'code';
    const LANGUAGES = 'languages';

    const LANGUAGE_TABLE         = 'db_guide_language';
    const LANGUAGE_GUIDE_ID      = 'guide_id';
    const LANGUAGE_LANGUAGE_CODE = 'language_code';

    /**
     * Retrieve Id
     *
     * @return int
     */
    public function getId();

    /**
     * Set Id
     *
     * @param  $entityId
     * @return $this
     */
    public function setId($entityId);

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
     * Retrieve FirstName
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Set FirstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * Retrieve LastName
     *
     * @return string
     */
    public function getLastName();

    /**
     * Set Tour
     *
     * @param string $lastName
     */
    public function setLastName($lastName);

    /**
     * Retrieve Telephone
     *
     * @return string
     */
    public function getTelephone();

    /**
     * Set Telephone
     *
     * @param string $telephone
     */
    public function setTelephone($telephone);

    /**
     * Retrieve Email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set Email
     *
     * @param string $email
     */
    public function setEmail($email);

    /**
     * Retrieve Priority
     *
     * @return int
     */
    public function getPriority();

    /**
     * Set Priority
     *
     * @param int $priority
     */
    public function setPriority($priority);


    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Dbtours\Guide\Api\Data\GuideExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Dbtours\Guide\Api\Data\GuideExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Dbtours\Guide\Api\Data\GuideExtensionInterface $extensionAttributes
    );
}
