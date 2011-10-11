<?php

namespace Auth;

class Auth_Login_AuthOrm extends \Auth_Login_Driver
{
	protected $user = null;
	
	protected $config = array
	(
		'drivers' => array('group' => array('AuthOrm')),
	);

	public static function _init()
	{
		\Config::load('authorm', true);
	}
	
	/**
	 * Perform the actual login check
	 *
	 * @return  bool
	 */
	public function perform_check()
	{
		$userModel = \Config::get('authorm.model.user');
		$user = $this->user;
		
		if($user instanceof $userModel)
		{
			return true;
		}
		
		\Session::delete('user');
			
		// check failed
		return false;
	}

	/**
	 * Login method
	 *
	 * @return  bool  whether login succeeded
	 */
	public function login($user = '', $pass = '')
	{
		$user = trim($user);
		$pass = trim($pass);
		
		$userModel = \Config::get('authorm.model.user');
		
		$userFieldUser = \Config::get('authorm.fields.user.username');
		$userFieldPass = \Config::get('authorm.fields.user.password');
		
		$hashingAlgorithm = \Config::get('authorm.hashing_algorithm');
		
		if(!count($userFieldUser))			return false;
		if(strlen($userFieldPass) <= 0)		return false;
		
		// start search		
		foreach($userFieldUser as $userfield)
		{
			$userObj = $userModel::find()->where($userfield, $user)->where($userFieldPass, $this->hash_password($pass))->get_one();
						
			if($userObj) break;
		}
		
		if($userObj instanceof $userModel)
		{
			// store to session
			\Session::set('user', $userObj);
			$this->user = $userObj;
			
			return true;
		}
		
		return false;
	}

	/**
	 * Logout method
	 */
	public function logout()
	{
		// you have to unregister the verified
		// diver by hand, otherwise you won't be able
		// to logout properly!
		\Auth::_unregister_verified($this);
		
		// resetting user
		$this->user = null;
		\Session::delete('user');
		
		return true;
	}

	/**
	 * Get User Identifier of the current logged in user
	 * in the form: array(driver_id, user_id)
	 *
	 * @return  array
	 */
	public function get_user_id()
	{
		if($this->perform_check())
		{
			$id = \Config::get('authorm.fields.user.id');
			
			return array($this->id, $this->user->{$id});
		}
		
		return false;
	}
	
	public function get_user()
	{
		if($this->perform_check())
		{
			return $this->user;
		}
		
		return null;
	}

	/**
	 * Get User Groups of the current logged in user
	 * in the form: array(array(driver_id, group_id), array(driver_id, group_id), etc)
	 *
	 * @return  array
	 */
	public function get_groups()
	{
		if($this->perform_check())
		{
			$user = $this->user;
			
			$group_key = \Config::get('authorm.fields.user.groups');
			$group_id = \Config::get('authorm.fields.group.id');
			
			$groups = $user->{$group_key};
			
			$return = array();
			
			if(count($groups) > 0)
			{
				foreach($groups as $group)
				{
					$tmp = array($this->id, $group->{$group_id});
					
					$return[] = $tmp;
				}
			}
			
			return $return;
		}
		
		return false;
	}

	/**
	 * Get emailaddress of the current logged in user
	 *
	 * @return  string
	 */
	public function get_email()
	{
		if($this->perform_check())
		{
			$mail = \Config::get('authorm.fields.user.email');
			
			return $this->user->{$mail};
						
		}
		
		return false;
	}

	/**
	 * Get screen name of the current logged in user
	 *
	 * @return  string
	 */
	public function get_screen_name()
	{
		if($this->perform_check())
		{
			$fields = \Config::get('authorm.fields.user.screenname');
			
			$name = '';
			
			foreach($fields as $field)
			{
				$name .= $this->user->{$field} . ' ';
			}
			
			return trim($name);
						
		}
		
		return false;
	}
}
