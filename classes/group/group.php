<?php

namespace Auth;

class Auth_Group_AuthOrm extends \Auth_Group_Driver
{
	protected $config = array
	(
		'drivers' => array('acl' => array('AuthOrm'))
	);
	
	/**
	 * Check membership of given users
	 *
	 * @param	mixed	condition to check for access
	 * @param	array	user identifier in the form of array(driver_id, user_id), or null for logged in
	 * @return	bool
	 */
	 public function member($group, $user = null)
	 {
		$groups = \Auth::instance()->get_groups();
					
		// get config values
		$groupModel		= \Config::get('authorm.model.group');
		$groupUserKey	= \Config::get('authorm.fields.group.users');
		$groupIdKey		= \Config::get('authorm.fields.group.id');
		
		if($group instanceof $groupModel)
		{
			// get id
			$id = $group->{$groupIdKey};
			
			return in_array(array($this->id, $id), $groups);
		}
		else
		{
			return in_array(array($this->id, $group), $groups);
		}
	 }

	/**
	 * Fetch the display name of the given group
	 *
	 * @param	mixed	group condition to check
	 * @return	string
	 */
	public function get_name($group)
	{
		$groupModel		= \Config::get('authorm.model.group');
		$groupNameKey	= \Config::get('authorm.fields.group.name');
		
		if($group instanceof $groupModel)
		{
			$name = $groupModel->{$groupNameKey};
		}
		else
		{
			$name = $groupModel->find($group)->get_one()->{$groupNameKey};
		}
		
		return $name;
	}
}
