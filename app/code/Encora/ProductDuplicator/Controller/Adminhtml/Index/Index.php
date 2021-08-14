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
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index for ProductDuplicator
 */
class Index extends Action
{
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Product Duplicator')));

        return $resultPage;
    }

    /**
     * Check Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Encora_ProductDuplicator::productduplicator');
    }
}
