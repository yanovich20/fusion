<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\PageNavigation;
use \Bitrix\Main\Type\DateTime as DT;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;



$grid_id  = 'VISITORS_GRID';
$filter_id = "VISITORS_FILTER";

$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
    'FILTER_ID' => $filter_id,
    'GRID_ID' => $grid_id,
    'AJAX_MODE'=>'Y',
    'FILTER' => [
        ['id' => 'UF_NAME', 'name' => 'Имя посетителя', 'type'=>'text', 'default' => true],
        ['id' => 'UF_LAST_NAME', 'name' => 'Фамилия посетителя', 'type'=>'text', 'default' => true],        
        ['id' => 'UF_AGE', 'name' => 'Возраст посетителя', 'type'=>'number', 'default' => true], 
        ['id' => 'UF_DATE_VISIT', 'name' => 'Дата посещения', 'type' => 'list', 'items' => [
            '1' => '0-7 дней',
            '2' => '0-14 дней',
            '3' => '0-30 дней',
            '4' => '0-2 мес',
            '5' => '0-6 мес',
        ], 'params' => ['multiple' => 'N'], 'default' => true],
    ],
    'ENABLE_LIVE_SEARCH' => true,
    'ENABLE_LABEL' => true,
    'VALUE_REQUIRED_MODE' => true,
]);
$filterOption = new Bitrix\Main\UI\Filter\Options($filter_id);
$filterFields = $filterOption->getFilter([]);


foreach ($filterFields as $k => $v) {
    if($k == 'FIND' && $v)
        $filterData['UF_NAME'] = "%".$v."%";
    else
        $filterData[$k] = $v;
}

$mainFilter = [];

if($filterData['UF_NAME'])
    $mainFilter['UF_NAME'] = $filterData['UF_NAME'];


if($filterData['UF_LAST_NAME'])
    $mainFilter['UF_LAST_NAME'] = $filterData['UF_LAST_NAME'];

if($filterData['UF_AGE_from'])
    $mainFilter['>=UF_AGE'] = $filterData['UF_AGE_from'];

if($filterData['UF_AGE_to'])
    $mainFilter['<=UF_AGE'] = $filterData['UF_AGE_to'];

if($filterData['UF_DATE_VISIT'][0]){
    $format = "Y-m-d H:i:s";
    $today = date($format);
    $interval = 0;

    switch ($filterData['UF_DATE_VISIT'][0]){
        case '1':
            $interval = 7;
            break;
        case '2':
            $interval = 14;
            break;
        case '3':
            $interval = 30;
            break;
        case '4':
            $interval = 60;
            break;
        case '5':
            $interval = 180;
            break;
    }

    $data = (new \DateTime($today))->modify("-". $interval ." day");
    $mainFilter['>UF_DATE_VISIT'] = DT::createFromTimestamp(strtotime($data->format('Y-m-d')));
}

