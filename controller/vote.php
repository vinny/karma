<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\karma\controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
* phpBB Karma Extension Vote API Controller
*/
class vote
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\content_visibility */
	protected $content_visibility;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/** @var string */
	protected $table_prefix;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\content_visibility $content_visibility
	* @param \phpbb\db\driver\driver_interface $db
	* @param \phpbb\request\request $request
	* @param \phpbb\user $user
	* @param \phpbb\notification\manager $notification_manager
	* @param \phpbb\config\config $config
	* @param \phpbb\log\log $log
	* @param string $root_path
	* @param string $php_ext
	* @param string $table_prefix
	*/
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\content_visibility $content_visibility,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\user $user,
		\phpbb\notification\manager $notification_manager,
		\phpbb\config\config $config,
		\phpbb\log\log $log,
		$root_path,
		$php_ext,
		$table_prefix
	)
	{
		$this->auth = $auth;
		$this->content_visibility = $content_visibility;
		$this->db = $db;
		$this->request = $request;
		$this->user = $user;
		$this->notification_manager = $notification_manager;
		$this->config = $config;
		$this->log = $log;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->table_prefix = $table_prefix;
	}

	/**
	* Handle Upvote / Downvote API Request
	*
	* @param int $post_id
	* @param string $type
	* @return JsonResponse
	*/
	public function handle_vote($post_id, $type)
	{
		// Validate Vote Type
		if (!in_array($type, array('up', 'down'), true))
		{
			return new JsonResponse(array(
				'status'	=> 'error',
				'title'		=> $this->user->lang('KARMA'),
				'message'	=> $this->user->lang('KARMA_ERROR_VOTE_FAILED'),
			));
		}

		// Verify Authentication
		if (!$this->user->data['is_registered'] || $this->user->data['user_id'] == ANONYMOUS)
		{
			return new JsonResponse(array(
				'status'	=> 'error',
				'title'		=> $this->user->lang('KARMA'),
				'message'	=> $this->user->lang('KARMA_ERROR_MUST_LOG_IN')
			));
		}

		$user_id = (int) $this->user->data['user_id'];

		// Validate CSRF via link hash
		$hash = $this->request->variable('hash', '');
		if (empty($hash) || !check_link_hash($hash, 'vinny_karma'))
		{
			return new JsonResponse(array(
				'status'	=> 'error',
				'title'		=> $this->user->lang('KARMA'),
				'message'	=> $this->user->lang('FORM_INVALID')
			));
		}

		// Fetch Post Details and Forum Password
		$sql = 'SELECT p.poster_id, p.forum_id, p.topic_id, p.post_karma, p.post_visibility, f.forum_password
			FROM ' . POSTS_TABLE . ' p
			LEFT JOIN ' . FORUMS_TABLE . ' f ON (f.forum_id = p.forum_id)
			WHERE p.post_id = ' . (int) $post_id;
		$result = $this->db->sql_query($sql);
		$post = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$post || !$this->auth->acl_get('f_read', (int) $post['forum_id']) || !$this->content_visibility->is_visible('post', (int) $post['forum_id'], $post) || !$this->check_forum_password_access((int) $post['forum_id'], $post['forum_password']))
		{
			return new JsonResponse(array(
				'status'	=> 'error',
				'title'		=> $this->user->lang('KARMA'),
				'message'	=> $this->user->lang('KARMA_ERROR_POST_NOT_FOUND')
			));
		}

		// Verify Extension Enabled Config
		$enabled = isset($this->config['vinny_karma_enabled']) ? (bool) $this->config['vinny_karma_enabled'] : false;
		if (!$enabled)
		{
			return new JsonResponse(array(
				'status'	=> 'error',
				'title'		=> $this->user->lang('KARMA'),
				'message'	=> $this->user->lang('KARMA_ERROR_NO_PERMISSION')
			));
		}

		// Verify Forum is Not Excluded
		$excluded_forums = isset($this->config['vinny_karma_excluded_forums']) && $this->config['vinny_karma_excluded_forums'] !== '' ? explode(',', $this->config['vinny_karma_excluded_forums']) : array();
		if (in_array((int) $post['forum_id'], $excluded_forums))
		{
			return new JsonResponse(array(
				'status'	=> 'error',
				'title'		=> $this->user->lang('KARMA'),
				'message'	=> $this->user->lang('KARMA_ERROR_NO_PERMISSION')
			));
		}

		// Verify Downvotes are Enabled if Type is Downvote
		$enable_downvote = isset($this->config['vinny_karma_enable_downvote']) ? (bool) $this->config['vinny_karma_enable_downvote'] : true;
		if ($type === 'down' && !$enable_downvote)
		{
			return new JsonResponse(array(
				'status'	=> 'error',
				'title'		=> $this->user->lang('KARMA'),
				'message'	=> $this->user->lang('KARMA_ERROR_NO_PERMISSION')
			));
		}

		$poster_id = (int) $post['poster_id'];

		// Validate Global Permission
		if (!$this->auth->acl_get('u_karma_vote'))
		{
			return new JsonResponse(array(
				'status'	=> 'error',
				'title'		=> $this->user->lang('KARMA'),
				'message'	=> $this->user->lang('KARMA_ERROR_NO_PERMISSION')
			));
		}

		// Prevent Self-Voting
		if ($user_id === $poster_id)
		{
			return new JsonResponse(array(
				'status'	=> 'error',
				'title'		=> $this->user->lang('KARMA'),
				'message'	=> $this->user->lang('KARMA_ERROR_SELF_VOTE')
			));
		}

		// Anti-Flood Control (using custom config for voting flood interval)
		$flood_interval = isset($this->config['vinny_karma_flood_interval']) ? (int) $this->config['vinny_karma_flood_interval'] : 0;
		if ($flood_interval > 0)
		{
			$sql = 'SELECT MAX(vote_time) as last_vote_time
				FROM ' . $this->table_prefix . 'vinny_karma_votes
				WHERE user_id = ' . (int) $user_id;
			$result = $this->db->sql_query($sql);
			$last_vote_row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ($last_vote_row && isset($last_vote_row['last_vote_time']))
			{
				$last_vote_time = (int) $last_vote_row['last_vote_time'];
				$time_passed = time() - $last_vote_time;
				if ($time_passed < $flood_interval)
				{
					$remaining = $flood_interval - $time_passed;
					return new JsonResponse(array(
						'status'	=> 'error',
						'title'		=> $this->user->lang('KARMA'),
						'message'	=> sprintf($this->user->lang('KARMA_ERROR_FLOOD'), $remaining)
					));
				}
			}
		}

		// Determine new requested vote direction
		$new_direction = ($type === 'up') ? 1 : -1;

		// Get Existing Vote
		$sql = 'SELECT vote_id, vote_direction
			FROM ' . $this->table_prefix . 'vinny_karma_votes
			WHERE post_id = ' . (int) $post_id . '
				AND user_id = ' . (int) $user_id;
		$result = $this->db->sql_query($sql);
		$existing_vote = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$db_action = 'insert';
		$existing_vote_id = 0;
		$previous_direction = 0;

		if ($existing_vote)
		{
			$existing_vote_id = (int) $existing_vote['vote_id'];
			$previous_direction = (int) $existing_vote['vote_direction'];

			if ($previous_direction === $new_direction)
			{
				return new JsonResponse(array(
					'status'	=> 'error',
					'title'		=> $this->user->lang('KARMA'),
					'message'	=> $this->user->lang('KARMA_ERROR_ALREADY_VOTED')
				));
			}
			// If voting in the opposite direction, retract the current vote (reset to 0)
			$db_action = 'delete';
		}

		// Calculate Karma diffs
		if ($db_action === 'delete')
		{
			$vote_direction = 0;
			$karma_diff = -$previous_direction;
		}
		else
		{
			$vote_direction = $new_direction;
			$karma_diff = $new_direction - $previous_direction;
		}

		// Start Database Transaction
		$this->db->sql_transaction('begin');

		// A. Update Post Karma
		$sql = 'UPDATE ' . POSTS_TABLE . '
			SET post_karma = post_karma + ' . (int) $karma_diff . '
			WHERE post_id = ' . (int) $post_id;
		$this->db->sql_query($sql);

		// B. Update Author's User Karma (if the author is a registered user)
		if ($poster_id !== ANONYMOUS)
		{
			$sql = 'UPDATE ' . USERS_TABLE . '
				SET user_karma = user_karma + ' . (int) $karma_diff . '
				WHERE user_id = ' . (int) $poster_id;
			$this->db->sql_query($sql);
		}

		$new_vote_id = 0;

		// C. Write/Delete Vote tracking record
		if ($db_action === 'delete')
		{
			$sql = 'DELETE FROM ' . $this->table_prefix . 'vinny_karma_votes
				WHERE vote_id = ' . (int) $existing_vote_id;
			$this->db->sql_query($sql);
		}
		else
		{
			// Insert new vote
			$sql_arr = array(
				'post_id'			=> (int) $post_id,
				'user_id'			=> $user_id,
				'vote_direction'	=> $vote_direction,
				'vote_time'			=> time(),
			);
			$sql = 'INSERT INTO ' . $this->table_prefix . 'vinny_karma_votes ' . $this->db->sql_build_array('INSERT', $sql_arr);
			$this->db->sql_query($sql);
			$new_vote_id = (int) $this->db->sql_nextid();
		}

		// Commit Database Changes
		$this->db->sql_transaction('commit');

		// Delete notification if vote was retracted
		if ($db_action === 'delete' && $existing_vote_id)
		{
			$this->notification_manager->delete_notifications('vinny.karma.notification.type.karma_vote', (int) $existing_vote_id);
		}

		// Trigger Notification (if new vote is cast and author is not guest)
		if ($vote_direction !== 0 && $poster_id !== ANONYMOUS && $new_vote_id)
		{
			$this->notification_manager->add_notifications('vinny.karma.notification.type.karma_vote', array(
				'vote_id'			=> (int) $new_vote_id,
				'post_id'			=> (int) $post_id,
				'topic_id'			=> (int) $post['topic_id'],
				'post_author_id'	=> $poster_id,
				'voter_id'			=> $user_id,
				'vote_type'			=> ($vote_direction === 1) ? 'up' : 'down',
				'vote_username'		=> $this->user->data['username'],
			));
		}

		// Fetch updated Post & Author Karma to return accurate state
		$sql = 'SELECT post_karma FROM ' . POSTS_TABLE . ' WHERE post_id = ' . (int) $post_id;
		$result = $this->db->sql_query($sql);
		$updated_post = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$updated_user_karma = 0;
		if ($poster_id !== ANONYMOUS)
		{
			$sql = 'SELECT user_karma FROM ' . USERS_TABLE . ' WHERE user_id = ' . (int) $poster_id;
			$result = $this->db->sql_query($sql);
			$updated_user = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
			$updated_user_karma = (int) $updated_user['user_karma'];
		}

		return new JsonResponse(array(
			'status' => 'success',
			'post_karma'		=> (int) $updated_post['post_karma'],
			'user_karma'		=> $updated_user_karma,
			'poster_id'			=> $poster_id,
			'vote_direction'	=> $vote_direction,
		));
	}

	/**
	* Check if the current user has access to a password-protected forum
	*
	* @param int $forum_id
	* @param string $forum_password
	* @return bool
	*/
	protected function check_forum_password_access($forum_id, $forum_password)
	{
		if (empty($forum_password))
		{
			return true;
		}

		$sql = 'SELECT forum_id
			FROM ' . FORUMS_ACCESS_TABLE . '
			WHERE forum_id = ' . (int) $forum_id . '
				AND user_id = ' . (int) $this->user->data['user_id'] . "
				AND session_id = '" . $this->db->sql_escape($this->user->session_id) . "'";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return (bool) $row;
	}

	/**
	* Reset Karma Score for a specific Post
	*
	* @param int $post_id
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function reset_post_karma($post_id)
	{
		// Verify moderator permission
		if (!$this->auth->acl_get('m_karma_manage'))
		{
			trigger_error('NOT_AUTHORISED', E_USER_WARNING);
		}

		// Validate CSRF via link hash
		$hash = $this->request->variable('hash', '');
		if (empty($hash) || !check_link_hash($hash, 'vinny_karma'))
		{
			trigger_error('FORM_INVALID', E_USER_WARNING);
		}

		$post_id = (int) $post_id;

		// Fetch Post and Author Details
		$sql = 'SELECT p.poster_id, p.topic_id, p.forum_id, p.post_visibility, f.forum_password
			FROM ' . POSTS_TABLE . ' p
			LEFT JOIN ' . FORUMS_TABLE . ' f ON (f.forum_id = p.forum_id)
			WHERE p.post_id = ' . (int) $post_id;
		$result = $this->db->sql_query($sql);
		$post = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$post || !$this->auth->acl_get('f_read', (int) $post['forum_id']) || !$this->content_visibility->is_visible('post', (int) $post['forum_id'], $post) || !$this->check_forum_password_access((int) $post['forum_id'], $post['forum_password']))
		{
			trigger_error('NO_POST', E_USER_WARNING);
		}

		$poster_id = (int) $post['poster_id'];
		$topic_id = (int) $post['topic_id'];

		$redirect_url = append_sid($this->root_path . 'viewtopic.' . $this->php_ext, 'p=' . $post_id . '#p' . $post_id);

		if ($this->request->is_set_post('cancel'))
		{
			return new \Symfony\Component\HttpFoundation\RedirectResponse($redirect_url);
		}

		// If user clicked "Confirm", delete
		if (confirm_box(true))
		{
			$this->db->sql_transaction('begin');

			// Find and delete notifications for all votes of this post
				$sql = 'SELECT vote_id
					FROM ' . $this->table_prefix . 'vinny_karma_votes
					WHERE post_id = ' . (int) $post_id;
				$result = $this->db->sql_query($sql);
				$vote_ids = array();
				while ($v_row = $this->db->sql_fetchrow($result))
				{
					$vote_ids[] = (int) $v_row['vote_id'];
				}
				$this->db->sql_freeresult($result);

				if (!empty($vote_ids))
				{
					$this->notification_manager->delete_notifications('vinny.karma.notification.type.karma_vote', $vote_ids);
				}

				// Delete all votes for this post
				$sql = 'DELETE FROM ' . $this->table_prefix . 'vinny_karma_votes
					WHERE post_id = ' . (int) $post_id;
				$this->db->sql_query($sql);

				// Reset post karma
				$sql = 'UPDATE ' . POSTS_TABLE . '
					SET post_karma = 0
					WHERE post_id = ' . (int) $post_id;
				$this->db->sql_query($sql);

				// Recalculate poster total karma (re-sync)
				if ($poster_id !== ANONYMOUS)
				{
					$sql = 'UPDATE ' . USERS_TABLE . '
						SET user_karma = user_karma_adjustment + (
							SELECT COALESCE(SUM(post_karma), 0)
							FROM ' . POSTS_TABLE . '
							WHERE poster_id = ' . (int) $poster_id . '
						)
						WHERE user_id = ' . (int) $poster_id;
					$this->db->sql_query($sql);
				}

				$this->db->sql_transaction('commit');

				// Fetch poster username for logging
				$poster_name = $this->user->lang['GUEST'];
				if ($poster_id !== ANONYMOUS)
				{
					$sql = 'SELECT username FROM ' . USERS_TABLE . ' WHERE user_id = ' . (int) $poster_id;
					$res = $this->db->sql_query($sql);
					$poster_name = (string) $this->db->sql_fetchfield('username');
					$this->db->sql_freeresult($res);
				}

				// Log to moderator logs
				$this->log->add('mod', $this->user->data['user_id'], $this->user->ip, 'LOG_MCP_KARMA_RESET_POST', time(), array(
					'forum_id'	=> (int) $post['forum_id'],
					'topic_id'	=> (int) $post['topic_id'],
					'post_id'	=> (int) $post_id,
					$poster_name
				));

			meta_refresh(3, $redirect_url);
			trigger_error($this->user->lang('VINNY_KARMA_MCP_RESET_POST_SUCCESS', $post_id) . '<br /><br />' . sprintf($this->user->lang['RETURN_PAGE'], '<a href="' . $redirect_url . '">', '</a>'));
		}
		else
		{
			confirm_box(false, $this->user->lang('VINNY_KARMA_MCP_RESET_POST_CONFIRM'), build_hidden_fields(array(
				'hash'	=> $hash,
			)));
		}
	}
}
