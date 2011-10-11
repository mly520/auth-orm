<?php
class Model_Permission extends \Orm\Model
{
	protected static $_table_name = 'permissions';
	protected static $_primary_key = array('id');
	
	protected static $_properties = array (
	
		'id'		=> array('data_type' => 'int'),
		'area'		=> array('data_type' => 'varchar'),
		'right'		=> array('data_type' => 'varchar'),
		'granted'	=> array('data_type' => 'bool')
	);
	
	protected static $_observers = array(
		'Orm\\Observer_Typing' => array('after_load')
	);
}
?>
