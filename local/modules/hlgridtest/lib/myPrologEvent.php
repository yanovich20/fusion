<?php 
namespace HLGridTest;
use Bitrix\Main\Context;

class MyPrologEvent{
    public static function onPrologEventHandler(){
        $request = Context::getCurrent()->getRequest();
        if(strpos($request->getRequestedPageDirectory(),"deal")!==false)
        {
            \Bitrix\Main\Page\Asset::getInstance()->addJs("/local/modules/hlgridtest/lib/addFilterApply.js");
            \Bitrix\Main\Page\Asset::getInstance()->addJs("/local/modules/hlgridtest/lib/deleteRows.js");
        }
    }
}