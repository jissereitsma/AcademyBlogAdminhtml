<?php declare(strict_types=1);

namespace Koen\AcademyBlogAdminhtml\Controller\Adminhtml;

use Koen\AcademyBlogCore\Api\PostRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;

abstract class AbstractPostController extends Action
{
    const ADMIN_RESOURCE = 'Koen_AcademyBlogAdminhtml::top_level';

    /** @var PostRepositoryInterface */
    protected $postRepository;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository
    ) {
        parent::__construct($context);
        $this->postRepository = $postRepository;
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Koen'), __('Koen'))
            ->addBreadcrumb(__('Post'), __('Post'));
        return $resultPage;
    }
}

