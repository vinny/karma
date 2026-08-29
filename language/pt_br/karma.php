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
	'KARMA'							=> 'Karma',
	'KARMA_UPVOTE'					=> 'Votar a favor',
	'KARMA_DOWNVOTE'				=> 'Votar contra',

	'KARMA_ERROR_VOTE_FAILED'		=> 'Falha ao processar seu voto. Por favor, tente novamente.',
	'KARMA_ERROR_MUST_LOG_IN'		=> 'Você precisa estar autenticado para votar.',
	'KARMA_ERROR_POST_NOT_FOUND'	=> 'A mensagem solicitada não foi encontrada.',
	'KARMA_ERROR_NO_PERMISSION'		=> 'Você não tem permissão para votar.',
	'KARMA_ERROR_SELF_VOTE'			=> 'Você não pode votar em suas próprias mensagens.',
	'KARMA_ERROR_VOTE_DISABLED'		=> 'A votação está desativada para esta mensagem.',
	'KARMA_ERROR_DB_FAILED'			=> 'Ocorreu um erro no banco de dados. Por favor, tente novamente.',
	'KARMA_ALREADY_VOTED_UP'		=> 'Você já votou a favor desta mensagem.',
	'KARMA_ALREADY_VOTED_DOWN'		=> 'Você já votou contra esta mensagem.',
	'KARMA_ERROR_ALREADY_VOTED'		=> 'Você já votou nesta mensagem.',
	'KARMA_ERROR_FLOOD'				=> 'Você deve aguardar %d segundos antes de votar novamente.',

	// Notifications
	'NOTIFICATION_TYPE_KARMA_VOTE'	=> 'Alguém votou em sua mensagem',
	'NOTIFICATION_KARMA_UPVOTE'		=> '<strong>%1$s</strong> votou a favor da sua mensagem.',
	'NOTIFICATION_KARMA_DOWNVOTE'	=> '<strong>%1$s</strong> votou contra sua mensagem.',

	// Ranking page
	'KARMA_RANKING'					=> 'Classificação de karma',
	'KARMA_POSITION'				=> '#',
	'KARMA_USER'					=> 'Usuário',
	'KARMA_TOTAL'					=> 'Total de karma',
	'KARMA_RANK_GOLD'				=> '1º lugar',
	'KARMA_RANK_SILVER'				=> '2º lugar',
	'KARMA_RANK_BRONZE'				=> '3º lugar',
	'KARMA_RANK_EMPTY'				=> 'Nenhum usuário encontrado',

	// Mod actions
	'KARMA_SCORE_RESET'						=> 'Reiniciar pontuação de karma',
	'VINNY_KARMA_MCP_RESET_POST_CONFIRM'	=> 'Tem certeza de que deseja reiniciar a pontuação de karma desta mensagem para 0?',
	'VINNY_KARMA_MCP_RESET_POST_SUCCESS'	=> 'Pontuação de karma reiniciada com sucesso para a mensagem #%d.',
	'VINNY_KARMA_MCP_MANAGE'				=> 'Gerenciar',

	'KARMA_ERROR_INTERNAL'					=> 'Ocorreu um erro interno. Os detalhes foram registrados no log.',
	'LOG_KARMA_EXCEPTION'					=> 'Erro do sistema de Karma: %s',

	// Moderator logs
	'LOG_MCP_KARMA_RESET_RECEIVED'		=> '<strong>Karma recebido pelo usuário reiniciado</strong><br />» Usuário: %s',
	'LOG_MCP_KARMA_RESET_CAST'			=> '<strong>Votos efetuados pelo usuário reiniciados</strong><br />» Usuário: %s',
	'LOG_MCP_KARMA_ADJUST'				=> '<strong>Pontuação de karma do usuário ajustada</strong><br />» Usuário: %1$s (Ajuste: %2$d, Motivo: %3$s)',
	'LOG_MCP_KARMA_RESET_POST'			=> '<strong>Pontuação de karma da mensagem reiniciada</strong><br />» Autor: %s',
));

// Report Reasons
$lang['report_reasons']['TITLE']['ABUSE_KARMA'] = 'Abuso de votos';
$lang['report_reasons']['DESCRIPTION']['ABUSE_KARMA'] = 'O usuário está tentando manipular ou abusar do sistema de votação de karma.';
