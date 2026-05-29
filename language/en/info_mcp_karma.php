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
	'MCP_KARMA'							=> 'Karma',
	'MCP_KARMA_USER'					=> 'Front page',
	'MCP_KARMA_USER_DETAILS'			=> 'User details',
	'ACL_M_KARMA_MANAGE'				=> 'Can moderate user karma',

	'VINNY_KARMA_MCP_USER_NOT_FOUND'	=> 'The specified user was not found.',
	'VINNY_KARMA_MCP_SELECT_USER'		=> 'Select User',
	'VINNY_KARMA_MCP_VOTER_STATS'		=> 'Votes Cast (Voter Stats)',
	'VINNY_KARMA_MCP_AUTHOR_STATS'		=> 'Votes Received (Author Stats)',
	'VINNY_KARMA_MCP_UPVOTES'			=> 'Upvotes',
	'VINNY_KARMA_MCP_DOWNVOTES'			=> 'Downvotes',
	'VINNY_KARMA_MCP_RESET_RECEIVED'	=> 'Reset Received Karma',
	'VINNY_KARMA_MCP_RESET_CAST'		=> 'Reset Cast Karma',
	'VINNY_KARMA_MCP_RESET_ACTIONS'			=> 'Reset Actions',
	'VINNY_KARMA_MCP_RESET_ACTIONS_EXPLAIN'	=> 'You can reset karma received or cast by this user. These actions are destructive and will modify vote history logs.',
	'VINNY_KARMA_MCP_RESET_RECEIVED_EXPLAIN' => 'Delete all votes cast by other users on posts authored by this user, resetting their post scores.',
	'VINNY_KARMA_MCP_RESET_CAST_EXPLAIN'	=> 'Delete all votes cast by this user on posts authored by other users.',
	'VINNY_KARMA_MCP_ADJUST_BALANCE'	=> 'Adjust Karma Balance',
	'VINNY_KARMA_MCP_ADJUST_BALANCE_EXPLAIN' => 'You can manually add or subtract points to the user\'s total karma balance. This will directly modify their profile score.',
	'VINNY_KARMA_MCP_ADJUST_AMOUNT'		=> 'Adjustment Amount',
	'VINNY_KARMA_MCP_ADJUST_AMOUNT_EXP'	=> 'Use negative numbers to subtract (e.g. -5) or positive to add (e.g. 10).',
	'VINNY_KARMA_MCP_REASON'			=> 'Moderation Reason',
	'VINNY_KARMA_MCP_REASON_EXP'		=> 'Provide a reason for the log entry.',
	'VINNY_KARMA_MCP_VOTER'				=> 'Voter',
	'VINNY_KARMA_MCP_POST_AUTHOR'		=> 'Author',
	'VINNY_KARMA_MCP_POST_ID'			=> 'Post',
	'VINNY_KARMA_MCP_DIRECTION'			=> 'Vote',
	'VINNY_KARMA_MCP_TOTAL_VOTES'		=> 'Total votes: %d',

	'VINNY_KARMA_MCP_CONFIRM_RESET_RECEIVED'	=> 'Are you sure you want to reset all received karma for %s? This will delete all vote records on their posts and recalculate their total karma score.',
	'VINNY_KARMA_MCP_CONFIRM_RESET_CAST'		=> 'Are you sure you want to reset all cast karma for %s? This will delete all votes this user has cast on other posts.',
	'VINNY_KARMA_MCP_CONFIRM_ADJUST'			=> 'Are you sure you want to adjust the karma balance of %s by %d points?',

	'VINNY_KARMA_MCP_RESET_RECEIVED_SUCCESS'	=> 'Successfully reset all received karma for %s.',
	'VINNY_KARMA_MCP_RESET_CAST_SUCCESS'		=> 'Successfully reset all cast karma for %s.',
	'VINNY_KARMA_MCP_ADJUST_SUCCESS'			=> 'Successfully adjusted karma balance for %s by %d points.',
	'VINNY_KARMA_MCP_VOTES_DELETED_SUCCESS'		=> 'Successfully deleted %d vote(s).',
	'VINNY_KARMA_MCP_ALL_VOTES_DELETED_SUCCESS'	=> 'Successfully deleted all votes associated with this user.',
	'VINNY_KARMA_MCP_VOTE_CONFIRM_DELETE'		=> 'Are you sure you want to delete the selected votes?',
	'VINNY_KARMA_MCP_VOTE_CONFIRM_DELETE_ALL'	=> 'Are you sure you want to delete all votes associated with this user?',

	'VINNY_KARMA_MCP_FILTER_VOTER'		=> 'Filter by Voter Username',
	'VINNY_KARMA_MCP_FILTER_AUTHOR'		=> 'Filter by Post Author',
	'VINNY_KARMA_MCP_FILTER_POST'		=> 'Filter by Post ID',

	'VINNY_KARMA_MCP_RESET_POST_CONFIRM'		=> 'Are you sure you want to reset the karma score of this post back to 0?',
	'VINNY_KARMA_MCP_RESET_POST_SUCCESS'		=> 'Successfully reset karma score for post #%d.',

	// Moderator logs
	'LOG_MCP_KARMA_RESET_RECEIVED'		=> '<strong>Reset user received karma</strong><br />» %s',
	'LOG_MCP_KARMA_RESET_CAST'			=> '<strong>Reset user cast votes</strong><br />» %s',
	'LOG_MCP_KARMA_ADJUST'				=> '<strong>Adjusted user karma score</strong><br />» %s (Adjustment: %d)',
	'LOG_MCP_KARMA_VOTE_DELETE'			=> '<strong>Deleted karma vote</strong><br />» Voter: %s, Author: %s, Post: #%d',
	'LOG_MCP_KARMA_RESET_POST'			=> '<strong>Reset post karma score</strong><br />» Post: #%d, Author: %s',
));
