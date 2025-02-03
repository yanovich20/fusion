<?php
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;

Loader::IncludeModule('highloadblock');
$filter = array(
	'select' => array('ID'),
	'filter' => array('=NAME' => "VisitorSite")
);
$hlblock = HL\HighloadBlockTable::getList($filter)->fetch();
if(is_array($hlblock) && !empty($hlblock))
{
	$result = HL\HighloadBlockTable::delete($hlblock['ID']);
	if($result->isSuccess())
	{
		echo CAdminMessage::ShowNote("Модуль HLTEST успешно удален");
	}
	else{
		echo CAdminMessage::ShowMessage("Ошибка удаления модуля HLTEST");
	}
}