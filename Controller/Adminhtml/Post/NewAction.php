<?php declare(strict_types=1);

namespace Koen\AcademyBlogAdminhtml\Controller\Adminhtml\Post;

use Koen\AcademyBlogAdminhtml\Controller\Adminhtml\Post;
use Koen\AcademyBlogCore\Api\PostRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;

class NewAction extends Post
{
    protected $forwardFactory;

    /**
     * NewAction constructor.
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param ForwardFactory $forwardFactory
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        ForwardFactory $forwardFactory
    ) {
        parent::__construct($context, $postRepository);
        $this->forwardFactory = $forwardFactory;
    }

    /**
     * New action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultForward = $this->forwardFactory->create();
        return $resultForward->forward('edit');
    }
}

