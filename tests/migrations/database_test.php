<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\karma\tests\migrations;

class database_test extends \phpbb_database_test_case
{
	protected $db_tools;
	protected $table_prefix;

	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/add_database_changes.xml');
	}

	public function setUp(): void
	{
		parent::setUp();

		global $table_prefix;
		$this->table_prefix = $table_prefix;

		$db = $this->new_dbal();
		$this->db_tools = new \phpbb\db\tools($db);
	}

	public function test_karma_votes_table_exists()
	{
		$this->assertTrue(
			$this->db_tools->sql_table_exists($this->table_prefix . 'vinny_karma_votes'),
			'Asserting that vinny_karma_votes table exists'
		);
	}

	public function test_post_karma_column_exists()
	{
		$this->assertTrue(
			$this->db_tools->sql_column_exists($this->table_prefix . 'posts', 'post_karma'),
			'Asserting that post_karma column exists in posts table'
		);
	}

	public function test_user_karma_column_exists()
	{
		$this->assertTrue(
			$this->db_tools->sql_column_exists($this->table_prefix . 'users', 'user_karma'),
			'Asserting that user_karma column exists in users table'
		);
	}
}
