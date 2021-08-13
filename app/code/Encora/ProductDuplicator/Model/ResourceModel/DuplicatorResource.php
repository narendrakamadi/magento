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

namespace Encora\ProductDuplicator\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * ResourceModel Class For Duplicator
 */
class DuplicatorResource extends AbstractDb
{
    /**
     * Constructor function for Duplicator Resource
     */
    protected function _construct()
    {
        $this->_init('encora_productduplicator_tmp', 'id');
    }
}
