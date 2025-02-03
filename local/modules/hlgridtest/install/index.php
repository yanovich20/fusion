<?
Class HLGridTest extends CModule
{
var $MODULE_ID = "hlgridtest";
var $MODULE_VERSION;
var $MODULE_VERSION_DATE;
var $MODULE_NAME;
var $MODULE_DESCRIPTION;
var $MODULE_CSS;
  function __construct()
  {
  $arModuleVersion = array();
  $path = str_replace("\\", "/", __FILE__);
  $path = substr($path, 0, strlen($path) - strlen("/index.php"));
  include($path."/version.php");
  if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
  {
  $this->MODULE_VERSION = $arModuleVersion["VERSION"];
  $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
  }
  $this->MODULE_NAME = "HlTest модуль для работы с highload блоками и гридом";
  $this->MODULE_DESCRIPTION = "После установки вы сможете пользоваться вкладкой с посетителями сайта";
  }
  function InstallFiles()
  {
  //CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/dv_module/install/components",
  //           $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
  return true;
  }
  function UnInstallFiles()
  {
  //eleteDirFilesEx("/local/components/dv");
  return true;
  }
  function DoInstall()
  {
  global $DOCUMENT_ROOT, $APPLICATION;
  $this->InstallFiles();
  RegisterModule("hlgridtest");
  $this->installEvents();
  $APPLICATION->IncludeAdminFile("Установка модуля HLTest", $DOCUMENT_ROOT."/local/modules/hlgridtest/install/step.php");
  echo "world!";
  }
  function DoUninstall()
  {
  global $DOCUMENT_ROOT, $APPLICATION;
  $this->UnInstallFiles();
  $this->unInstallEvents();
  UnRegisterModule("hlgridtest");
  $APPLICATION->IncludeAdminFile("Деинсталляция модуля HLTest", $DOCUMENT_ROOT."/local/modules/hlgridtest/install/unstep.php");
  }
  function installEvents(){
    $eventManager = \Bitrix\Main\EventManager::getInstance(); 
    $eventManager->registerEventHandler("crm","onEntityDetailsTabsInitialized","hlgridtest","\\HLGridTest\\EventTabs","myOnEntityDetailsTabsInitialized");
    $eventManager->registerEventHandler("main","onProlog","hlgridtest","\\HLGridTest\\MyPrologEvent","onPrologEventHandler");
  }
  function unInstallEvents()
  {
    $eventManager = \Bitrix\Main\EventManager::getInstance(); 
    $eventManager->unRegisterEventHandler("crm","onEntityDetailsTabsInitialized","HLGridTest","\\HLGridTest\\EventTabs","myOnEntityDetailsTabsInitialized");
    $eventManager->registerEventHandler("main","onProlog","hlgridtest","\\HLGridTest\\MyPrologEvent","onPrologEventHandler");
  }
}
?>
