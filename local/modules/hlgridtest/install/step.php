<?if(!check_bitrix_sessid()) return;?>

<?

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
createHLBlock();
function createHLBlock(){
    Loader::IncludeModule('highloadblock');
    
$arLangs = Array(
    'ru' => 'Посетители сайта',
    'en' => 'Site visitors'
);

$result = HL\HighloadBlockTable::add(array(
    'NAME' => 'VisitorSite',
    'TABLE_NAME' => 'visitor_site', 
));
if ($result->isSuccess()) {
    $id = $result->getId();
    foreach($arLangs as $lang_key => $lang_val){
        HL\HighloadBlockLangTable::add(array(
            'ID' => $id,
            'LID' => $lang_key,
            'NAME' => $lang_val
        ));
    }
} else {
    $errors = $result->getErrorMessages();
    var_dump($errors);  
}
$UFObject = "HLBLOCK_".$id;
$arVisitorsFields = Array(
    'UF_VISITOR_NAME'=>Array(
        'ENTITY_ID' => $UFObject,
        'FIELD_NAME' => 'UF_NAME',
        'USER_TYPE_ID' => 'string',
        'MANDATORY' => 'Y',
        "EDIT_FORM_LABEL" => Array('ru'=>'Имя посетителя', 'en'=>'Visitor name'), 
        "LIST_COLUMN_LABEL" => Array('ru'=>'Имя посетителя', 'en'=>'Visitor name'),
        "LIST_FILTER_LABEL" => Array('ru'=>'Имя посетителя', 'en'=>'Visitor name'), 
        "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
        "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
    ),
    'UF_DATE_VISIT'=>Array(
        'ENTITY_ID' => $UFObject,
        'FIELD_NAME' => 'UF_DATE_VISIT',
        'USER_TYPE_ID' => 'date',
        'MANDATORY' => 'Y',
        "EDIT_FORM_LABEL" => Array('ru'=>'Дата посещения', 'en'=>'Date vizt'), 
        "LIST_COLUMN_LABEL" => Array('ru'=>'Дата посещения', 'en'=>'Date vizt'),
        "LIST_FILTER_LABEL" => Array('ru'=>'Дата посещения', 'en'=>'Date vizt'), 
        "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
        "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
    ),
    'UF_AGE'=>Array(
        'ENTITY_ID' => $UFObject,
        'FIELD_NAME' => 'UF_AGE',
        'USER_TYPE_ID' => 'integer',
        'MANDATORY' => 'Y',
        "EDIT_FORM_LABEL" => Array('ru'=>'Возраст посетителя', 'en'=>'Age visitor'), 
        "LIST_COLUMN_LABEL" => Array('ru'=>'Возраст посетителя', 'en'=>'Age visitor'),
        "LIST_FILTER_LABEL" => Array('ru'=>'Возраст посетителя', 'en'=>'Age visitor'), 
        "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
        "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
    ),
    'UF_LAST_NAME'=>Array(
        'ENTITY_ID' => $UFObject,
        'FIELD_NAME' => 'UF_LAST_NAME',
        'USER_TYPE_ID' => 'string',
        'MANDATORY' => 'Y',
        "EDIT_FORM_LABEL" => Array('ru'=>'Фамилия посетителя', 'en'=>'Last name visitor'), 
        "LIST_COLUMN_LABEL" => Array('ru'=>'Фамилия посетителя', 'en'=>'Last name visitor'),
        "LIST_FILTER_LABEL" => Array('ru'=>'Фамилия посетителя', 'en'=>'Last name visitor'), 
        "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
        "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
    ),
    'UF_DATE_BIRTH'=>Array(
        'ENTITY_ID' => $UFObject,
        'FIELD_NAME' => 'UF_DATE_BIRTH',
        'USER_TYPE_ID' => 'date',
        'MANDATORY' => '',
        "EDIT_FORM_LABEL" => Array('ru'=>'Дата рождения', 'en'=>'Date of birth'), 
        "LIST_COLUMN_LABEL" => Array('ru'=>'Дата рождения', 'en'=>'Date of birth'),
        "LIST_FILTER_LABEL" => Array('ru'=>'Дата рождения', 'en'=>'Date of birth'), 
        "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
        "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
    ),
);

$arSavedFieldsRes = Array();
foreach($arVisitorsFields as $arVisitorField){
    $obUserField  = new CUserTypeEntity;
    $ID = $obUserField->Add($arVisitorField);
    $arSavedFieldsRes[] = $ID;
}

echo CAdminMessage::ShowNote("Модуль HLTEST установлен");
}
?>