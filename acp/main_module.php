<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\karma\acp;

/**
* phpBB Karma Extension ACP Module class
*/
class main_module
{
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
	* Execute ACP Module
	*
	* @param int $id
	* @param string $mode
	*/
	public function main($id, $mode)
	{
		global $db, $request, $template, $user, $config, $phpbb_container;

		$this->root_path = $phpbb_container->getParameter('core.root_path');
		$this->php_ext = $phpbb_container->getParameter('core.php_ext');
		$table_prefix = $phpbb_container->getParameter('core.table_prefix');

		$user->add_lang_ext('vinny/karma', 'info_acp_karma');

		$this->page_title = $user->lang('ACP_VINNY_KARMA');

		if ($mode === 'settings')
		{
			$this->tpl_name = 'acp_karma_settings';
			add_form_key('vinny_karma_settings');

			if ($request->is_set_post('submit'))
			{
				if (!check_form_key('vinny_karma_settings'))
				{
					trigger_error('FORM_INVALID', E_USER_WARNING);
				}

				$config->set('vinny_karma_enabled', $request->variable('vinny_karma_enabled', 0));
				$config->set('vinny_karma_enable_downvote', $request->variable('vinny_karma_enable_downvote', 0));
				$config->set('vinny_karma_flood_interval', $request->variable('vinny_karma_flood_interval', 0));

				$excluded_forums = $request->variable('vinny_karma_excluded_forums', array(0));
				$excluded_forums_str = implode(',', array_filter(array_map('intval', $excluded_forums)));
				$config->set('vinny_karma_excluded_forums', $excluded_forums_str);

				trigger_error($user->lang('VINNY_KARMA_SAVED') . adm_back_link($this->u_action));
			}

			// Excluded forums select
			if (!function_exists('make_forum_select'))
			{
				include($this->root_path . 'includes/functions_admin.' . $this->php_ext);
			}

			$current_excluded = explode(',', $config['vinny_karma_excluded_forums']);
			$forum_options = make_forum_select($current_excluded, false, true, false, false, false, false);

			$template->assign_vars(array(
				'VINNY_KARMA_ENABLED'			=> (int) $config['vinny_karma_enabled'],
				'VINNY_KARMA_ENABLE_DOWNVOTE'	=> (int) $config['vinny_karma_enable_downvote'],
				'VINNY_KARMA_FLOOD_INTERVAL'	=> (int) $config['vinny_karma_flood_interval'],
				'S_FORUM_OPTIONS'				=> $forum_options,
				'U_ACTION'						=> $this->u_action,
			));
		}
		else if ($mode === 'maintenance')
		{
			$this->tpl_name = 'acp_karma_maintenance';
			add_form_key('vinny_karma_maintenance');

			$action = '';
			if ($request->is_set_post('cancel'))
			{
				$action = '';
			}
			else if ($request->is_set_post('submit_resync'))
			{
				$action = 'resync';
			}
			else if ($request->is_set_post('submit_reset_user'))
			{
				$action = 'reset_user';
			}
			else if ($request->is_set_post('submit_prune'))
			{
				$action = 'prune';
			}

			if ($action)
			{
				if (($action !== 'reset_user' || !$request->is_set_post('confirm')) && !check_form_key('vinny_karma_maintenance'))
				{
					trigger_error('FORM_INVALID', E_USER_WARNING);
				}

				if ($action === 'resync')
				{
					// Recalculate post_karma for all posts
					$sql = 'UPDATE ' . POSTS_TABLE . ' p
						SET p.post_karma = (
							SELECT COALESCE(SUM(v.vote_direction), 0)
							FROM ' . $table_prefix . 'vinny_karma_votes v
							WHERE v.post_id = p.post_id
						)';
					$db->sql_query($sql);

					// Recalculate user_karma for all users
					$sql = 'UPDATE ' . USERS_TABLE . ' u
						SET u.user_karma = (
							SELECT COALESCE(SUM(p.post_karma), 0)
							FROM ' . POSTS_TABLE . ' p
							WHERE p.poster_id = u.user_id
						)';
					$db->sql_query($sql);

					trigger_error($user->lang('VINNY_KARMA_RESYNC_SUCCESS') . adm_back_link($this->u_action));
				}
				else if ($action === 'reset_user')
				{
					$username = $request->variable('username', '', true);

					if ($username === '')
					{
						trigger_error($user->lang('VINNY_KARMA_RESET_USER_NOT_FOUND') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$username_clean = utf8_clean_string($username);

					$sql = 'SELECT user_id, username
						FROM ' . USERS_TABLE . "
						WHERE username_clean = '" . $db->sql_escape($username_clean) . "'";
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					if (!$row)
					{
						trigger_error($user->lang('VINNY_KARMA_RESET_USER_NOT_FOUND') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$target_user_id = (int) $row['user_id'];

					if (confirm_box(true))
					{
						$db->sql_transaction('begin');
						try
						{
							// Delete votes cast by this user
							$sql = 'DELETE FROM ' . $table_prefix . 'vinny_karma_votes
								WHERE user_id = ' . (int) $target_user_id;
							$db->sql_query($sql);

							// Delete votes received on posts authored by this user
							$sql = 'DELETE FROM ' . $table_prefix . 'vinny_karma_votes
								WHERE post_id IN (
									SELECT post_id
									FROM ' . POSTS_TABLE . '
									WHERE poster_id = ' . (int) $target_user_id . '
								)';
							$db->sql_query($sql);

							// Reset karma score on their posts
							$sql = 'UPDATE ' . POSTS_TABLE . '
								SET post_karma = 0
								WHERE poster_id = ' . (int) $target_user_id;
							$db->sql_query($sql);

							// Reset user's own karma score
							$sql = 'UPDATE ' . USERS_TABLE . '
								SET user_karma = 0
								WHERE user_id = ' . (int) $target_user_id;
							$db->sql_query($sql);

							// Run resync queries to ensure everything is recalculated for other users
							$sql = 'UPDATE ' . POSTS_TABLE . ' p
								SET p.post_karma = (
									SELECT COALESCE(SUM(v.vote_direction), 0)
									FROM ' . $table_prefix . 'vinny_karma_votes v
									WHERE v.post_id = p.post_id
								)';
							$db->sql_query($sql);

							$sql = 'UPDATE ' . USERS_TABLE . ' u
								SET u.user_karma = (
									SELECT COALESCE(SUM(p.post_karma), 0)
									FROM ' . POSTS_TABLE . ' p
									WHERE p.poster_id = u.user_id
								)';
							$db->sql_query($sql);

							$db->sql_transaction('commit');
						}
						catch (\Exception $e)
						{
							$db->sql_transaction('rollback');
							trigger_error($e->getMessage() . adm_back_link($this->u_action), E_USER_WARNING);
						}

						trigger_error(sprintf($user->lang('VINNY_KARMA_RESET_USER_SUCCESS'), $row['username']) . adm_back_link($this->u_action));
					}
					else
					{
						confirm_box(false, sprintf($user->lang('VINNY_KARMA_CONFIRM_RESET'), $row['username']), build_hidden_fields(array(
							'username'			=> $username,
							'submit_reset_user'	=> 1,
							'i'					=> $id,
							'mode'				=> $mode,
						)));
					}
				}
				else if ($action === 'prune')
				{
					$prune_days = $request->variable('prune_days', 0);
					if ($prune_days <= 0)
					{
						trigger_error('FORM_INVALID', E_USER_WARNING);
					}

					$prune_time = time() - ($prune_days * 86400);

					$sql = 'DELETE FROM ' . $table_prefix . 'vinny_karma_votes
						WHERE vote_time < ' . (int) $prune_time;
					$db->sql_query($sql);
					$affected_rows = $db->sql_affectedrows();

					trigger_error(sprintf($user->lang('VINNY_KARMA_PRUNE_SUCCESS'), $affected_rows, $prune_days) . adm_back_link($this->u_action));
				}
			}

			// Render paginated Audit Log
			$sql = 'SELECT COUNT(vote_id) as total_votes
				FROM ' . $table_prefix . 'vinny_karma_votes';
			$result = $db->sql_query($sql);
			$total_votes = (int) $db->sql_fetchfield('total_votes');
			$db->sql_freeresult($result);

			$start = $request->variable('start', 0);
			$limit = 20;

			$sql = 'SELECT v.vote_direction, v.vote_time, v.post_id,
					u1.user_id as voter_id, u1.username as voter_username, u1.user_colour as voter_colour,
					u2.user_id as author_id, u2.username as author_username, u2.user_colour as author_colour,
					p.topic_id
				FROM ' . $table_prefix . 'vinny_karma_votes v
				LEFT JOIN ' . POSTS_TABLE . ' p ON v.post_id = p.post_id
				LEFT JOIN ' . USERS_TABLE . ' u1 ON v.user_id = u1.user_id
				LEFT JOIN ' . USERS_TABLE . ' u2 ON p.poster_id = u2.user_id
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
				'U_ACTION'			=> $this->u_action,
				'U_FIND_USERNAME'	=> append_sid($this->root_path . 'memberlist.' . $this->php_ext, 'mode=searchuser&amp;form=acp_karma_reset_user&amp;field=username&amp;select_single=true'),
			));
		}
	}
}
