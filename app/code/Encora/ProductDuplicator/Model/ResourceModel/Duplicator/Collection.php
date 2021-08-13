<?php
/**
 * Encora_ProductDuplicator
 *
 * PHP version 7.x
 *
 * @category  PHP
 * @package   Encora\ProductDuplicator
 * @author    Narendra Kamadi <narendrakamadi@gmail.com>
 * @copyright 2021 Copyright (c) Encora
 */
declare (strict_types=1);

namespace Encora\ProductDuplicator\Model\ResourceModel\Duplicator;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection for ProductDuplicator
 */
class Collection extends AbstractCollection
{
    /**
     * Constructor for ProductDuplicator Collection
     */
    protected function _construct()
    {
        $this->_init(
            \Encora\ProductDuplicator\Model\Duplicator::class,
            \Encora\ProductDuplicator\Model\ResourceModel\DuplicatorResource::class
        );
    }
}
