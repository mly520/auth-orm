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
	
	}
}
