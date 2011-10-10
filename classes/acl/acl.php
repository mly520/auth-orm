<?php

namespace Auth;

class Auth_Acl_AuthOrm extends \Auth_Acl_Driver
{
	public static function _init()
	{
	}
	
	/**
	 * Check access rights
	 *
	 * @param	mixed	condition to check for access
	 * @param	mixed	user or group identifier in the form of array(driver_id, id)
	 * @return	bool
	 */
	public function has_access($condition, Array $entity)
	{
		$granted = false;
		$conditions = static::_parse_conditions($condition);
		
		// ... sure, nice
		$area   = $conditions[0];
		$rights = is_array($conditions[1]) ? $conditions[1] : array($conditions[1]);
		
		// get config vars
		$userModel			= \Config::get('authorm.model.user');
		$permissionModel	= \Config::get('authorm.model.permission');
		$permissionArea		= \Config::get('authorm.fields.permission.area');
		$permissionRight	= \Config::get('authorm.fields.permission.right');
		
		// get user
		$user = $userModel::find($entity[1]);
		
		foreach($rights as $right)
		{
			$permission = $permissionModel::find()->where($permissionArea, $area)->where($permissionRight, $right)->get_one();
			
			// user permissions			
			if(in_array($permission, $user->permissions))
			{
				if($permission->granted) $granted = true;
			}
		}
		
		return $granted;
	}
}
