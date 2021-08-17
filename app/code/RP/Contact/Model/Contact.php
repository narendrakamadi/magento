<?php
/**
 * RP_Contact
 *
 * PHP version 7.x
 *
 * @category  PHP
 * @package   RP\Contact
 * @author    Narendra Kamadi <narendrakamadi@gmail.com>
 * @copyright 2021 Copyright (c) Right Point
 */
declare (strict_types=1);

namespace RP\Contact\Model;

use Magento\Framework\Model\AbstractModel;
use RP\Contact\Model\ResourceModel\ContactResource;

/**
 * Class Right Point Contact
 */
class Contact extends AbstractModel
{
    public function _construct()
    {
        $this->_init(ContactResource::class);
    }
}
