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
use Magento\Catalog\Model\ProductFactory;

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
     * @var ProductFactory
     */
    protected $productFactory;

    const ENCORA_PRODUCT_TYPE_NEW = 'new';
    const ENCORA_PRODUCT_TYPE_USED = 'used';
    const ENCORA_PRODUCT_TYPE_REFURBISHED = 'refurbished';
    const PRODUCT_TYPE_ID = 'simple';
    const XML_PATH_DUPLICATE_PRODUCT_LOGGER_ENABLE = 'productduplicator/general/debug';
    const XML_PATH_PRODUCT_BATCH_LIMIT = 'productduplicator/general/product_limit';

    /**
     * @param Logger $logger
     * @param ProductCollection $productCollection
     * @param ScopeConfigInterface $scopeConfig
     * @param DuplicatorResource $productDuplicator
     * @param ProductRepository $productRepository
     * @param ProductFactory $productFactory
     */
    public function __construct(
        Logger $logger,
        ProductCollection $productCollection,
        ScopeConfigInterface $scopeConfig,
        DuplicatorResource $productDuplicator,
        ProductRepository $productRepository,
        ProductFactory $productFactory
    ) {
        $this->logger = $logger;
        $this->productCollection = $productCollection;
        $this->scopeConfig = $scopeConfig;
        $this->productDuplicator = $productDuplicator;
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
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

    /**
     * @param $newProductInstance
     */
    private function duplicateProduct($newProductInstance)
    {
        try {
            $this->duplicateUsedProduct($newProductInstance);
            $this->duplicateRefurbishedProduct($newProductInstance);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param $newProduct
     */
    protected function duplicateUsedProduct($newProduct)
    {
        try {
            $usedProductInstance = $this->productFactory->create();
            $usedProductInstance->setSku($newProduct->getSku() . '_' . self::ENCORA_PRODUCT_TYPE_USED);
            $usedProductInstance->setName($newProduct->getName() . ' ' . ucfirst(self::ENCORA_PRODUCT_TYPE_USED));
            $usedProductInstance->setUrlKey($newProduct->getUrlKey() . '_' . self::ENCORA_PRODUCT_TYPE_USED);
            $usedProductInstance->setAttributeSetId($newProduct->getDefaultAttributeSetId());
            $usedProductInstance->setStatus($newProduct->getStatus());
            $usedProductInstance->setVisibility($newProduct->getVisibility());
            $usedProductInstance->setTaxClassId($newProduct->getTaxClassId());
            $usedProductInstance->setTypeId($newProduct->getTypeId());
            $usedProductInstance->setProductHasWeight($newProduct->getProductHasWeight());
            $usedProductInstance->setWeight($newProduct->getWeight());
            $usedProductInstance->setEncoraProductType(self::ENCORA_PRODUCT_TYPE_USED);
            $usedProductInstance->setPrice($newProduct->getPrice());
            $usedProductInstance->setWebsiteIds($newProduct->getWebsiteIds());
            $usedProductInstance->setCategoryIds($newProduct->getCategoryIds());
            $usedProductInstance->setStockData(
                [
                    'use_config_manage_stock' => 0,
                    'manage_stock' => 1,
                    'is_in_stock' => 1,
                    'qty' => $newProduct->getDefaultAttributeSetId()
                ]
            );
            $usedProductInstance->save();
            $this->logger->info("Product with a SKU " . $newProduct->getSku() . " copied for used type successfully.");
            $this->updateQueueProduct($newProduct->getId());
        } catch (\Exception $e) {
            $this->logger->info("Product with a SKU " . $newProduct->getSku() . " was not copied for used type. Error : " . $e->getMessage());
        }
    }

    protected function duplicateRefurbishedProduct($newProduct)
    {
        try {
            $refurbishedProductInstance = $this->productFactory->create();
            $refurbishedProductInstance->setSku($newProduct->getSku() . '_' . self::ENCORA_PRODUCT_TYPE_REFURBISHED);
            $refurbishedProductInstance->setName($newProduct->getName() . ' ' . ucfirst(self::ENCORA_PRODUCT_TYPE_REFURBISHED));
            $refurbishedProductInstance->setUrlKey($newProduct->getUrlKey() . '_' . self::ENCORA_PRODUCT_TYPE_REFURBISHED);
            $refurbishedProductInstance->setAttributeSetId($newProduct->getDefaultAttributeSetId());
            $refurbishedProductInstance->setStatus($newProduct->getStatus());
            $refurbishedProductInstance->setVisibility($newProduct->getVisibility());
            $refurbishedProductInstance->setTaxClassId($newProduct->getTaxClassId());
            $refurbishedProductInstance->setTypeId($newProduct->getTypeId());
            $refurbishedProductInstance->setProductHasWeight($newProduct->getProductHasWeight());
            $refurbishedProductInstance->setWeight($newProduct->getWeight());
            $refurbishedProductInstance->setEncoraProductType(self::ENCORA_PRODUCT_TYPE_REFURBISHED);
            $refurbishedProductInstance->setPrice($newProduct->getPrice());
            $refurbishedProductInstance->setWebsiteIds($newProduct->getWebsiteIds());
            $refurbishedProductInstance->setCategoryIds($newProduct->getCategoryIds());
            $refurbishedProductInstance->setStockData(
                [
                    'use_config_manage_stock' => 0,
                    'manage_stock' => 1,
                    'is_in_stock' => 1,
                    'qty' => $newProduct->getDefaultAttributeSetId()
                ]
            );
            $refurbishedProductInstance->save();
            $this->logger->info("Product with a SKU " . $newProduct->getSku() . " copied for refurbished type successfully.");
            $this->updateQueueProduct($newProduct->getId());
        } catch (\Exception $e) {
            $this->logger->info("Product with an SKU " . $newProduct->getSku() . " was not copied for refurbished type. Error : " . $e->getMessage());
        }
    }

    /**
     * @param $productId
     */
    public function updateQueueProduct($productId)
    {
        $connection  = $this->productDuplicator->getConnection();
        $insertData = ['status' => 1];
        $where = ['product_sku = ?' => $productId];
        $tableName = $connection->getTableName("encora_productduplicator_tmp");
        $connection->update($tableName, $insertData, $where);
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
