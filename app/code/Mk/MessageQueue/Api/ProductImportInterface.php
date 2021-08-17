<?php
/**
 * Mk_MessageQueue
 *
 * PHP version 7.x
 *
 * @category  PHP
 * @package   Mk\MessageQueue
 * @author    Mk Team <mk.support@gmail.com>
 * @copyright 2020 Copyright (c) TechnoLab
 * @link      https://www.mktechnolab.com/
 **/
namespace Mk\MessageQueue\Api;

use Mk\MessageQueue\Api\ProductInterface;

/**
 * Interface ProductImportInterface
 */
interface ProductImportInterface
{
    /**
     * @param \Mk\MessageQueue\Api\ProductInterface $product[]
     * @return mixed
     */
    public function update(ProductInterface $product);
}
