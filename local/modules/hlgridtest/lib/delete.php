<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;

try{
    $request = Context::getCurrent()->getRequest();

    Loader::includeModule("highloadblock");

    $hlBlockByNameObj = HL\HighloadBlockTable::getList(array("filter"=>["=NAME"=>"VisitorSite"],"select"=>["ID"]))->fetch();
    $hlBlockId = $hlBlockByNameObj["ID"];

    $hlblock = HL\HighloadBlockTable::getById($hlBlockId)->fetch();

    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    $rowsIds = $request->get("rows");

    foreach($rowsIds as $id)
    {
        $entity_data_class::delete($id);
    }
    $result["status"]="success";
    $result["message"]="OK";
    echo json_encode($result);
}
catch(\Throwable $e)
{
    $result["status"]="error";
    $result["message"]=$e->getMessage();
    echo json_encode($result);
}
