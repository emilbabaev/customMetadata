<?php

namespace Custom\Metadata;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Config\Option;

class MetadataTable
{
    private static $hlblockId;

    private static function getHlblockId()
    {
        if (!self::$hlblockId) {
            self::$hlblockId = Option::get('custom.metadata', 'HLBLOCK_ID');
        }
        return self::$hlblockId;
    }

    private static function getEntity()
    {
        $hlblockId = self::getHlblockId();
        if ($hlblockId) {
            $hlblock = HL\HighloadBlockTable::getById($hlblockId)->fetch();
            return HL\HighloadBlockTable::compileEntity($hlblock);
        }
        return null;
    }

    public static function getByUrl($url)
    {
        $entity = self::getEntity();
        if ($entity) {
            $entityDataClass = $entity->getDataClass();
            return $entityDataClass::getList([
                'filter' => ['UF_URL' => $url],
                'select' => ['UF_TITLE', 'UF_DESCRIPTION', 'ID'],
            ])->fetch();
        }
        return false;
    }

    public static function add($data)
    {
        $entity = self::getEntity();
        if ($entity) {
            $entityDataClass = $entity->getDataClass();
            $result = $entityDataClass::add($data);
            return $result->isSuccess();
        }
        return false;
    }

    public static function update($id, $data)
    {
        $entity = self::getEntity();
        if ($entity) {
            $entityDataClass = $entity->getDataClass();
            $result = $entityDataClass::update($id, $data);
            return $result->isSuccess();
        }
        return false;
    }
}