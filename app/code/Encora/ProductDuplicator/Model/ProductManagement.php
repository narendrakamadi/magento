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

use Encora\ProductDuplicator\Logger\Logger;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Encora\ProductDuplicator\Model\ResourceModel\DuplicatorResource;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\Product\Copier;

/**
 * Class ProductManagement of ProductDuplicator
 */
class ProductManagement
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ProductCollection
     */
    private $productCollection;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var DuplicatorResource
     */
    private $productDuplicator;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var Copier
     */
    private $copier;

    const ENCORA_PRODUCT_TYPE_NEW = 'new';
    const PRODUCT_TYPE_ID = 'simple';
    const XML_PATH_DUPLICATE_PRODUCT_LOGGER_ENABLE = 'productduplicator/general/debug';
    const XML_PATH_PRODUCT_BATCH_LIMIT = 'productduplicator/general/product_limit';

    /**
     * @param Logger $logger
     * @param ProductCollection $productCollection
     * @param ScopeConfigInterface $scopeConfig
     * @param DuplicatorResource $productDuplicator
     * @param ProductRepository $productRepository
     * @param Copier $copier
     */
    public function __construct(
        Logger $logger,
        ProductCollection $productCollection,
        ScopeConfigInterface $scopeConfig,
        DuplicatorResource $productDuplicator,
        ProductRepository $productRepository,
        Copier $copier
    ) {
        $this->logger = $logger;
        $this->productCollection = $productCollection;
        $this->scopeConfig = $scopeConfig;
        $this->productDuplicator = $productDuplicator;
        $this->productRepository = $productRepository;
        $this->copier = $copier;
    }

    /**
     * Function for Schedule Product Batch
     */
    public function scheduleBatch()
    {
        try {
            $productCollection = $this->productCollection
                ->addAttributeToSelect('*')
                ->addFieldTofilter('type_id', self::PRODUCT_TYPE_ID)
                ->addAttributeToFilter('encora_product_type', self::ENCORA_PRODUCT_TYPE_NEW);

            if ($productCollection->getSize() > 0) {
                foreach ($productCollection as $product) {
                    $products[] = [
                        'product_id' => $product->getId(),
                        'product_name' => $product->getName(),
                        'product_sku' => $product->getSku(),
                        'status' => 0
                    ];
                }
                $this->productDuplicator->getConnection()->insertMultiple(
                    'encora_productduplicator_tmp',
                    $products
                );
                if ($this->isLoggerEnabled()) {
                    $this->logger->info("Product's added into the queue successfully");
                }
            } else {
                if ($this->isLoggerEnabled()) {
                    $this->logger->error('Products with encora type new are not found.');
                }
            }
        } catch (\Exception $e) {
            if ($this->isLoggerEnabled()) {
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * @return array
     */
    public function getProductBatch()
    {
        $connection = $this->productDuplicator->getConnection();
        $select = $connection
            ->select()
            ->from($this->productDuplicator->getTable('encora_productduplicator_tmp'))
            ->where('status = 0');

        return $connection->fetchAll($select);
    }

    /**
     * @param $productId
     */
    public function processQueue($productId)
    {
        try {
            $newProductInstance = $this->productRepository->getById($productId);
            $this->duplicateProduct($newProductInstance);
        } catch (\Exception $e) {
            $this->logger->warning('Data Not Found');
        }
    }

    private function duplicateProduct($newProductInstance)
    {
        $this->copier->copy($newProductInstance);
        $this->logger->info("Product with SKU $newProductInstance->getSku() copied successfully.");
    }

    /**
     * @return mixed
     */
    public function isLoggerEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DUPLICATE_PRODUCT_LOGGER_ENABLE);
    }

    /**
     * @return mixed
     */
    public function getProductBatchProcessLimit()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PRODUCT_BATCH_LIMIT);
    }
}
