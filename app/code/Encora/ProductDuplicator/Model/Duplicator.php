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

namespace Encora\ProductDuplicator\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Model Class For Duplicator
 */
class Duplicator extends AbstractModel
{
    public function __construct()
    {
        $this->_init(\Encora\ProductDuplicator\Model\ResourceModel\DuplicatorResource::class);
    }
}
