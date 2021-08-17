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

/**
 * Interface ProductInterface
 */
interface ProductInterface
{
    /**
     * @return string
     */
    public function getSku();

    /**
     * @param $sku
     * @return $this
     */
    public function setSku($sku);
}
