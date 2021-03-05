<?php declare(strict_types=1);

namespace Koen\AcademyBlogAdminhtml\Model\Post;

use Koen\AcademyBlogCore\Model\Blog\Resource\Collection\PostCollection;
use Koen\AcademyBlogCore\Model\Blog\Resource\Collection\PostCollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{
    /** @var PostCollection */
    protected $postCollection;

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /** @var array */
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        PostCollectionFactory $postCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->postCollection = $postCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->postCollection->getItems();

        foreach ($items as $dealer) {
            $this->loadedData[$dealer->getId()] = $dealer->getData();
        }

        $data = $this->dataPersistor->get('post');
        if (!empty($data)) {
            $dealer = $this->collection->getNewEmptyItem();
            $dealer->setData($data);
            $this->loadedData[$dealer->getId()] = $dealer->getData();
            $this->dataPersistor->clear('post');
        }

        return $this->loadedData;
    }
}
