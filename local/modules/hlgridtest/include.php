<?php 
//include_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/HLGridTest/libs/eventTabs.php");
//$eventManager = \Bitrix\Main\EventManager::getInstance(); 
//$eventManager->addEventHandler("crm","onEntityDetailsTabsInitialized",array("\\HLTest\\EventTabs","myOnEntityDetailsTabsInitialized"));
Bitrix\Main\Loader::registerAutoloadClasses("hlgridtest",array("\\HLGridTest\\EventTabs"=>"lib/eventTabs.php"));
Bitrix\Main\Loader::registerAutoloadClasses("hlgridtest",array("\\HLGridTest\\MyPrologEvent"=>"lib/myPrologEvent.php"));