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

namespace Encora\ProductDuplicator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Encora\ProductDuplicator\Model\ProductManagement;

/**
 * Class DuplicateProduct for ProductDuplicator
 */
class DuplicateProduct extends Command
{
    /**
     * @var ProductManagement
     */
    private $productManager;

    /**
     * @param ProductManagement $productManager
     * @param string|null $name
     */
    public function __construct(
        ProductManagement $productManager,
        string $name = null
    ) {
        $this->productManager = $productManager;
        parent::__construct($name);
    }

    /**
     * Configure Function
     */
    protected function configure()
    {
        $this->setName('encora:duplicate-product');
        $this->setDescription('Copy simple products which has new encora type set into used & refurbished type.');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    public function execute(
        InputInterface  $input,
        OutputInterface $output
    ) {
        $this->productManager->scheduleBatch();
        $output->writeln("Product's added into the queue successfully.");
    }
}
