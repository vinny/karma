<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
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
