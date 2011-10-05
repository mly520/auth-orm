<?php

namespace AuthOrm;

class Auth_Group_AuthOrm extends \Auth_Group_Driver
{
	/**
	 * Check membership of given users
	 *
	 * @param	mixed	condition to check for access
	 * @param	array	user identifier in the form of array(driver_id, user_id), or null for logged in
	 * @return	bool
	 */
	 public function member($group, $user = null)
	 {
	 
	 }

	/**
	 * Fetch the display name of the given group
	 *
	 * @param	mixed	group condition to check
	 * @return	string
	 */
	 public function get_name($group)
	 {
	 
	 }
}
