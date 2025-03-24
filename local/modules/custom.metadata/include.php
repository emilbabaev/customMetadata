<?php

use Bitrix\Main\Loader;

Loader::includeModule('highloadblock');

AddEventHandler('main', 'OnEndBufferContent', ['Custom\\Metadata\\MetadataHandler', 'setMetadata']);