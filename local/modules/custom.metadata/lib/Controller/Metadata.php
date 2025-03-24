<?php

namespace Custom\Metadata\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Custom\Metadata\MetadataTable;

class Metadata extends Controller
{
    public function configureActions()
    {
        return [
            'save' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(['POST']),
                ],
            ],
        ];
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        $url = $request->getPost('url');
        $title = $request->getPost('title');
        $description = $request->getPost('description');

        if (empty($url)) {
            return ['status' => 'error', 'message' => 'URL обязателен'];
        }

        $metadata = MetadataTable::getByUrl($url);
        if ($metadata) {
            $result = MetadataTable::update($metadata['ID'], [
                'UF_TITLE' => $title,
                'UF_DESCRIPTION' => $description,
            ]);
        } else {
            $result = MetadataTable::add([
                'UF_URL' => $url,
                'UF_TITLE' => $title,
                'UF_DESCRIPTION' => $description,
            ]);
        }

        return $result ? ['status' => 'success'] : ['status' => 'error', 'message' => 'Не удалось сохранить'];
    }
}