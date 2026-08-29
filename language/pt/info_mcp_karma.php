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
	'MCP_KARMA'							=> 'Karma',
	'MCP_KARMA_USER'					=> 'Visão geral',
	'MCP_KARMA_USER_DETAILS'			=> 'Detalhes do utilizador',
	'ACL_M_KARMA_MANAGE'				=> 'Pode moderar karma de utilizadores',


	'VINNY_KARMA_MCP_RESET'				=> 'Repor',
	'VINNY_KARMA_MCP_RESET_RECEIVED'	=> 'Repor karma recebido',
	'VINNY_KARMA_MCP_RESET_CAST'		=> 'Repor karma enviado',
	'VINNY_KARMA_MCP_RESET_ACTIONS'			=> 'Ações de reposição',
	'VINNY_KARMA_MCP_RESET_ACTIONS_EXPLAIN'	=> 'Pode repor o karma recebido ou enviado por este utilizador. Estas ações são destrutivas e modificarão o histórico de votos.',
	'VINNY_KARMA_MCP_RESET_RECEIVED_EXPLAIN' => 'Elimina todos os votos efetuados por outros utilizadores nas mensagens deste utilizador, repondo as pontuações.',
	'VINNY_KARMA_MCP_RESET_CAST_EXPLAIN'	=> 'Elimina todos os votos efetuados por este utilizador em mensagens de outros utilizadores.',
	'VINNY_KARMA_MCP_ADJUST_BALANCE'	=> 'Ajustar saldo de karma',
	'VINNY_KARMA_MCP_ADJUST_BALANCE_EXPLAIN' => 'Pode adicionar ou subtrair pontos manualmente ao saldo total de karma do utilizador. Isto modificará diretamente a pontuação do perfil.',
	'VINNY_KARMA_MCP_ADJUST_AMOUNT'		=> 'Quantidade de ajuste',
	'VINNY_KARMA_MCP_ADJUST_AMOUNT_EXP'	=> 'Utilize números negativos para subtrair (ex: -5) ou positivos para adicionar (ex: 10).',
	'VINNY_KARMA_MCP_REASON'			=> 'Motivo da moderação',
	'VINNY_KARMA_MCP_REASON_EXP'		=> 'Forneça um motivo para o registo no log.',
	'VINNY_KARMA_MCP_VOTER'				=> 'Votante',
	'VINNY_KARMA_MCP_POST_AUTHOR'		=> 'Autor',
	'VINNY_KARMA_MCP_POST_ID'			=> 'Mensagem',
	'VINNY_KARMA_MCP_DIRECTION'			=> 'Voto',
	'VINNY_KARMA_MCP_TOTAL_VOTES'		=> 'Total de votos: %d',

	'VINNY_KARMA_MCP_CONFIRM_RESET_RECEIVED'	=> 'Tem a certeza de que deseja repor todo o karma recebido por %s? Isto eliminará todos os registos de votos nas suas mensagens e recalculará a sua pontuação total.',
	'VINNY_KARMA_MCP_CONFIRM_RESET_CAST'		=> 'Tem a certeza de que deseja repor todos os votos efetuados por %s? Isto eliminará todos os votos que este utilizador realizou noutras mensagens.',
	'VINNY_KARMA_MCP_CONFIRM_ADJUST'			=> 'Tem a certeza de que deseja ajustar o saldo de karma de %s em %d pontos?',

	'VINNY_KARMA_MCP_RESET_RECEIVED_SUCCESS'	=> 'Todo o karma recebido por %s foi reposto com sucesso.',
	'VINNY_KARMA_MCP_RESET_CAST_SUCCESS'		=> 'Todos os votos efetuados por %s foram repostos com sucesso.',
	'VINNY_KARMA_MCP_ADJUST_SUCCESS'			=> 'O saldo de karma de %s foi ajustado em %d pontos com sucesso.',
	'VINNY_KARMA_MCP_ADJUST_REQUIRED'			=> 'A quantidade de ajuste (diferente de zero) e o motivo da moderação são obrigatórios.',
));
