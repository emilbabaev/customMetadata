<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Highloadblock as HL;

Loc::loadMessages(__FILE__);

class custom_metadata extends CModule
{
    public $MODULE_ID = 'custom.metadata';
    public $MODULE_VERSION = '1.0.0';
    public $MODULE_VERSION_DATE = '2025-03-24 00:00:00';
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        $this->MODULE_NAME = Loc::getMessage('CUSTOM_METADATA_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('CUSTOM_METADATA_MODULE_DESC');
        Loader::includeModule('highloadblock');
    }

    public function DoInstall()
    {
        $this->InstallDB();
        $this->InstallFiles();
        RegisterModule($this->MODULE_ID);
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
    }

    public function InstallDB()
    {
        $hlblockId = $this->createHighloadBlock();
        if ($hlblockId) {
            Bitrix\Main\Config\Option::set($this->MODULE_ID, 'HLBLOCK_ID', $hlblockId);
        }
    }

    public function UnInstallDB()
    {
        $hlblockId = Bitrix\Main\Config\Option::get($this->MODULE_ID, 'HLBLOCK_ID');
        if ($hlblockId) {
            HL\HighloadBlockTable::delete($hlblockId);
        }
    }

    public function InstallFiles()
    {
        CopyDirFiles(__DIR__ . '/../js', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/' . $this->MODULE_ID, true, true);
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx('/bitrix/js/' . $this->MODULE_ID);
    }

    private function createHighloadBlock()
    {
        $result = HL\HighloadBlockTable::add([
            'NAME' => 'Metadata',
            'TABLE_NAME' => 'custom_metadata',
        ]);

        if (!$result->isSuccess()) {
            return false;
        }

        $hlblockId = $result->getId();
        $fields = [
            [
                'ENTITY_ID' => 'HLBLOCK_' . $hlblockId,
                'FIELD_NAME' => 'UF_URL',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' => ['ru' => 'URL'],
                'LIST_COLUMN_LABEL' => ['ru' => 'URL'],
                'SETTINGS' => ['SIZE' => 50],
            ],
            [
                'ENTITY_ID' => 'HLBLOCK_' . $hlblockId,
                'FIELD_NAME' => 'UF_TITLE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => ['ru' => 'Title'],
                'LIST_COLUMN_LABEL' => ['ru' => 'Title'],
                'SETTINGS' => ['SIZE' => 50],
            ],
            [
                'ENTITY_ID' => 'HLBLOCK_' . $hlblockId,
                'FIELD_NAME' => 'UF_DESCRIPTION',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => ['ru' => 'Description'],
                'LIST_COLUMN_LABEL' => ['ru' => 'Description'],
                'SETTINGS' => ['SIZE' => 50],
            ],
        ];

        $userTypeEntity = new CUserTypeEntity();
        foreach ($fields as $field) {
            $userTypeEntity->Add($field);
        }

        return $hlblockId;
    }
}