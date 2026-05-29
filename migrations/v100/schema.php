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
* phpBB Karma Extension Columns Schema Migration
*/
class schema extends \phpbb\db\migration\migration
{
	/**
	* Define migration dependencies
	*
	* @return array
	*/
	static public function depends_on()
	{
		return array('\vinny\karma\migrations\v100\tables');
	}

	/**
	* Check if migration is effectively installed
	*
	* @return bool
	*/
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'posts', 'post_karma');
	}

	/**
	* Add columns to schema
	*
	* @return array
	*/
	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'posts' => array(
					'post_karma' => array('INT', 0),
				),
				$this->table_prefix . 'users' => array(
					'user_karma' => array('INT', 0),
				),
			),
		);
	}

	/**
	* Drop columns from schema
	*
	* @return array
	*/
	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'posts' => array(
					'post_karma',
				),
				$this->table_prefix . 'users' => array(
					'user_karma',
				),
			),
		);
	}
}
