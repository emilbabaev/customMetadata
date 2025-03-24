<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;

global $USER;
$userGroups = $USER->GetUserGroupArray();

use Bitrix\Main\GroupTable;

$group = GroupTable::getList([
    'filter' => ['STRING_ID' => 'contentman'],
    'select' => ['ID']
])->fetch();

if ($group && in_array($group['ID'], $userGroups)):
    Loader::includeModule('custom.metadata');
    $url = parse_url(Context::getCurrent()->getRequest()->getRequestUri(), PHP_URL_PATH);
    $metadata = \Custom\Metadata\MetadataTable::getByUrl($url);
    ?>
    <div id="metadata-editor">
        <input type="text" id="metadata-title" value="<?= htmlspecialchars($metadata['UF_TITLE'] ?? '') ?>"
               placeholder="Title">
        <input type="text" id="metadata-description"
               value="<?= htmlspecialchars($metadata['UF_DESCRIPTION'] ?? '') ?>" placeholder="Description">
        <button id="metadata-save">Сохранить</button>
    </div>

    <script src="/local/modules/custom.metadata/js/metadata.js"></script>
    <?php
endif;
?>