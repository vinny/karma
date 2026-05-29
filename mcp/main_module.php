<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\karma\mcp;

/**
* phpBB Karma Extension MCP Module class
*/
class main_module
{
	/** @var \phpbb\module\module */
	public $p_master;

	/** @var string */
	public $u_action;

	/** @var string */
	public $tpl_name;

	/** @var string */
	public $page_title;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\module\module $p_master
	*/
	public function __construct($p_master)
	{
		$this->p_master = $p_master;
	}

	/**
	* Execute MCP Module
	*
	* @param int $id
	* @param string $mode
	*/
	public function main($id, $mode)
	{
		global $db, $request, $template, $user, $config, $phpbb_container, $auth;

		$this->root_path = $phpbb_container->getParameter('core.root_path');
		$this->php_ext = $phpbb_container->getParameter('core.php_ext');
		$table_prefix = $phpbb_container->getParameter('core.table_prefix');

		// Check moderator permission
		if (!$auth->acl_get('m_karma_manage'))
		{
			trigger_error('NO_PERMISSION', E_USER_WARNING);
		}

		$user->add_lang_ext('vinny/karma', 'karma');
		$user->add_lang_ext('vinny/karma', 'info_mcp_karma');

		$this->page_title = $user->lang('MCP_KARMA');

		$action = $request->variable('action', array('' => ''));

		if (is_array($action))
		{
			$action = key($action);
		}

		switch ($mode)
		{
			case 'karma_user':
				$template->assign_vars(array(
					'U_FIND_USERNAME'	=> append_sid("{$this->root_path}memberlist.{$this->php_ext}", 'mode=searchuser&amp;form=mcp&amp;field=username&amp;select_single=true'),
					'U_POST_ACTION'		=> str_replace('mode=karma_user', 'mode=karma_user_details', $this->u_action),

					'L_TITLE'			=> $user->lang['MCP_KARMA'],
				));

				$this->tpl_name = 'mcp_karma_front';
			break;

			case 'karma_user_details':
				$user_id = $request->variable('u', 0);
				$username = $request->variable('username', '', true);

				$sql_where = ($user_id) ? "user_id = $user_id" : "username_clean = '" . $db->sql_escape(utf8_clean_string($username)) . "'";

				$sql = 'SELECT *
					FROM ' . USERS_TABLE . "
					WHERE $sql_where";
				$result = $db->sql_query($sql);
				$userrow = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if (!$userrow || (int) $userrow['user_id'] === ANONYMOUS)
				{
					trigger_error('NO_USER');
				}

				$user_id = (int) $userrow['user_id'];

				// Populate user id to the currently active module (this module)
				if (strpos($this->u_action, "&amp;u=$user_id") === false)
				{
					$this->p_master->adjust_url('&amp;u=' . $user_id);
					$this->u_action .= "&amp;u=$user_id";
				}

				add_form_key('mcp_karma');

				// Handle actions
				if ($action)
				{
					if ($action === 'reset_received')
					{
						if (confirm_box(true))
						{
							$db->sql_transaction('begin');
							try
							{
								// Delete votes received on posts authored by this user
								$sql = 'DELETE FROM ' . $table_prefix . 'vinny_karma_votes
									WHERE post_id IN (
										SELECT post_id
										FROM ' . POSTS_TABLE . '
										WHERE poster_id = ' . (int) $user_id . '
									)';
								$db->sql_query($sql);

								// Reset post karma score on their posts
								$sql = 'UPDATE ' . POSTS_TABLE . '
									SET post_karma = 0
									WHERE poster_id = ' . (int) $user_id;
								$db->sql_query($sql);

								// Reset user's own karma score
								$sql = 'UPDATE ' . USERS_TABLE . '
									SET user_karma = 0
									WHERE user_id = ' . (int) $user_id;
								$db->sql_query($sql);

								// Run global resync to update everyone's scores based on remaining logs
								$this->resync($table_prefix);
								$db->sql_transaction('commit');

								// Log moderation action to Mod Log
								$phpbb_log = $phpbb_container->get('log');
								$phpbb_log->add('mod', $user->data['user_id'], $user->ip, 'LOG_MCP_KARMA_RESET_RECEIVED', time(), array(
									'forum_id' => 0,
									'topic_id' => 0,
									$userrow['username']
								));
							}
							catch (\Exception $e)
							{
								$db->sql_transaction('rollback');
								trigger_error($e->getMessage() . adm_back_link($this->u_action), E_USER_WARNING);
							}

							meta_refresh(3, $this->u_action);
							trigger_error(sprintf($user->lang('VINNY_KARMA_MCP_RESET_RECEIVED_SUCCESS'), $userrow['username']) . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $this->u_action . '">', '</a>'));
						}
						else
						{
							confirm_box(false, sprintf($user->lang('VINNY_KARMA_MCP_CONFIRM_RESET_RECEIVED'), $userrow['username']), build_hidden_fields(array(
								'action[reset_received]'	=> 1,
								'u'							=> $user_id,
							)));
						}
					}
					else if ($action === 'reset_cast')
					{
						if (confirm_box(true))
						{
							$db->sql_transaction('begin');
							try
							{
								// Delete votes cast by this user
								$sql = 'DELETE FROM ' . $table_prefix . 'vinny_karma_votes
									WHERE user_id = ' . (int) $user_id;
								$db->sql_query($sql);

								$this->resync($table_prefix);
								$db->sql_transaction('commit');

								// Log moderation action to Mod Log
								$phpbb_log = $phpbb_container->get('log');
								$phpbb_log->add('mod', $user->data['user_id'], $user->ip, 'LOG_MCP_KARMA_RESET_CAST', time(), array(
									'forum_id' => 0,
									'topic_id' => 0,
									$userrow['username']
								));
							}
							catch (\Exception $e)
							{
								$db->sql_transaction('rollback');
								trigger_error($e->getMessage() . adm_back_link($this->u_action), E_USER_WARNING);
							}

							meta_refresh(3, $this->u_action);
							trigger_error(sprintf($user->lang('VINNY_KARMA_MCP_RESET_CAST_SUCCESS'), $userrow['username']) . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $this->u_action . '">', '</a>'));
						}
						else
						{
							confirm_box(false, sprintf($user->lang('VINNY_KARMA_MCP_CONFIRM_RESET_CAST'), $userrow['username']), build_hidden_fields(array(
								'action[reset_cast]'	=> 1,
								'u'						=> $user_id,
							)));
						}
					}
					else if ($action === 'adjust_balance')
					{
						$adjustment = $request->variable('adjust_amount', 0);
						$reason = $request->variable('adjust_reason', '', true);

						if ($adjustment === 0)
						{
							trigger_error('FORM_INVALID', E_USER_WARNING);
						}

						if (confirm_box(true))
						{
							$sql = 'UPDATE ' . USERS_TABLE . '
								SET user_karma = user_karma + ' . (int) $adjustment . '
								WHERE user_id = ' . (int) $user_id;
							$db->sql_query($sql);

							// Log moderation action to Mod Log
							$phpbb_log = $phpbb_container->get('log');
							$phpbb_log->add('mod', $user->data['user_id'], $user->ip, 'LOG_MCP_KARMA_ADJUST', time(), array(
								'forum_id' => 0,
								'topic_id' => 0,
								$userrow['username'] . ($reason ? ' (' . $reason . ')' : ''),
								$adjustment
							));

							meta_refresh(3, $this->u_action);
							trigger_error(sprintf($user->lang('VINNY_KARMA_MCP_ADJUST_SUCCESS'), $userrow['username'], $adjustment) . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $this->u_action . '">', '</a>'));
						}
						else
						{
							confirm_box(false, sprintf($user->lang('VINNY_KARMA_MCP_CONFIRM_ADJUST'), $userrow['username'], $adjustment), build_hidden_fields(array(
								'action[adjust_balance]'	=> 1,
								'adjust_amount'				=> $adjustment,
								'adjust_reason'				=> $reason,
								'u'							=> $user_id,
							)));
						}
					}

				}

				if (!function_exists('phpbb_get_user_avatar'))
				{
					include($this->root_path . 'includes/functions_display.' . $this->php_ext);
				}
				$avatar_img = phpbb_get_user_avatar($userrow);

				// Build query filter
				$where_sql = ' WHERE (v.user_id = ' . (int) $user_id . ' OR p.poster_id = ' . (int) $user_id . ')';

				// Count total votes matching the criteria
				$sql = 'SELECT COUNT(v.vote_id) as total_votes
					FROM ' . $table_prefix . 'vinny_karma_votes v
					LEFT JOIN ' . POSTS_TABLE . ' p ON v.post_id = p.post_id' . $where_sql;
				$result = $db->sql_query($sql);
				$total_votes = (int) $db->sql_fetchfield('total_votes');
				$db->sql_freeresult($result);

				// Handle pagination
				$start = $request->variable('start', 0);
				$limit = 20;

				// Fetch votes
				$sql = 'SELECT v.vote_id, v.vote_direction, v.vote_time, v.post_id,
						u1.user_id as voter_id, u1.username as voter_username, u1.user_colour as voter_colour,
						u2.user_id as author_id, u2.username as author_username, u2.user_colour as author_colour,
						p.topic_id
					FROM ' . $table_prefix . 'vinny_karma_votes v
					LEFT JOIN ' . POSTS_TABLE . ' p ON v.post_id = p.post_id
					LEFT JOIN ' . USERS_TABLE . ' u1 ON v.user_id = u1.user_id
					LEFT JOIN ' . USERS_TABLE . ' u2 ON p.poster_id = u2.user_id' . $where_sql . '
					ORDER BY v.vote_time DESC';
				$result = $db->sql_query_limit($sql, $limit, $start);

				while ($row = $db->sql_fetchrow($result))
				{
					$voter_link = $row['voter_id'] ? get_username_string('full', $row['voter_id'], $row['voter_username'], $row['voter_colour']) : $user->lang('GUEST');
					$author_link = $row['author_id'] ? get_username_string('full', $row['author_id'], $row['author_username'], $row['author_colour']) : $user->lang('GUEST');

					$post_url = '';
					if ($row['post_id'] && $row['topic_id'])
					{
						$post_url = append_sid($this->root_path . 'viewtopic.' . $this->php_ext, 'p=' . $row['post_id'] . '#p' . $row['post_id']);
					}

					$template->assign_block_vars('votes', array(
						'VOTE_ID'		=> $row['vote_id'],
						'VOTER'			=> $voter_link,
						'AUTHOR'		=> $author_link,
						'POST_URL'		=> $post_url,
						'POST_ID'		=> $row['post_id'],
						'DIRECTION'		=> $row['vote_direction'],
						'DATE'			=> $user->format_date($row['vote_time']),
					));
				}
				$db->sql_freeresult($result);

				// Generate Pagination
				$base_url = $this->u_action;
				$pagination = $phpbb_container->get('pagination');
				$pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_votes, $limit, $start);

				$template->assign_vars(array(
					'U_POST_ACTION'			=> $this->u_action,

					'L_TITLE'			=> $user->lang['MCP_KARMA_USER_DETAILS'],

					'TOTAL_REPORTS'		=> $user->lang('VINNY_KARMA_MCP_TOTAL_VOTES', $total_votes),

					'USER_KARMA'		=> (int) $userrow['user_karma'],

					'USERNAME_FULL'		=> get_username_string('full', $userrow['user_id'], $userrow['username'], $userrow['user_colour']),
					'USERNAME_COLOUR'	=> get_username_string('colour', $userrow['user_id'], $userrow['username'], $userrow['user_colour']),
					'USERNAME'			=> get_username_string('username', $userrow['user_id'], $userrow['username'], $userrow['user_colour']),
					'U_PROFILE'			=> get_username_string('profile', $userrow['user_id'], $userrow['username'], $userrow['user_colour']),

					'AVATAR_IMG'		=> $avatar_img,
				));

				$this->tpl_name = 'mcp_karma_user';
			break;
		}
	}

	/**
	* Recalculates karma scores for all posts and users
	*
	* @param string $table_prefix
	*/
	protected function resync($table_prefix)
	{
		global $db;

		// 1. Recalculate post_karma for all posts
		$sql = 'UPDATE ' . POSTS_TABLE . ' p
			SET p.post_karma = (
				SELECT COALESCE(SUM(v.vote_direction), 0)
				FROM ' . $table_prefix . 'vinny_karma_votes v
				WHERE v.post_id = p.post_id
			)';
		$db->sql_query($sql);

		// 2. Recalculate user_karma for all users
		$sql = 'UPDATE ' . USERS_TABLE . ' u
			SET u.user_karma = (
				SELECT COALESCE(SUM(p.post_karma), 0)
				FROM ' . POSTS_TABLE . ' p
				WHERE p.poster_id = u.user_id
			)';
		$db->sql_query($sql);
	}
}
