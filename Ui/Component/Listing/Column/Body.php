<?php declare(strict_types=1);

namespace Koen\AcademyBlogAdminhtml\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class Body extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item['body'] = substr($item['body'], 0, 100);
            }
        }
    }
}
