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
	'MCP_KARMA_USER_DETAILS'			=> 'Detalhes do usuário',
	'ACL_M_KARMA_MANAGE'				=> 'Pode moderar karma de usuários',


	'VINNY_KARMA_MCP_RESET'				=> 'Reiniciar',
	'VINNY_KARMA_MCP_RESET_RECEIVED'	=> 'Reiniciar karma recebido',
	'VINNY_KARMA_MCP_RESET_CAST'		=> 'Reiniciar karma enviado',
	'VINNY_KARMA_MCP_RESET_ACTIONS'			=> 'Ações de reinicialização',
	'VINNY_KARMA_MCP_RESET_ACTIONS_EXPLAIN'	=> 'Você pode reiniciar o karma recebido ou enviado por este usuário. Estas ações são destrutivas e modificarão o histórico de votos.',
	'VINNY_KARMA_MCP_RESET_RECEIVED_EXPLAIN' => 'Exclui todos os votos efetuados por outros usuários nas mensagens deste usuário, reiniciando as pontuações.',
	'VINNY_KARMA_MCP_RESET_CAST_EXPLAIN'	=> 'Exclui todos os votos efetuados por este usuário em mensagens de outros usuários.',
	'VINNY_KARMA_MCP_ADJUST_BALANCE'	=> 'Ajustar saldo de karma',
	'VINNY_KARMA_MCP_ADJUST_BALANCE_EXPLAIN' => 'Você pode adicionar ou subtrair pontos manualmente do saldo total de karma do usuário. Isso modificará diretamente a pontuação do perfil.',
	'VINNY_KARMA_MCP_ADJUST_AMOUNT'		=> 'Quantidade de ajuste',
	'VINNY_KARMA_MCP_ADJUST_AMOUNT_EXP'	=> 'Use números negativos para subtrair (ex: -5) ou positivos para adicionar (ex: 10).',
	'VINNY_KARMA_MCP_REASON'			=> 'Motivo da moderação',
	'VINNY_KARMA_MCP_REASON_EXP'		=> 'Informe um motivo para o registro no log.',
	'VINNY_KARMA_MCP_VOTER'				=> 'Votante',
	'VINNY_KARMA_MCP_POST_AUTHOR'		=> 'Autor',
	'VINNY_KARMA_MCP_POST_ID'			=> 'Mensagem',
	'VINNY_KARMA_MCP_DIRECTION'			=> 'Voto',
	'VINNY_KARMA_MCP_TOTAL_VOTES'		=> 'Total de votos: %d',

	'VINNY_KARMA_MCP_CONFIRM_RESET_RECEIVED'	=> 'Tem certeza de que deseja reiniciar todo o karma recebido por %s? Isso excluirá todos os registros de votos em suas mensagens e recalculará sua pontuação total.',
	'VINNY_KARMA_MCP_CONFIRM_RESET_CAST'		=> 'Tem certeza de que deseja reiniciar todos os votos efetuados por %s? Isso excluirá todos os votos que este usuário realizou em outras mensagens.',
	'VINNY_KARMA_MCP_CONFIRM_ADJUST'			=> 'Tem certeza de que deseja ajustar o saldo de karma de %s em %d pontos?',

	'VINNY_KARMA_MCP_RESET_RECEIVED_SUCCESS'	=> 'Todo o karma recebido por %s foi reiniciado com sucesso.',
	'VINNY_KARMA_MCP_RESET_CAST_SUCCESS'		=> 'Todos os votos efetuados por %s foram reiniciados com sucesso.',
	'VINNY_KARMA_MCP_ADJUST_SUCCESS'			=> 'O saldo de karma de %s foi ajustado em %d pontos com sucesso.',
	'VINNY_KARMA_MCP_ADJUST_REQUIRED'			=> 'A quantidade de ajuste (diferente de zero) e o motivo da moderação são obrigatórios.',
));
