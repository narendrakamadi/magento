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

use Mk\MessageQueue\Api\ProductImportInterface;
use Mk\MessageQueue\Api\ProductInterface;
use Mk\MessageQueue\Publisher\ProductImport as Publisher;

/**
 * Class ProductImport
 */
class ProductImport implements ProductImportInterface
{
    /**
     * @var Publisher
     */
    protected $publisher;

    /**
     * ProductImport constructor.
     * @param Publisher $publisher
     */
    public function __construct(
        Publisher $publisher
    ) {
        $this->publisher = $publisher;
    }

    /**
     * @param \Mk\MessageQueue\Api\ProductInterface $product[]
     * @return mixed|void
     */
    public function update(ProductInterface $product)
    {
        return $this->publisher->publish([$product->getSku()]);
    }
}
