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
namespace Mk\MessageQueue\Model;

use Mk\MessageQueue\Api\ProductInterface;

/**
 * Class Product
 */
class Product implements ProductInterface
{
    protected $sku;

    /**
     * @param $sku
     * @return $this|Product
     */
    public function setSku($sku)
    {
        return $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }
}
