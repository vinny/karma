<?php
/**
*
* @package karma
* @copyright (c) 2026 Vinny
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace vinny\karma\acp;

/**
* phpBB Karma Extension ACP Info class
*/
class main_info
{
	/**
	* Define ACP module modes and properties
	*
	* @return array
	*/
	public function module()
	{
		return array(
			'filename'	=> '\vinny\karma\acp\main_module',
			'title'		=> 'ACP_VINNY_KARMA',
			'modes'		=> array(
				'settings'		=> array(
					'title'	=> 'ACP_VINNY_KARMA_SETTINGS',
					'auth'	=> 'ext_vinny/karma && acl_a_board',
					'cat'	=> array('ACP_VINNY_KARMA'),
				),
				'maintenance'	=> array(
					'title'	=> 'ACP_VINNY_KARMA_MAINTENANCE',
					'auth'	=> 'ext_vinny/karma && acl_a_board',
					'cat'	=> array('ACP_VINNY_KARMA'),
				),
			),
		);
	}
}
