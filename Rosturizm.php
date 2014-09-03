<?php
/**
 * Общий класс для работы с реестром Ростуризма
 * 
 */
class Rosturizm 
{
	/**
	 * Базовый адрес URL, по которому осуществляется доступ 
	 * к реестру туроператоров
	 * @var string
	 */
	public $baseUrl = 'http://reestr.russiatourism.ru/';
	
	/**
	 * Поиск туроператора по названию
	 * 
	 * @param string $name Фрагмент названия
	 * @return array Массив объектов класса Rosturizm (из данных есть
	 * только полное наименование и ссылка)
	 */
	public function searchByName($name)
	{
		$name = mb_convert_encoding($name, 'cp1251', 'UTF-8');
		$xpath = $this->getXPath(
			'?ac=search&mode=1&mode=1&fr_name=' . urlencode($name)
		);
		
		$items = $xpath->query(
			'//*[@class="b_inner__src_rslt_item"]/a'
		);
		$result = array();
		foreach($items as $item) {
			$result[] = new RosturizmOperator(
				$this,
				$item->getAttribute('href'),
				$item->nodeValue
			);
		}
		return $result;
		
	}
	
	/**
	 * Обертка для HTTP-запроса — если нужно, можно переопределить
	 * 
	 * @param string $url Адрес для запроса
	 * @return string
	 */
	public function get($url)
	{
		return file_get_contents("{$this->baseUrl}{$url}");
	}
	
	/**
	 * Получение XPath для HTML-документа по указанному адресу
	 * 
	 * @param string $url Адрес URL
	 * @return DOMXPath
	 */
	public function getXPath($url)
	{
		$content = $this->get($url);
		
		$document = new DOMDocument("1.0", "cp1251");
		$document->loadHTML($content, LIBXML_NOWARNING);
		
		return new DOMXPath($document);
	}
	
}
