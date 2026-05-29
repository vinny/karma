<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\karma\migrations\v100;

/**
* phpBB Karma Extension ACP Module Migration
*/
class acp_module extends \phpbb\db\migration\migration
{
	/**
	* Define migration dependencies
	*
	* @return array
	*/
	static public function depends_on()
	{
		return array('\vinny\karma\migrations\v100\permissions');
	}

	/**
	* Check if migration is effectively installed
	*
	* @return bool
	*/
	public function effectively_installed()
	{
		$sql = 'SELECT module_id
			FROM ' . MODULES_TABLE . "
			WHERE module_langname = 'ACP_VINNY_KARMA'";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return (bool) $row;
	}

	/**
	* Add configs and register ACP module
	*
	* @return array
	*/
	public function update_data()
	{
		return array(
			// Add main module category under extensions tab
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_VINNY_KARMA'
			)),

			// Add settings and maintenance modes to the module category
			array('module.add', array(
				'acp',
				'ACP_VINNY_KARMA',
				array(
					'module_basename'	=> '\vinny\karma\acp\main_module',
					'modes'				=> array('settings', 'maintenance'),
				),
			)),
		);
	}

	/**
	* Remove configs and ACP module
	*
	* @return array
	*/
	public function revert_data()
	{
		return array(
			array('module.remove', array(
				'acp',
				'ACP_VINNY_KARMA',
				array(
					'module_basename'	=> '\vinny\karma\acp\main_module',
					'modes'				=> array('settings', 'maintenance'),
				),
			)),

			array('module.remove', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_VINNY_KARMA'
			)),
		);
	}
}
