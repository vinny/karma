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
		return isset($this->config['vinny_karma_enable_downvote']);
	}

	/**
	* Add configs and register ACP module
	*
	* @return array
	*/
	public function update_data()
	{
		return array(
			array('config.add', array('vinny_karma_enable_downvote', 1)),
			array('config.add', array('vinny_karma_flood_interval', 10)),
			array('config.add', array('vinny_karma_excluded_forums', '')),

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
			array('config.remove', array('vinny_karma_enable_downvote')),
			array('config.remove', array('vinny_karma_flood_interval')),
			array('config.remove', array('vinny_karma_excluded_forums')),

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
