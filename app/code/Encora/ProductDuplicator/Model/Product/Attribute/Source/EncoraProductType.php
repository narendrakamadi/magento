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

namespace Encora\ProductDuplicator\Model\Product\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class EncoraProductType For Dropdown Options
 */
class EncoraProductType extends AbstractSource
{
    /**
     * Return DropDown Options for Encora Product Type
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('None'), 'value' => 'none'],
                ['label' => __('New'), 'value' => 'new'],
                ['label' => __('Used'), 'value' => 'used'],
                ['label' => __('Refurbished'), 'value' => 'refurbished'],
            ];
        }
        return $this->_options;
    }
}
