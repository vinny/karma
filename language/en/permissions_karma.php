<?php
/**
*
* @package karma
* @copyright (c) 2026 Vinny
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACL_CAT_KARMA_SYSTEM'		=> 'Karma System',
	'ACL_U_KARMA_VIEW'			=> 'Can view karma system scores',
	'ACL_U_KARMA_VOTE'			=> 'Can vote on posts',
	'ACL_U_KARMA_RANKING'		=> 'Can view karma ranking page',
	'ACL_M_KARMA_MANAGE'		=> 'Can moderate user karma',
));
