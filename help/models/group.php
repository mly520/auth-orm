<?php
class Model_Group extends \Orm\Model
{
	protected static $_table_name = 'group';
	protected static $_primary_key = array('id');
	
	protected static $_properties = array (
	
		'id'			=> array('data_type' => 'int'),
		'name'			=> array('data_type' => 'varchar'),
	);

	protected static $_many_many = array (
		
		'users'		=> array(
			'key_from'			=> 'id',
			'key_through_from'	=> 'group_id',
			'table_through'		=> 'map_user_group',
			'key_through_to'	=> 'user_id',
			'model_to'			=> 'Model_User',
			'key_to'			=> 'id',
			'cascade_save'		=> true,
			'cascade_delete'	=> false,
		),
		
		'permissions'	=> array
		(
			'key_from'			=> 'id',
			'key_through_from'	=> 'group_id',
			'table_through'		=> 'map_group_permission',
			'key_through_to'	=> 'permission_id',
			'model_to'			=> 'Model_Permission',
			'key_to'			=> 'id',
			'cascade_save'		=> true,
			'cascade_delete'	=> false
		),
	);
}
?>
