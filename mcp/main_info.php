<?php
/**
*
* @package karma
* @copyright (c) 2026 Vinny
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace vinny\karma\mcp;

/**
* phpBB Karma Extension MCP Info class
*/
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\vinny\karma\mcp\main_module',
			'title'		=> 'MCP_KARMA',
			'modes'		=> array(
				'karma_user'			=> array('title' => 'MCP_KARMA_USER', 'auth' => 'acl_m_karma_manage', 'cat' => array('MCP_KARMA')),
				'karma_user_details'	=> array('title' => 'MCP_KARMA_USER_DETAILS', 'auth' => 'acl_m_karma_manage', 'cat' => array('MCP_KARMA')),
			),
		);
	}
}
