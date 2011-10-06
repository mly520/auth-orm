<?php

return array
(
	'model' => array
	(
		'user'	=> 'Model_User',
		'group'	=> 'Model_Group'
	),
	
	'fields' => array
	(
		'user'	=> array
		(
			'screenname'	=> array('name'),
			'username'		=> array('username'),
			'password'		=> 'password',
			'groups'		=> 'groups',
			'email'			=> 'email',
			'id'			=> 'id',
		),
		
		'group'	=> array
		(
			'id'		=> 'id',
			'users'		=> 'users',
			'name'		=> 'name'
		)
	)
);
