<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Koen\AcademyBlogAdminhtml\Controller\Adminhtml\Post;

use Exception;
use Koen\AcademyBlogCore\Api\Data\PostInterface;
use Koen\AcademyBlogCore\Api\PostRepositoryInterface;
use Koen\AcademyBlogCore\Model\Blog\Post;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Koen\AcademyBlogAdminhtml\Controller\Adminhtml\Post
{
    protected $dataPersistor;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param RedirectFactory $redirectFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        RedirectFactory $redirectFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context, $postRepository);
        $this->redirectFactory = $redirectFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $postData = $this->getRequest()->getPostValue();

        if (empty($postData)) {
            return $resultRedirect->setPath('*/*');
        }

        $id = (int) $this->getRequest()->getParam('id');

        if ($id) {
            $post = $this->postRepository->get($id);
        } else {
            $post = $this->postRepository->create();
        }

        if ($postData['title'] !== null) {
            $post->setTitle($postData['title']);
        }

        if ($postData['url_key'] !== null) {
            $post->setUrlKey($postData['url_key']);
        }

        if ($postData['body'] !== null) {
            $post->setBody($postData['body']);
        }

        try {
            $this->postRepository->save($post);
            $this->messageManager->addSuccessMessage(__('Succesfully saved post'));
            $this->dataPersistor->clear('koen_academy_blog_post');

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $post->getId()]);
            }

            return $resultRedirect->setPath('*/*');
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception, __('Something went wrong while saving the post.'));
        }

        $this->dataPersistor->set('koen_academy_blog_post', $postData);

        return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
    }
}

