<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Koen\AcademyBlogAdminhtml\Controller\Adminhtml\Post;

class Delete extends \Koen\AcademyBlogAdminhtml\Controller\Adminhtml\Post
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = (int) $this->getRequest()->getParam('id');

        if (!$id) {
            $this->messageManager->addErrorMessage(__('No ID set'));
            return $resultRedirect->setPath('*/*');
        }

        try {
            $post = $this->postRepository->get($id);
            $this->postRepository->delete($post);
            $this->messageManager->addSuccessMessage(__('Deleted post: %1', $post->getTitle()));
            return $resultRedirect->setPath('*/*');
        } catch (\Exception $exception) {
            $this->messageManager->addSuccessMessage($exception->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }
    }
}

