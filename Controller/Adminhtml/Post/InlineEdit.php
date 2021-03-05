<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Koen\AcademyBlogAdminhtml\Controller\Adminhtml\Post;

use Koen\AcademyBlogCore\Api\PostRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Koen\AcademyBlogAdminhtml\Controller\Adminhtml\Post;

class InlineEdit extends Post
{
    /** @var ResultJsonFactory */
    protected $resultJsonFactory;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param ResultJsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        ResultJsonFactory $resultJsonFactory
    ) {
        parent::__construct($context, $postRepository);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Inline edit action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        if (!$this->getRequest()->getParam('isAjax')) {
            return $resultJson->setData([
                'messages' => [__('Invalid Ajax request')],
                'error' => true
            ]);
        }

        $postItems = $this->getRequest()->getParam('items', []);

        if (count($postItems) === 0) {
            return $resultJson->setData([
                'messages' => [__('Could not save because no data is received')],
                'error' => true
            ]);
        }

        $error = false;
        $messages = [];
        foreach (array_keys($postItems) as $postId) {
            $post = $this->postRepository->get((int) $postId);
            $postData = $postItems[$postId];

            if (isset($postData['title'])) {
                $post->setTitle($postData['title']);
            }

            try {
                $this->postRepository->save($post);
            } catch (\Exception $exception) {
                $messages[] = "[Post ID: {$postId}]  {$e->getMessage()}";
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}

