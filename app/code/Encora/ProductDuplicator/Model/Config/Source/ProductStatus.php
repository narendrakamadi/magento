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

namespace Encora\ProductDuplicator\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class ProductStatus for ProductDuplicator
 */
class ProductStatus implements OptionSourceInterface
{
    /**
     * Value which equal Completed for Status dropdown.
     */
    const COMPLETED_VALUE = 1;

    /**
     * Value which equal Pending for Status dropdown.
     */
    const PENDING_VALUE = 0;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::COMPLETED_VALUE, 'label' => __('Completed')],
            ['value' => self::PENDING_VALUE, 'label' => __('Pending')],
        ];
    }
}
