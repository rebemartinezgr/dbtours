<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

namespace Dbtours\Catalog\Plugin\Model\Product;

use Magento\Catalog\Model\Product\Option as MagentoOption;
use Magento\Catalog\Model\Product\Option\Type\Factory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Option
 */
class Option
{
    /**
     * @var Factory
     */
    private $optionTypeFactory;

    /**
     * @var array
     */
    private $optionGroupsToTypes;

    /**
     * @var array
     */
    private $optionGroupsToClass;

    /**
     * Option constructor.
     * @param Factory $optionTypeFactory
     * @param array $optionGroupsToTypes
     * @param array $optionGroupsToClass
     */
    public function __construct(
        Factory $optionTypeFactory,
        array $optionGroupsToTypes = [],
        array $optionGroupsToClass = []
    ) {
        $this->optionTypeFactory   = $optionTypeFactory;
        $this->optionGroupsToTypes = array_combine(
            array_column($optionGroupsToTypes, 'type'),
            array_column($optionGroupsToTypes, 'group')
        );
        $this->optionGroupsToClass = array_combine(
            array_column($optionGroupsToClass, 'group'),
            array_column($optionGroupsToClass, 'class')
        );
    }

    /**
     * @param MagentoOption $subject
     * @param \Closure $proceed
     * @param string $type
     * @return MagentoOption\Type\DefaultType
     * @throws LocalizedException
     */
    public function aroundGroupFactory(
        MagentoOption $subject,
        \Closure $proceed,
        string $type
    ) {
        if (!$this->supportsType($type)) {
            return $proceed($type);
        }

        $group = $subject->getGroupByType($type);
        if ($this->supportsGroup($group)) {
            return $this->optionTypeFactory->create($this->optionGroupsToClass[$group]);
        }
        throw new LocalizedException(__('The option type to get group instance is incorrect.'));
    }

    /**
     * @param MagentoOption $subject
     * @param \Closure $proceed
     * @param string $type
     * @return mixed|string
     */
    public function aroundGetGroupByType(
        MagentoOption $subject,
        \Closure $proceed,
        ?string $type
    ) {
        if (!$this->supportsType($type)) {
            return $proceed($type);
        }

        return $this->optionGroupsToTypes[$type] ?? '';
    }

    /**
     * @param string|null $type
     * @return bool
     */
    private function supportsType(?string $type): bool
    {
        return isset($this->optionGroupsToTypes[$type]);
    }

    /**
     * @param string|null $group
     * @return bool
     */
    private function supportsGroup(?string $group): bool
    {
        return isset($this->optionGroupsToClass[$group]);
    }
}
