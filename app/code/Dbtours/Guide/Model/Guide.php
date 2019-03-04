<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Model;

use Dbtours\Guide\Api\Data\GuideExtensionInterface;
use Dbtours\Guide\Api\Data\GuideInterface;
use Dbtours\Guide\Model\ResourceModel\Guide as ResourceModelGuide;

use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Guide
 */
class Guide extends AbstractExtensibleModel implements GuideInterface
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModelGuide::class);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->_getData(self::ID) ?: null;
    }

    /**
     * @inheritdoc
     */
    public function setId($entityId)
    {
        $this->setData(self::ID, $entityId);
    }

    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return $this->_getData(self::FIRSTNAME);
    }

    /**
     * @inheritdoc
     */
    public function setFirstName($firstName)
    {
        $this->setData(self::FIRSTNAME, $firstName);
    }

    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return $this->_getData(self::LASTNAME);
    }

    /**
     * @inheritdoc
     */
    public function setLastName($lastName)
    {
        $this->setData(self::LASTNAME, $lastName);
    }

    /**
     * @inheritdoc
     */
    public function getTelephone()
    {
        return $this->_getData(self::TELEPHONE);
    }

    /**
     * @inheritdoc
     */
    public function setTelephone($telephone)
    {
        $this->setData(self::TELEPHONE, $telephone);
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->_getData(self::EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
    {
        $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(GuideExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
