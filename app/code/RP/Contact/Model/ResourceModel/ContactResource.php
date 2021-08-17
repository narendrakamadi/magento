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

namespace RP\Contact\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class RP ContactResource
 */
class ContactResource extends AbstractDb
{
    public function _construct()
    {
        $this->_init('rp_contact', 'id');
    }
}
