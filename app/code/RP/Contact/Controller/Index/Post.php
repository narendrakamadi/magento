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
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use RP\Contact\Model\Contact;
use RP\Contact\Model\ResourceModel\ContactResource;
use RP\Contact\Model\ContactFactory;

class Post extends Action
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Contact
     */
    private $contactModel;

    /**
     * @var ContactResource
     */
    private $contactResource;

    /**
     * @var ContactFactory
     */
    private $contactFactory;

    /**
     * @param Context $context
     * @param LoggerInterface $logger
     * @param ContactFactory $contactModel
     * @param ContactResource $contactResource
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        ContactFactory $contactModel,
        ContactResource $contactResource,
        ContactFactory $contactFactory
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->contactModel = $contactModel;
        $this->contactResource = $contactResource;
        $this->contactFactory = $contactFactory;
    }

    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        try {
            $this->processData($this->validatedParams());
            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $this->resultRedirectFactory->create()->setPath('rpcontact/index');
    }

    /**
     * Process Contact Us Data
     */
    protected function processData($post)
    {
        try {
            $contactData = [
                'name' => $post['name'],
                'email' => $post['email'],
                'telephone' => $post['telephone'],
                'comment' => $post['comment']
            ];
            // $contactModel = $this->contactModel->setData($contactData);
            $contactFactory = $this->contactFactory->create();
            $contactFactory->setData($contactData);
            $this->contactResource->save($contactFactory);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    protected function validatedParams()
    {
        $request = $this->getRequest();
        if (trim($request->getParam('name')) === '') {
            throw new LocalizedException(__('Enter the Name and try again.'));
        }
        if (trim($request->getParam('comment')) === '') {
            throw new LocalizedException(__('Enter the comment and try again.'));
        }
        if (false === \strpos($request->getParam('email'), '@')) {
            throw new LocalizedException(__('The email address is invalid. Verify the email address and try again.'));
        }
        if (trim($request->getParam('hideit')) !== '') {
            throw new \Exception();
        }

        return $request->getParams();
    }
}
