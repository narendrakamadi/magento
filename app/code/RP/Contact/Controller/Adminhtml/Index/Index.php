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

namespace RP\Contact\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index for Contact
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
        $resultPage->getConfig()->getTitle()->prepend((__('Right Point Contacts')));

        return $resultPage;
    }

    /**
     * Check Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('RP_Contact::contact');
    }
}
