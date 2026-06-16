<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
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
	'ACP_VINNY_KARMA'						=> 'Karma System',
	'ACP_VINNY_KARMA_SETTINGS'				=> 'Settings',
	'ACP_VINNY_KARMA_MAINTENANCE'			=> 'Maintenance',

	'VINNY_KARMA_SETTINGS_TITLE'			=> 'Karma Settings',
	'VINNY_KARMA_SETTINGS_EXPLAIN'			=> 'Configure the global settings for the Karma System extension here.',
	'VINNY_KARMA_MAINTENANCE_TITLE'			=> 'Karma Maintenance',
	'VINNY_KARMA_MAINTENANCE_EXPLAIN'		=> 'Perform database maintenance tasks like resyncing user/post karma scores, resetting user scores, pruning logs, and viewing the vote audit trail.',

	'VINNY_KARMA_ENABLED'					=> 'Enable Karma System',
	'VINNY_KARMA_ENABLED_EXPLAIN'			=> 'Globally enable or disable karma displays, voting buttons, and calculations.',
	'VINNY_KARMA_ENABLE_DOWNVOTE'			=> 'Enable Downvotes',
	'VINNY_KARMA_ENABLE_DOWNVOTE_EXPLAIN'	=> 'Allows users to cast negative votes. If disabled, only positive voting (upvotes) is possible, encouraging community positivity.',
	'VINNY_KARMA_FLOOD_INTERVAL'			=> 'Vote Flood Interval',
	'VINNY_KARMA_FLOOD_INTERVAL_EXPLAIN'	=> 'Number of seconds a user must wait before they can cast another vote. Set to 0 to disable this limit.',
	'VINNY_KARMA_EXCLUDED_FORUMS'			=> 'Excluded Forums',
	'VINNY_KARMA_EXCLUDED_FORUMS_EXPLAIN'	=> 'Select the forums where the karma system (voting panels, score counters) should be completely hidden and disabled. Select multiple forums by holding <samp>CTRL</samp> (or <samp>Cmd</samp> on macOS) and clicking.',

	'VINNY_KARMA_SAVED'						=> 'Karma settings have been successfully updated.',

	// Maintenance Actions
	'VINNY_KARMA_RESYNC'					=> 'Resync Karma Scores',
	'VINNY_KARMA_RESYNC_EXPLAIN'			=> 'Recalculates all post and user karma scores from scratch using the raw votes log table. Use this if counters become out-of-sync.',
	'VINNY_KARMA_RESYNC_SUCCESS'			=> 'Karma scores have been successfully resynchronized.',

	'VINNY_KARMA_RESET_USER'				=> 'Reset User Karma & History',
	'VINNY_KARMA_RESET_USER_EXPLAIN'		=> 'Enter a username to reset their total karma score to 0 and completely purge all votes cast by them and received on their posts from the log.',
	'VINNY_KARMA_RESET_USER_NOT_FOUND'		=> 'The requested username could not be found.',
	'VINNY_KARMA_RESET_USER_SUCCESS'		=> 'Karma score and vote logs for user "%s" have been successfully reset.',
	'VINNY_KARMA_CONFIRM_RESET'				=> 'Do you want to reset the Karma of user %s?',

	'VINNY_KARMA_PRUNE'						=> 'Prune Old Vote Logs',
	'VINNY_KARMA_PRUNE_EXPLAIN'				=> 'Clears historical vote tracking records that are older than the specified number of days (e.g. entering 30 will delete logs older than 30 days, keeping all recent votes and today’s votes intact). The absolute karma scores of posts and users will remain unchanged.',
	'VINNY_KARMA_PRUNE_SUCCESS'				=> 'Successfully pruned %d vote entries older than %d days.',

	// Log
	'VINNY_KARMA_AUDIT_LOG'					=> 'Vote Log',
	'VINNY_KARMA_AUDIT_VOTER'				=> 'Voter',
	'VINNY_KARMA_AUDIT_AUTHOR'				=> 'Author',
	'VINNY_KARMA_AUDIT_POST'				=> 'Post ID',
	'VINNY_KARMA_AUDIT_DIRECTION'			=> 'Vote',
	'VINNY_KARMA_AUDIT_TIME'				=> 'Date / Time',
	'VINNY_KARMA_AUDIT_UP'					=> 'Upvote',
	'VINNY_KARMA_AUDIT_DOWN'				=> 'Downvote',
	'VINNY_KARMA_AUDIT_EMPTY'				=> 'No votes have been recorded in the database yet.',
));
