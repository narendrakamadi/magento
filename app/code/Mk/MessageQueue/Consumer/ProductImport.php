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
namespace Mk\MessageQueue\Consumer;

/**
 * Class ProductImport
 */
class ProductImport
{
    public function ConsumerProcess($operation)
    {
        $path = "/var/www/html/magento-local/var/log/messageQueue.log";
        file_put_contents($path, $operation);
    }
}
