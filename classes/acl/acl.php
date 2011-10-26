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
			
			// some shortcuts
			$gpf = $groupPermissionField;
			$upf = $userPermissionField;
			$pfa = $permissionArea;
			$pfr = $permissionRight;
			$pgf = $permissionGrantedField;
			
			// chained shortcuts
			$cfa = sprintf("%s.%s", $gpf, $pfa);
			$cfr = sprintf("%s.%s", $gpf, $pfr);
			$cfg = sprintf("%s.%s", $gpf, $pgf);
			
			// group permissions
			foreach($groups as $group)
			{				
				$prm =	$group::query()->related($gpf)
						
						// conditions
						->where($cfa, $area)
						->where($cfr, $right)
						->where($cfg, true)
						
						// sorting
						->order_by($cfg, 'desc')
						
						// fetch
						->get_one();
				
				$permissions = $group->{$gpf};
				
				if(!is_null($prm) && count($permissions) >= 1)
				{
					$groupGranted = $groupGranted || (reset($permissions)->{$pgf} === false ? false : true);
				}
			}
			
			// overwrite chained shortcuts
			$cfa = sprintf("%s.%s", $upf, $pfa);
			$cfr = sprintf("%s.%s", $upf, $pfr);
			$cfg = sprintf("%s.%s", $upf, $pgf);
			
			// user permissions
			$prm =	$user::query()->related($upf)
			
				// conditions
				->where($cfa, $area)
				->where($cfr, $right)
				->where($cfg, true)
				
				// sorting
				->order_by($cfg, 'desc')
				
				// fetch
				->get_one();
			
			$permissions = $user->{$upf};
						
			if(!is_null($prm) && count($permissions) >= 1)
			{
				$userGranted = $userGranted || ((bool)reset($permissions)->{$pgf} === false ? false : true);
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
