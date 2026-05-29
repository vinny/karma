<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
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
