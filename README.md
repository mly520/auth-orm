#Auth-Orm
* [Website](http://anteater.ch)
* [Bugtracker](https://github.com/anteater/auth-orm/issues)
* [FuelPHP](https://github.com/fuel/)
* [Auth](https://github.com/fuel/auth)
* [Orm](https://github.com/fuel/orm)

##Description
Auth-Orm is a package for the FuelPHP-Framework. Basically it's just an auth-driver
which uses the Orm-Package as a persistance layer. It is written as a package to be completely update-save.

##Devs
* Jim Schmid [@sheeep](https://github.com/sheeep)
* Michael Kreis	[@m-kay](https://github.com/m-kay)

## Installation
Clone Auth-Orm into your package path.

    git clone git@github.com:anteater/auth-orm.git

Setup Fuel to use Auth-Orm.

    cp fuel/packages/auth-orm/config/auth.php fuel/app/config/auth.php

Now edit the configuration file in your app path by adding a salt, like:

    <?php

    return array(
	    'driver'	=> 'AuthOrm',
	    'salt'		=> 'Nope! Just Chuck Testa!',
    );

Setup Fuel to load the required packages by activating them in your app path located config. (file: config.php)

    'packages' => array(
    	'auth',
    	'orm',
    	'auth-orm'
    ),

Setup your models, but be aware: As models are not that flexible to handle like - lets say - simple databases: here are the limitations: You need six different tables and three different models if you plan to implement the whole story, including users, groups and ACL handling. A sample of a database installation script and the relating models can be found in the help folder in the root path of the package. All the field names of the models (like say: username or group_id) can be customized in the authorm-config.

Create a configuration file in your app path and update some values if you need. (Or copy the one from the package path)

    cp fuel/packages/auth-orm/config/authorm.php fuel/app/config/authorm.php

Party!

	$auth = Auth::instance();
	
	if(!$auth->login('test', 'tester'))
	{
		Response::redirect('login');
	}
	
	if($auth->has_access('welcome.read'))
	{
		// congratulation, you have access!
	}
	
	$auth->logout();

## Help or Fail!
Write an issue in our [Bugtracker](https://github.com/anteater/auth-orm/issues), drop us a message or ask on [Twitter](https://twitter.com/anteaterBT).

## Thanks!
No worries. 
