<?php

namespace Auth;

class Auth_Acl_AuthOrm extends \Auth_Acl_Driver
{	
	/**
	 * Check access rights
	 *
	 * @param	mixed	condition to check for access
	 * @param	mixed	user or group identifier in the form of array(driver_id, id)
	 * @return	bool
	 */
	public function has_access($condition, Array $entity)
	{
		$granted = true;
		$conditions = static::_parse_conditions($condition);
		
		// ... sure, nice
		$area   = $conditions[0];
		$rights = is_array($conditions[1]) ? $conditions[1] : array($conditions[1]);
		
		// get config vars
		$userModel				= \Config::get('authorm.model.user');
		$userGroupField			= \Config::get('authorm.fields.user.groups');
		$userPermissionField	= \Config::get('authorm.fields.user.permissions');
		$groupPermissionField	= \Config::get('authorm.fields.group.permissions');
		$permissionModel		= \Config::get('authorm.model.permission');
		$permissionArea			= \Config::get('authorm.fields.permission.area');
		$permissionRight		= \Config::get('authorm.fields.permission.right');
		$permissionGrantedField	= \Config::get('authorm.fields.permission.granted');
		
		// get user
		$user = $userModel::find($entity[1]);
		
		// get groups
		$groups = $user->{$userGroupField};
		
		foreach($rights as $right)
		{
			$rightGranted = false;
			$groupGranted = false;
			$userGranted  = false;
			
			// group permissions
			foreach($groups as $group)
			{	
				$prm = $group::query()->related($groupPermissionField, array
				(
					'where' => array(
						array($permissionArea, $area),
						array($permissionRight, $right),
						array($permissionGrantedField, true)
					),
					'sort_by' => array($groupPermissionField . '.' . $permissionGrantedField, 'desc'),
				))->get_one();
				
				$permissions = $group->{$groupPermissionField};
				
				if(!is_null($prm) && count($permissions) >= 1)
				{
					$groupGranted = $groupGranted || (reset($permissions)->{$permissionGrantedField} === false ? false : true);
				}
			}
			
			// user permissions
			$prm = $user::query()->related($userPermissionField, array
			(
				'where' => array(
					array($permissionArea, $area),
					array($permissionRight, $right),
					array($permissionGrantedField, true)
				),
				'sort_by' => array($userPermissionField . '.' . $permissionGrantedField, 'desc'),
			))->get_one();
			
			$permissions = $user->{$userPermissionField};
						
			if(!is_null($prm) && count($permissions) >= 1)
			{
				$userGranted = $userGranted || ((bool)reset($permissions)->{$permissionGrantedField} === false ? false : true);
			}
			elseif($groupGranted)
			{
				// because the group has the permission, but it can't be found
				// in the userrights -> the user has groupaccess.
				$userGranted = true;
			}
			
			$rightGranted = $userGranted || ($userGranted && $groupGranted);
			
			$granted = $granted && $rightGranted;
		}
		
		return $granted;
	}
}
