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
* phpBB Karma Extension Report Reason Migration
*/
class report_reason extends \phpbb\db\migration\migration
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
		$sql = 'SELECT reason_id FROM ' . $this->table_prefix . "reports_reasons WHERE reason_title = 'ABUSE_KARMA'";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return (bool) $row;
	}

	/**
	* Add report reason
	*
	* @return array
	*/
	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'insert_report_reason'))),
		);
	}

	/**
	* Remove report reason
	*
	* @return array
	*/
	public function revert_data()
	{
		return array(
			array('custom', array(array($this, 'delete_report_reason'))),
		);
	}

	/**
	* Insert report reason into DB
	*/
	public function insert_report_reason()
	{
		$sql = 'SELECT MAX(reason_order) as max_order FROM ' . $this->table_prefix . 'reports_reasons';
		$result = $this->db->sql_query($sql);
		$max_order = (int) $this->db->sql_fetchfield('max_order');
		$this->db->sql_freeresult($result);

		$sql_ary = array(
			'reason_title'			=> 'ABUSE_KARMA',
			'reason_description'	=> '',
			'reason_order'			=> $max_order + 1,
		);

		$this->db->sql_query('INSERT INTO ' . $this->table_prefix . 'reports_reasons ' . $this->db->sql_build_array('INSERT', $sql_ary));
	}

	/**
	* Delete report reason from DB
	*/
	public function delete_report_reason()
	{
		$sql = 'DELETE FROM ' . $this->table_prefix . "reports_reasons WHERE reason_title = 'ABUSE_KARMA'";
		$this->db->sql_query($sql);
	}
}
