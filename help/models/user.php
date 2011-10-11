<?php
class Model_User extends \Orm\Model
{
	protected static $_table_name = 'users';
	protected static $_primary_key = array('id');
	
	protected static $_properties = array (
	
		'id'		=> array('data_type' => 'int'),
		'username'	=> array('data_type' => 'varchar'),
		'password'	=> array('data_type' => 'varchar'),
		'email'		=> array('data_type' => 'varchar'),
		'name'		=> array('data_type' => 'varchar'),
	
	);

	protected static $_many_many = array (
		
		'groups'		=> array
		(
			'key_from'			=> 'id',
			'key_through_from'	=> 'user_id',
			'table_through'		=> 'map_user_group',
			'key_through_to'	=> 'group_id',
			'model_to'			=> 'Model_Group',
			'key_to'			=> 'id',
			'cascade_save'		=> true,
			'cascade_delete'	=> false,
		),
		
		'permissions'	=> array
		(
			'key_from'			=> 'id',
			'key_through_from'	=> 'user_id',
			'table_through'		=> 'map_user_permission',
			'key_through_to'	=> 'permission_id',
			'model_to'			=> 'Model_Permission',
			'key_to'			=> 'id',
			'cascade_save'		=> true,
			'cascade_delete'	=> false
		),
	);
}
?>
