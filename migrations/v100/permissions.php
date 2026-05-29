<?php
/**
*
* @package karma
* @copyright (c) 2026 Vinny
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace vinny\karma\migrations\v100;

/**
* phpBB Karma Extension Permissions Migration
*/
class permissions extends \phpbb\db\migration\migration
{
	/**
	* Define migration dependencies
	*
	* @return array
	*/
	static public function depends_on()
	{
		return array('\vinny\karma\migrations\v100\config');
	}

	/**
	* Check if migration is effectively installed
	*
	* @return bool
	*/
	public function effectively_installed()
	{
		$sql = 'SELECT auth_option_id
			FROM ' . ACL_OPTIONS_TABLE . "
			WHERE auth_option = 'u_karma_view'";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return (bool) $row;
	}

	/**
	* Add permissions and assign to groups
	*
	* @return array
	*/
	public function update_data()
	{
		return array(
			array('permission.add', array('u_karma_view', true)),
			array('permission.add', array('u_karma_vote', true)),
			array('permission.add', array('u_karma_ranking', true)),

			// Set permissions to Yes for ADMINISTRATORS, GLOBAL_MODERATORS, and REGISTERED groups
			array('permission.permission_set', array('ADMINISTRATORS', 'u_karma_view', 'group', true)),
			array('permission.permission_set', array('ADMINISTRATORS', 'u_karma_vote', 'group', true)),
			array('permission.permission_set', array('ADMINISTRATORS', 'u_karma_ranking', 'group', true)),

			array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_karma_view', 'group', true)),
			array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_karma_vote', 'group', true)),
			array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_karma_ranking', 'group', true)),

			array('permission.permission_set', array('REGISTERED', 'u_karma_view', 'group', true)),
			array('permission.permission_set', array('REGISTERED', 'u_karma_vote', 'group', true)),
			array('permission.permission_set', array('REGISTERED', 'u_karma_ranking', 'group', true)),
		);
	}

	/**
	* Remove permissions from database
	*
	* @return array
	*/
	public function revert_data()
	{
		return array(
			array('permission.remove', array('u_karma_view')),
			array('permission.remove', array('u_karma_vote')),
			array('permission.remove', array('u_karma_ranking')),
		);
	}
}
