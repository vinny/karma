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
* phpBB Karma Extension Tables Migration
*/
class tables extends \phpbb\db\migration\migration
{
	/**
	* Define migration dependencies
	*
	* @return array
	*/
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v330\v330');
	}

	/**
	* Check if migration is effectively installed
	*
	* @return bool
	*/
	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'vinny_karma_votes');
	}

	/**
	* Add tables to schema
	*
	* @return array
	*/
	public function update_schema()
	{
		return array(
			'add_tables' => array(
				$this->table_prefix . 'vinny_karma_votes' => array(
					'COLUMNS' => array(
						'vote_id' => array('UINT', null, 'auto_increment'),
						'post_id' => array('UINT', 0),
						'user_id' => array('UINT', 0),
						'vote_direction' => array('TINT:4', 0),
						'vote_time' => array('TIMESTAMP', 0),
					),
					'PRIMARY_KEY' => 'vote_id',
					'KEYS' => array(
						'post_user' => array('UNIQUE', array('post_id', 'user_id')),
					),
				),
			),
		);
	}

	/**
	* Drop tables from schema
	*
	* @return array
	*/
	public function revert_schema()
	{
		return array(
			'drop_tables' => array(
				$this->table_prefix . 'vinny_karma_votes',
			),
		);
	}
}
