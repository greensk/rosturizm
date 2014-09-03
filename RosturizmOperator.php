<?php
/**
 * Класс описания данных о туроператоре
 * 
 */
class RosturizmOperator
{
	
	/**
	 * Объект Rosturizm, через который работаем
	 * @var Rosturizm
	 */
	protected $rosturizm;
	
	/**
	 * Ссылка на страницу на сайта реестра, где расположена информация
	 * об операторе
	 * @var string
	 */
	protected $href;
	
	/**
	 * Полное наименование туроператора
	 * @var string
	 */
	public $full_name;
	
	/**
	 * Сокращенное наименование туропертора
	 * @var string
	 */
	public $short_name;
	
	/**
	 * Названия полей, получаемых из реестра. Ассоциативный массив
	 * вида Название поля => Русское название на сайте Ростуризма
	 * 
	 * @var array
	 */
	protected $fields = array(
		'short_name' => 'Полное наименование:',
		'full_name' => 'Сокращенное наименование:',
		'address' => 'Адрес (место нахождения):',
		'address_postal' => 'Почтовый адрес:',
		'site' => 'Адрес официального сайта в сети "Интернет":',
		'tax_number' => 'ИНН:',
		'reg_number' => 'ОГРН:',
		
	);
	
	/**
	 * Для инициализации передаем объект Rosturizm, наименование
	 * туропертора и ссылка — данные получаемые при поиске
	 * 
	 * @param Rosturizm $rosturizm
	 * @param $full_name string
	 * @param $href string Ссылка на данные текущего оператора на сайте
	 * реестра
	 */
	public function __construct($rosturizm, $href, $full_name = null)
	{
		$this->rosturizm = $rosturizm;
		$this->href = $href;
		if ($full_name)
			$this->full_name = $full_name;
	}
	
	/**
	 * Загрузка полных данных с сайта реестра
	 * 
	 * @return void
	 */
	public function load()
	{
		$xpath = $this->rosturizm->getXPath($this->href);
		
		foreach ($this->fields as $id => $name) {
			$path = '//*[@class="b_inner__regis_item" and ' .
				'contains(string(), "' . $name . '")]' .
				'/following-sibling::*[@class="b_inner__regis_item"]';
			foreach ($xpath->query($path) as $element) {
			
				$this->$id = $element->nodeValue;
				break;
			}
		}
		
	}
}
