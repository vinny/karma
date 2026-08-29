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
	'ACP_VINNY_KARMA'						=> 'Sistema de Karma',
	'ACP_VINNY_KARMA_SETTINGS'				=> 'Definições',
	'ACP_VINNY_KARMA_MAINTENANCE'			=> 'Manutenção',

	'VINNY_KARMA_SETTINGS_TITLE'			=> 'Definições de karma',
	'VINNY_KARMA_SETTINGS_EXPLAIN'			=> 'Configure aqui as definições globais da extensão Sistema de Karma.',
	'VINNY_KARMA_MAINTENANCE_TITLE'			=> 'Manutenção de karma',
	'VINNY_KARMA_MAINTENANCE_EXPLAIN'		=> 'Execute tarefas de manutenção na base de dados, como resincronizar pontuações, repor pontuações de utilizadores, expurgar registos e visualizar o histórico de votos.',

	'KARMA_SUPPORT_STAR'					=> 'Se acha esta extensão útil, considere dar uma estrela no <a href="https://github.com/vinny/karma" target="_blank" rel="noopener"><i class="icon fa fa-github fa-fw" aria-hidden="true"></i>GitHub</a>.',
	'KARMA_SUPPORT_DONATE'					=> 'Também pode apoiar o desenvolvimento com uma <a href="https://ko-fi.com/vinny1" target="_blank" rel="noopener"><i class="icon fa fa-heart fa-fw" aria-hidden="true"></i>doação</a> opcional.',

	'VINNY_KARMA_ENABLED'					=> 'Ativar sistema de karma',
	'VINNY_KARMA_ENABLED_EXPLAIN'			=> 'Ativa ou desativa globalmente a exibição do karma, os botões de votação e os cálculos.',
	'VINNY_KARMA_ENABLE_DOWNVOTE'			=> 'Ativar votos contrários',
	'VINNY_KARMA_ENABLE_DOWNVOTE_EXPLAIN'	=> 'Permite que os utilizadores efetuem votos negativos. Se desativado, apenas votos positivos (a favor) serão permitidos, incentivando a positividade da comunidade.',
	'VINNY_KARMA_FLOOD_INTERVAL'			=> 'Intervalo entre votos',
	'VINNY_KARMA_FLOOD_INTERVAL_EXPLAIN'	=> 'Tempo em segundos que um utilizador deve aguardar antes de poder votar novamente. Defina como 0 para desativar este limite.',
	'VINNY_KARMA_EXCLUDED_FORUMS'			=> 'Fóruns excluídos',
	'VINNY_KARMA_EXCLUDED_FORUMS_EXPLAIN'	=> 'Selecione os fóruns onde o sistema de karma (painel de votação e contadores) deve ser completamente ocultado e desativado. Mantenha pressionado <samp>CTRL</samp> (ou <samp>Cmd</samp> no macOS) para selecionar múltiplos fóruns.',

	'VINNY_KARMA_SAVED'						=> 'As definições de karma foram atualizadas com sucesso.',

	// Maintenance Actions
	'VINNY_KARMA_RESYNC'					=> 'Resincronizar pontuações de karma',
	'VINNY_KARMA_RESYNC_EXPLAIN'			=> 'Recalcula todas as pontuações de karma de mensagens e utilizadores a partir da tabela de registos de votos. Utilize esta opção se os contadores ficarem desincronizados.',
	'VINNY_KARMA_RESYNC_SUCCESS'			=> 'As pontuações de karma foram resincronizadas com sucesso.',

	'VINNY_KARMA_RESET_USER'				=> 'Repor karma e histórico do utilizador',
	'VINNY_KARMA_RESET_USER_EXPLAIN'		=> 'Introduza um nome de utilizador para repor a sua pontuação total de karma para 0 e apagar completamente todos os votos enviados e recebidos por ele.',
	'VINNY_KARMA_RESET_USER_NOT_FOUND'		=> 'O nome de utilizador solicitado não foi encontrado.',
	'VINNY_KARMA_RESET_USER_SUCCESS'		=> 'A pontuação de karma e os registos de votos do utilizador "%s" foram repostos com sucesso.',
	'VINNY_KARMA_CONFIRM_RESET'				=> 'Deseja repor o Karma do utilizador %s?',

	'VINNY_KARMA_PRUNE'						=> 'Expurgar registos de votos antigos',
	'VINNY_KARMA_PRUNE_EXPLAIN'				=> 'Remove os registos históricos de votos mais antigos do que o número de dias especificado (ex: introduzir 30 apagará registos anteriores a 30 dias, mantendo votos recentes e de hoje intocados). As pontuações absolutas de karma de mensagens e utilizadores permanecerão inalteradas.',
	'VINNY_KARMA_PRUNE_SUCCESS'				=> '%d registos de votos anteriores a %d dias foram expurgados com sucesso.',

	// Log
	'VINNY_KARMA_AUDIT_LOG'					=> 'Registo de votos',
	'VINNY_KARMA_AUDIT_VOTER'				=> 'Votante',
	'VINNY_KARMA_AUDIT_AUTHOR'				=> 'Autor',
	'VINNY_KARMA_AUDIT_POST'				=> 'ID da mensagem',
	'VINNY_KARMA_AUDIT_DIRECTION'			=> 'Voto',
	'VINNY_KARMA_AUDIT_TIME'				=> 'Data / hora',
	'VINNY_KARMA_AUDIT_UP'					=> 'A favor',
	'VINNY_KARMA_AUDIT_DOWN'				=> 'Contra',
	'VINNY_KARMA_AUDIT_EMPTY'				=> 'Nenhum voto foi registado na base de dados ainda.',

	'VINNY_KARMA_PRUNE_INVALID_DAYS'		=> 'Por favor, introduza um número válido de dias (superior a 0).',
	'VINNY_KARMA_CONFIRM_PRUNE'				=> 'Tem a certeza de que deseja expurgar todos os registos de votos anteriores a %d dias? Esta ação não pode ser desfeita.',

	// Admin logs
	'LOG_ACP_KARMA_RESYNC'					=> '<strong>Sistema de Karma: Resincronizou todas as pontuações de karma</strong>',
	'LOG_ACP_KARMA_RESET_USER'				=> '<strong>Sistema de Karma: Repôs a pontuação de karma e histórico do utilizador</strong><br />» %s',
	'LOG_ACP_KARMA_PRUNE'					=> '<strong>Sistema de Karma: Expurgou registos de votos antigos</strong><br />» Registos anteriores a %d dias removidos (%d entradas eliminadas)',
));
