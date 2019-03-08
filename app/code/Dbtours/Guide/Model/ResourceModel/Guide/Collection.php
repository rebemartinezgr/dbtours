<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Guide\Model\ResourceModel\Guide;

use Dbtours\Guide\Model\Guide as ModelGuide;
use Dbtours\Guide\Model\ResourceModel\Guide as ResourceModelGuide;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dbtours\Guide\Api\Data\GuideInterface;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{

    protected $_idFieldName = 'entity_id';

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(ModelGuide::class, ResourceModelGuide::class);
    }

    /**
     * Initialize select object
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->addLanguageData();

        return $this;
    }

    /**
     * @param $guideIds
     */
    public function addGuideFilter($guideIds)
    {
        $this->addFieldToFilter($this->_idFieldName, ['in' => $guideIds]);
    }

    /**
     * @return $this
     */
    private function addLanguageData()
    {
        $selectedField = ['group_concat(l.' . GuideInterface::LANGUAGE_LANGUAGE_CODE . ') as languages'];
        $cond = 'main_table.' . $this->_idFieldName . '= l.' . GuideInterface::LANGUAGE_GUIDE_ID;
        $table = ['l' => GuideInterface::LANGUAGE_TABLE];
        $this->getSelect()
            ->joinLeft($table, $cond, $selectedField)
            ->group($this->_idFieldName);

        return $this;
    }
}
