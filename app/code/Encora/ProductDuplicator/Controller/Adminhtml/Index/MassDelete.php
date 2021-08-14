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

namespace Encora\ProductDuplicator\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Encora\ProductDuplicator\Model\ResourceModel\Duplicator\CollectionFactory;

class MassDelete extends Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * MassDelete constructor.
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param RedirectFactory $redirectFactory
     * @param Action\Context $context
     */
    public function __construct(
        Filter            $filter,
        CollectionFactory $collectionFactory,
        RedirectFactory   $redirectFactory,
        Action\Context    $context
    ) {
        $this->filter = $filter;
        $this->redirectFactory = $redirectFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return Redirect|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $product) {
            $product->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
