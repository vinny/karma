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
* phpBB Karma Extension Configuration Migration
*/
class config extends \phpbb\db\migration\migration
{
	/**
	* Define migration dependencies
	*
	* @return array
	*/
	static public function depends_on()
	{
		return array('\vinny\karma\migrations\v100\schema');
	}

	/**
	* Check if migration is effectively installed
	*
	* @return bool
	*/
	public function effectively_installed()
	{
		return isset($this->config['vinny_karma_enabled']);
	}

	/**
	* Add configuration settings
	*
	* @return array
	*/
	public function update_data()
	{
		return array(
			array('config.add', array('vinny_karma_enabled', 1)),
			array('config.add', array('vinny_karma_enable_downvote', 1)),
			array('config.add', array('vinny_karma_flood_interval', 10)),
			array('config.add', array('vinny_karma_excluded_forums', '')),
		);
	}

	/**
	* Remove configuration settings
	*
	* @return array
	*/
	public function revert_data()
	{
		return array(
			array('config.remove', array('vinny_karma_enabled')),
			array('config.remove', array('vinny_karma_enable_downvote')),
			array('config.remove', array('vinny_karma_flood_interval')),
			array('config.remove', array('vinny_karma_excluded_forums')),
		);
	}
}
