<?php

// add classes
Autoloader::add_classes(array
(
	'Auth\\Auth_Acl_AuthOrm'		=> __DIR__ . '/classes/acl/acl.php',
	'Auth\\Auth_Group_AuthOrm'		=> __DIR__ . '/classes/group/group.php',
	'Auth\\Auth_Login_AuthOrm'		=> __DIR__ . '/classes/login/login.php',
));


?>
