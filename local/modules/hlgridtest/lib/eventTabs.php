<?php
namespace HLGridTest;
class EventTabs{
	public static function myOnEntityDetailsTabsInitialized($event) {
		$tabs = $event->getParameter('tabs');
		// ID текущего элемента СРМ 
		$entityID = $event->getParameter('entityID');
		// ID типа сущности: Сделка, Компания, Контакт и т.д.
		$entityTypeID = $event->getParameter('entityTypeID');
		// Проверяем, что открыта карточка именно Сделки
		if($entityTypeID == \CCrmOwnerType::Deal) {
			// Добавляем свою вкладку в массив вкладок
			$tabs[] = [
				'id' => 'newTab',
				'name' => 'Посетители сайта',
				//'html'=>'<div>hello sky world!</div>'
				'loader' => [
					// Указываем URL адрес обработчика
					'serviceUrl' => '/local/modules/hlgridtest/lib/grid.php',
					'componentData' => [
						'template' => '',
						// Передаем массив необходимых параметров
						'params' => [
							'ENTITY_ID' => $entityID,
							'ENTITY_TYPE' => $entityTypeID,
							'TAB_ID' => 'newTab'
						]
					]
				]
			];
		}
		
		// Возвращаем модифицированный массив вкладок
		return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, [
			'tabs' => $tabs,
		]);
	}
}