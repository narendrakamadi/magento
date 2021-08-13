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

namespace Encora\ProductDuplicator\Cron;

use Encora\ProductDuplicator\Model\ProductManagement;
use Encora\ProductDuplicator\Logger\Logger;

/**
 * Class ProductCopier for CRON Job
 */
class ProductCopier
{
    /**
     * @var ProductManagement
     */
    private $productManager;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(
        ProductManagement $productManager,
        Logger $logger
    ) {
        $this->productManager = $productManager;
        $this->logger = $logger;
    }

    public function execute()
    {
        $productCollection = $this->productManager->getProductBatch();
        if (count($productCollection) > 0) {
            foreach ($productCollection as $product) {
                $this->productManager->processQueue($product['id']);
            }
        } else {
            $this->logger->warning('Data Not Found');
        }
    }
}
