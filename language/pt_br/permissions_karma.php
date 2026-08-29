<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) Vinny <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
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
	'ACL_CAT_KARMA_SYSTEM'		=> 'Sistema de Karma',
	'ACL_U_KARMA_VIEW'			=> 'Pode ver pontuações do sistema de karma',
	'ACL_U_KARMA_VOTE'			=> 'Pode votar em mensagens',
	'ACL_U_KARMA_RANKING'		=> 'Pode ver a página de classificação de karma',
	'ACL_M_KARMA_MANAGE'		=> 'Pode moderar karma de usuários',
));
