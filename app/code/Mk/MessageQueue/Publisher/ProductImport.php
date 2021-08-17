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
namespace Mk\MessageQueue\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;

/**
 * Class ProductImport
 */
class ProductImport
{
    const TOPIC_NAME = 'productImport.topic';

    /**
     * ProductImport constructor.
     *
     * @param PublisherInterface $publisher
     */
    public function __construct(
        PublisherInterface $publisher
    ) {
        $this->publisher = $publisher;
    }

    /**
     * @param array $data
     * @return mixed|null
     */
    public function publish(array $data)
    {
        return $this->publisher->publish(self::TOPIC_NAME, json_encode($data));
    }
}
