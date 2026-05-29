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
		);
	}
}
