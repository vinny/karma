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
	'ACP_VINNY_KARMA_SETTINGS'				=> 'Configurações',
	'ACP_VINNY_KARMA_MAINTENANCE'			=> 'Manutenção',

	'VINNY_KARMA_SETTINGS_TITLE'			=> 'Configurações de karma',
	'VINNY_KARMA_SETTINGS_EXPLAIN'			=> 'Configure aqui as definições globais da extensão Sistema de Karma.',
	'VINNY_KARMA_MAINTENANCE_TITLE'			=> 'Manutenção de karma',
	'VINNY_KARMA_MAINTENANCE_EXPLAIN'		=> 'Execute tarefas de manutenção no banco de dados, como ressincronizar pontuações, reiniciar pontuações de usuários, expurgar registros e visualizar o histórico de votos.',

	'KARMA_SUPPORT_STAR'					=> 'Se você acha esta extensão útil, considere dar uma estrela no <a href="https://github.com/vinny/karma" target="_blank" rel="noopener"><i class="icon fa fa-github fa-fw" aria-hidden="true"></i>GitHub</a>.',
	'KARMA_SUPPORT_DONATE'					=> 'Você também pode apoiar o desenvolvimento com uma <a href="https://ko-fi.com/vinny1" target="_blank" rel="noopener"><i class="icon fa fa-heart fa-fw" aria-hidden="true"></i>doação</a> opcional.',

	'VINNY_KARMA_ENABLED'					=> 'Ativar sistema de karma',
	'VINNY_KARMA_ENABLED_EXPLAIN'			=> 'Ativa ou desativa globalmente a exibição do karma, os botões de votação e os cálculos.',
	'VINNY_KARMA_ENABLE_DOWNVOTE'			=> 'Ativar votos contrários',
	'VINNY_KARMA_ENABLE_DOWNVOTE_EXPLAIN'	=> 'Permite que os usuários efetuem votos negativos. Se desativado, apenas votos positivos (a favor) serão permitidos, incentivando a positividade da comunidade.',
	'VINNY_KARMA_FLOOD_INTERVAL'			=> 'Intervalo entre votos',
	'VINNY_KARMA_FLOOD_INTERVAL_EXPLAIN'	=> 'Tempo em segundos que um usuário deve aguardar antes de votar novamente. Defina como 0 para desativar este limite.',
	'VINNY_KARMA_EXCLUDED_FORUMS'			=> 'Fóruns excluídos',
	'VINNY_KARMA_EXCLUDED_FORUMS_EXPLAIN'	=> 'Selecione os fóruns onde o sistema de karma (painel de votação e contadores) deve ser completamente ocultado e desativado. Mantenha pressionado <samp>CTRL</samp> (ou <samp>Cmd</samp> no macOS) para selecionar múltiplos fóruns.',

	'VINNY_KARMA_SAVED'						=> 'As configurações de karma foram atualizadas com sucesso.',

	// Maintenance Actions
	'VINNY_KARMA_RESYNC'					=> 'Ressincronizar pontuações de karma',
	'VINNY_KARMA_RESYNC_EXPLAIN'			=> 'Recalcula todas as pontuações de karma de mensagens e usuários a partir da tabela de registros de votos. Utilize esta opção se os contadores ficarem dessincronizados.',
	'VINNY_KARMA_RESYNC_SUCCESS'			=> 'As pontuações de karma foram ressincronizadas com sucesso.',

	'VINNY_KARMA_RESET_USER'				=> 'Reiniciar karma e histórico do usuário',
	'VINNY_KARMA_RESET_USER_EXPLAIN'		=> 'Informe um nome de usuário para reiniciar sua pontuação total de karma para 0 e apagar completamente todos os votos enviados e recebidos por ele.',
	'VINNY_KARMA_RESET_USER_NOT_FOUND'		=> 'O nome de usuário solicitado não foi encontrado.',
	'VINNY_KARMA_RESET_USER_SUCCESS'		=> 'A pontuação de karma e os registros de votos do usuário "%s" foram reiniciados com sucesso.',
	'VINNY_KARMA_CONFIRM_RESET'				=> 'Deseja reiniciar o Karma do usuário %s?',

	'VINNY_KARMA_PRUNE'						=> 'Expurgar registros de votos antigos',
	'VINNY_KARMA_PRUNE_EXPLAIN'				=> 'Remove os registros históricos de votos mais antigos que o número de dias especificado (ex: informar 30 apagará registros anteriores a 30 dias, mantendo votos recentes e de hoje intocados). As pontuações absolutas de karma de mensagens e usuários permanecerão inalteradas.',
	'VINNY_KARMA_PRUNE_SUCCESS'				=> '%d registros de votos anteriores a %d dias foram expurgados com sucesso.',

	// Log
	'VINNY_KARMA_AUDIT_LOG'					=> 'Registro de votos',
	'VINNY_KARMA_AUDIT_VOTER'				=> 'Votante',
	'VINNY_KARMA_AUDIT_AUTHOR'				=> 'Autor',
	'VINNY_KARMA_AUDIT_POST'				=> 'ID da mensagem',
	'VINNY_KARMA_AUDIT_DIRECTION'			=> 'Voto',
	'VINNY_KARMA_AUDIT_TIME'				=> 'Data / hora',
	'VINNY_KARMA_AUDIT_UP'					=> 'A favor',
	'VINNY_KARMA_AUDIT_DOWN'				=> 'Contra',
	'VINNY_KARMA_AUDIT_EMPTY'				=> 'Nenhum voto foi registrado no banco de dados ainda.',

	'VINNY_KARMA_PRUNE_INVALID_DAYS'		=> 'Por favor, informe um número válido de dias (maior que 0).',
	'VINNY_KARMA_CONFIRM_PRUNE'				=> 'Tem certeza de que deseja expurgar todos os registros de votos anteriores a %d dias? Esta ação não pode ser desfeita.',

	// Admin logs
	'LOG_ACP_KARMA_RESYNC'					=> '<strong>Sistema de Karma: Ressincronizou todas as pontuações de karma</strong>',
	'LOG_ACP_KARMA_RESET_USER'				=> '<strong>Sistema de Karma: Reiniciou pontuação de karma e histórico do usuário</strong><br />» %s',
	'LOG_ACP_KARMA_PRUNE'					=> '<strong>Sistema de Karma: Expurgou registros de votos antigos</strong><br />» Registros anteriores a %d dias removidos (%d entradas excluídas)',
));
