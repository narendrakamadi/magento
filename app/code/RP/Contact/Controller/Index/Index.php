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

namespace RP\Contact\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Index for RP Contact
 */
class Index extends Action
{
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
