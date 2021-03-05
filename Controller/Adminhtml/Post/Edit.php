<?php declare(strict_types=1);

namespace Koen\AcademyBlogAdminhtml\Controller\Adminhtml\Post;

use Koen\AcademyBlogAdminhtml\Controller\Adminhtml\AbstractPostController;
use Koen\AcademyBlogCore\Api\PostRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory as ResultPageFactory;

class Edit extends AbstractPostController
{
    /** @var ResultPageFactory */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param ResultPageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        ResultPageFactory $resultPageFactory
    ) {
        parent::__construct($context, $postRepository);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Edit action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $post = $this->postRepository->create();

        if ($id) {
            $post = $this->postRepository->get($id);

            if (!$post->getId()) {
                $this->messageManager->addErrorMessage(__('This post no longer exists'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*');
            }
        }

        $resultPage = $this->resultPageFactory->create();

        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Post') : __('New Post'),
            $id ? __('Edit Post') : __('New Post')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));
        $resultPage->getConfig()->getTitle()->prepend($post->getId() ? __('Edit Post %1', $post->getId()) : __('New Post'));

        return $resultPage;
    }
}