$grid_options = new GridOptions($grid_id);
$sort = $grid_options->GetSorting(['sort' => ['UF_DATE_VISIT' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$nav_params = $grid_options->GetNavParams();

$nav = new PageNavigation($grid_id);
$nav->allowAllRecords(true)
    ->setPageSize($nav_params['nPageSize'])
    ->initFromUri();
	
if ($nav->allRecordsShown())
    $nav_params = false;
else
    $nav_params['iNumPage'] = $nav->getCurrentPage();

$getListOptions = array(
    "select" => ['ID', "UF_DATE_VISIT", 'UF_NAME', 'UF_DATE_BIRTH',"UF_LAST_NAME","UF_AGE"],
    "order" => ["ID" => "DESC"],
    "filter" => $mainFilter,
    'limit' => $nav_params['nPageSize'],
    'offset' => $nav_params['iNumPage'] - 1,
    'runtime' => $runtimes ? $runtimes : '',
    'count_total' => true
);

Loader::includeModule("highloadblock");

$hlBlockByNameObj = HL\HighloadBlockTable::getList(array("filter"=>["=NAME"=>"VisitorSite"],"select"=>["ID"]))->fetch();
$hlBlockId = $hlBlockByNameObj["ID"];

$hlblock = HL\HighloadBlockTable::getById($hlBlockId)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$visitorsData = $entity_data_class::getList($getListOptions);

$visitorsCount = $visitorsData->getCount();
$visitorsData = $visitorsData->fetchAll();

$nav->setRecordCount($visitorsCount);

foreach($visitorsData as $k => $row) {
    $list[] = [
        'data' => [
            "ID" => $row['ID'],
            "DATE_VISIT" => $row['UF_DATE_VISIT'],
            "NAME" => $row['UF_NAME'],
            "LAST_NAME" => $row['UF_LAST_NAME'],
            "AGE" => $row['UF_AGE'],
            "DATE_BIRTH" => $row['UF_DATE_BIRTH'],
        ],
        'default_action' => [
            "href" => '/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$hlBlockId.'&type=content&lang=ru&ID='.$row["ID"].'&find_section_section=-1&WF=Y',
            "title" => 'Редактировать элемент',
        ],
        'actions' => [
            [
                'text' => 'Редактировать',
                'default' => true,
                'onclick' =>'window.location.href="/bitrix/admin/highloadblock_row_edit.php?ENTITY_ID='.$hlBlockId.'&ID='.$row["ID"].'"'
            ],
            [
                'delimiter' => true,
            ],
            [
                'text' => 'Удалить',
                'default' => true,
                'onclick' => "deleteSelectedRows([$row[ID]])"
            ],
        ]
    ];
}

$columns = [];
$columns[] = ['id' => 'DATE_VISIT', 'name' => 'Дата посещения сайта', 'sort' => 'DATE_VISIT', 'content' => 'Дата посещения сайта', 'title' => 'Дата посещения сайта', 'column_sort' => 100, 'default' => true];
$columns[] = ['id' => 'NAME', 'name' => 'Имя посетителя', 'sort' => 'NAME', 'content' => 'Имя посетителя', 'title' => 'Имя посетителя', 'column_sort' => 200, 'default' => true];
$columns[] = ['id' => 'AGE', 'name' => 'Возраст посетителя', 'sort' => 'AGE', 'content' => 'Возраст посетителя', 'title' => 'Возраст посетителя', 'column_sort' => 400, 'default' => true];
$columns[] = ['id' => 'DATE_BIRTH', 'name' => 'Дата рождения посетителя', 'sort' => 'DATE_BIRTH', 'content' => 'Дата рождения посетителя', 'title' => 'Дата рождения посетителя', 'column_sort' => 500, 'default' => true];
$columns[] = ['id' => 'LAST_NAME', 'name' => 'Фамилия посетителя', 'sort' => 'LAST_NAME', 'content' => 'Фамилия посетителя', 'title' => 'Фамилия посетителя', 'column_sort' => 300, 'default' => true];

$gridParams = [
    'GRID_ID' => $grid_id,
    'COLUMNS' => $columns,
    'ROWS' => $list,
    'FOOTER' => [
        'TOTAL_ROWS_COUNT' => $visitorsCount,
    ],
    'SHOW_ROW_CHECKBOXES' => true,
    'NAV_OBJECT' => $nav,
    'AJAX_MODE' => 'Y',
    'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
    'PAGE_SIZES' => [
        ['NAME' => "5", 'VALUE' => '5'],
        ['NAME' => '10', 'VALUE' => '10'],
        ['NAME' => '20', 'VALUE' => '20'],
        ['NAME' => '50', 'VALUE' => '50'],
        ['NAME' => '100', 'VALUE' => '100']
    ],
    'AJAX_OPTION_JUMP' => 'N',
    'SHOW_CHECK_ALL_CHECKBOXES' => false,
    'SHOW_ROW_ACTIONS_MENU' => true,
    'SHOW_GRID_SETTINGS_MENU' => true,
    'SHOW_NAVIGATION_PANEL' => true,
    'SHOW_PAGINATION' => true,
    'SHOW_SELECTED_COUNTER' => true,
    'SHOW_TOTAL_COUNTER' => true,
    'SHOW_PAGESIZE' => true,
    'SHOW_ACTION_PANEL' => true,
    'ALLOW_COLUMNS_SORT' => true,
    'ALLOW_COLUMNS_RESIZE' => true,
    'ALLOW_HORIZONTAL_SCROLL' => true,
    'ALLOW_SORT' => true,
    'ALLOW_PIN_HEADER' => true,
    'AJAX_OPTION_HISTORY' => 'N',
     'ACTION_PANEL'              => [
        'GROUPS' => [
            'TYPE' => [
                'ITEMS' => [
                    [
                        'ID'       => 'delete',
                        'TYPE'     => 'BUTTON',
                        'TEXT'     => 'Удалить',
                        'CLASS'    => 'icon remove',
                        'ONCHANGE' => [
                            [
                            "ACTION" =>  \Bitrix\Main\Grid\Panel\Actions::CALLBACK,
                            'DATA' => [
		                            ['JS' => 'deleteSelectedRows()'],
                                ]
                            ]
                        ],
                    ],
                ],
            ]
        ],
    ],
];

$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);

?>