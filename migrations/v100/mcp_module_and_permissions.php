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
* phpBB Karma Extension MCP Module and Permissions Migration
*/
class mcp_module_and_permissions extends \phpbb\db\migration\migration
{
	/**
	* Define migration dependencies
	*
	* @return array
	*/
	static public function depends_on()
	{
		return array('\vinny\karma\migrations\v100\acp_module');
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
			WHERE auth_option = 'm_karma_manage'";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return (bool) $row;
	}

	/**
	* Add permissions and register MCP module
	*
	* @return array
	*/
	public function update_data()
	{
		return array(
			// Register moderator permission
			array('permission.add', array('m_karma_manage', true)),

			// Set permission to Yes for ADMINISTRATORS and GLOBAL_MODERATORS
			array('permission.permission_set', array('ADMINISTRATORS', 'm_karma_manage', 'group', true)),
			array('permission.permission_set', array('GLOBAL_MODERATORS', 'm_karma_manage', 'group', true)),

			// Add main module category (MCP Tab)
			array('module.add', array(
				'mcp',
				0, // Tab level
				'MCP_KARMA'
			)),

			// Add modes to the module category
			array('module.add', array(
				'mcp',
				'MCP_KARMA',
				array(
					'module_basename'	=> '\vinny\karma\mcp\main_module',
					'modes'				=> array('karma_user', 'karma_user_details'),
				),
			)),
		);
	}

	/**
	* Remove permissions and MCP module
	*
	* @return array
	*/
	public function revert_data()
	{
		return array(
			array('permission.remove', array('m_karma_manage')),

			array('module.remove', array(
				'mcp',
				'MCP_KARMA',
				array(
					'module_basename'	=> '\vinny\karma\mcp\main_module',
					'modes'				=> array('karma_user', 'karma_user_details'),
				),
			)),

			array('module.remove', array(
				'mcp',
				0,
				'MCP_KARMA'
			)),
		);
	}
}
