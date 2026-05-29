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
	'KARMA'							=> 'Karma',
	'KARMA_UPVOTE'					=> 'Upvote',
	'KARMA_DOWNVOTE'				=> 'Downvote',

	'KARMA_ERROR_VOTE_FAILED'		=> 'Failed to process your vote. Please try again.',
	'KARMA_ERROR_MUST_LOG_IN'		=> 'You must be logged in to vote',
	'KARMA_ERROR_POST_NOT_FOUND'	=> 'The requested post could not be found',
	'KARMA_ERROR_NO_PERMISSION'		=> 'You do not have permission to vote',
	'KARMA_ERROR_SELF_VOTE'			=> 'You cannot vote on your own posts',
	'KARMA_ERROR_VOTE_DISABLED'		=> 'Voting is disabled for this post',
	'KARMA_ERROR_DB_FAILED'			=> 'A database error occurred. Please try again.',
	'KARMA_ALREADY_VOTED_UP'		=> 'You have already upvoted this post',
	'KARMA_ALREADY_VOTED_DOWN'		=> 'You have already downvoted this post',
	'KARMA_ERROR_ALREADY_VOTED'		=> 'You have already voted on this post',
	'KARMA_ERROR_FLOOD'				=> 'You must wait %d seconds before casting another vote.',

	// Notifications
	'NOTIFICATION_TYPE_KARMA_VOTE'	=> 'Someone votes on your post',
	'NOTIFICATION_KARMA_UPVOTE'		=> '<strong>%1$s</strong> upvoted your post',
	'NOTIFICATION_KARMA_DOWNVOTE'	=> '<strong>%1$s</strong> downvoted your post',

	// Ranking
	'KARMA_RANKING'					=> 'Karma Ranking',
	'KARMA_POSITION'				=> '#',
	'KARMA_USER'					=> 'Username',
	'KARMA_TOTAL'					=> 'Total Karma',
	'KARMA_RANK_GOLD'				=> '1st Place',
	'KARMA_RANK_SILVER'				=> '2nd Place',
	'KARMA_RANK_BRONZE'				=> '3rd Place',
	'KARMA_RANK_EMPTY'				=> 'No users found',

	// Mod actions
	'KARMA_SCORE_RESET'						=> 'Reset karma score',
	'VINNY_KARMA_MCP_RESET_POST_CONFIRM'	=> 'Are you sure you want to reset the karma score of this post back to 0?',
	'VINNY_KARMA_MCP_RESET_POST_SUCCESS'	=> 'Successfully reset karma score for post #%d.',
	'VINNY_KARMA_MCP_MANAGE'				=> 'Manage',
));

// Report Reasons
$lang['report_reasons']['TITLE']['ABUSE_KARMA'] = 'Vote Abuse';
$lang['report_reasons']['DESCRIPTION']['ABUSE_KARMA'] = 'Manipulating votes or targeting users (targeted upvoting or downvoting).';
