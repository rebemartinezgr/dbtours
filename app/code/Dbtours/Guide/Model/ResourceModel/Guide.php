<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Model\ResourceModel;

use Dbtours\Guide\Api\Data\GuideInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Guide
 */
class Guide extends AbstractDb
{
    /**
     * Initialize Resource Model
     */
    protected function _construct()
    {
        $this->_init(GuideInterface::TABLE, GuideInterface::ID);
    }

    /**
     * @inheritdoc
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $table = $this->getTable(GuideInterface::LANGUAGE_TABLE);
        $field = GuideInterface::LANGUAGE_LANGUAGE_CODE;
        try {
            $connection = $this->transactionManager->start($this->getConnection());
            $select     = $connection
                ->select()
                ->from($this->getTable($table), [$field])
                ->where(GuideInterface::LANGUAGE_GUIDE_ID . ' = :guide_id');

            $binds = [':guide_id' => (int)$object->getId()];

            $result  = [];
            $fetched = $connection->fetchAll($select, $binds);
            foreach ($fetched as $item) {
                if (isset($item[$field])) {
                    array_push($result, $item[$field]);
                }
            }
            $object->setData(GuideInterface::LANGUAGES, implode(",", $result));
            $this->transactionManager->commit();
        } catch (\Exception $e) {
            $this->transactionManager->rollBack();
            throw $e;
        }

        return parent::_afterLoad($object);
    }

    /**
     * @inheritdoc
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->saveToLanguageTable($object);
        return parent::_afterSave($object);
    }

    private function saveToLanguageTable($object)
    {
        $table = $this->getTable(GuideInterface::LANGUAGE_TABLE);
        $field = GuideInterface::LANGUAGE_LANGUAGE_CODE;
        $connection = $this->transactionManager->start($this->getConnection());
        try {
            $this->objectRelationProcessor->delete(
                $this->transactionManager,
                $connection,
                $table,
                $this->getConnection()
                    ->quoteInto(GuideInterface::LANGUAGE_GUIDE_ID . '=?', $object->getId()),
                $object->getData()
            );

            if (count($object->getLanguages())) {
                $data = [];
                foreach ($object->getLanguages() as $language) {
                    $data[] = [
                        GuideInterface::LANGUAGE_GUIDE_ID   => (int)$object->getId(),
                        $field => $language,
                    ];
                }
                if (count($data)) {
                    $this->getConnection()->insertMultiple($table, $data);
                }
            }
            $this->transactionManager->commit();
        } catch (\Exception $e) {
            $this->transactionManager->rollBack();
            throw $e;
        }
    }
}
