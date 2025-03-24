<?php

namespace Custom\Metadata;

use Bitrix\Main\Context;

class MetadataHandler
{
    public static function setMetadata(&$content)
    {
        global $APPLICATION;

        $url = parse_url(Context::getCurrent()->getRequest()->getRequestUri(), PHP_URL_PATH);

        $metadata = MetadataTable::getByUrl($url);

        if ($metadata) {
            if (!empty($metadata['UF_TITLE'])) {
                $APPLICATION->SetTitle($metadata['UF_TITLE']);
                $APPLICATION->SetPageProperty('title', $metadata['UF_TITLE']);
            }

            if (!empty($metadata['UF_DESCRIPTION'])) {
                $APPLICATION->SetPageProperty('description', $metadata['UF_DESCRIPTION']);
            }


            if (!empty($metadata['UF_TITLE']) || !empty($metadata['UF_DESCRIPTION'])) {
                $content = self::replaceMetaTags($content, $metadata['UF_TITLE'], $metadata['UF_DESCRIPTION']);
            }
        }
    }

    private static function replaceMetaTags($content, $title, $description)
    {

        $titlePattern = '/<title[^>]*>(.*?)<\/title>/is';
        $descPattern = '/<meta\s+name=["\']description["\'][^>]*content=["\'](.*?)["\'][^>]*>/is';


        if ($title) {
            if (preg_match($titlePattern, $content)) {
                $content = preg_replace($titlePattern, '<title>' . htmlspecialchars($title) . '</title>', $content);
            } else {
                $content = str_replace('</head>', '<title>' . htmlspecialchars($title) . '</title></head>', $content);
            }
        }

        if ($description) {
            if (preg_match($descPattern, $content)) {
                $content = preg_replace($descPattern, '<meta name="description" content="' . htmlspecialchars($description) . '">', $content);
            } else {
                $content = str_replace('</head>', '<meta name="description" content="' . htmlspecialchars($description) . '"></head>', $content);
            }
        }

        return $content;
    }
}