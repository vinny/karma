<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\karma\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* phpBB Karma Extension Event Listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/** @var string */
	protected $table_prefix;

	/** @var array|null */
	protected $user_votes = null;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\controller\helper $helper
	* @param \phpbb\db\driver\driver_interface $db
	* @param \phpbb\request\request $request
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param \phpbb\config\config $config
	* @param string $root_path
	* @param string $php_ext
	* @param string $table_prefix
	*/
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\controller\helper $helper,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\config\config $config,
		$root_path,
		$php_ext,
		$table_prefix
	) {
		$this->auth = $auth;
		$this->helper = $helper;
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->config = $config;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->table_prefix = $table_prefix;
	}

	/**
	* Subscribed events
	*
	* @return array
	*/
	public static function getSubscribedEvents()
	{
		return array(
			'core.user_setup' => 'user_setup',
			'core.permissions' => 'add_permissions',
			'core.viewtopic_post_rowset_data' => 'viewtopic_post_rowset_data',
			'core.viewtopic_cache_user_data' => 'viewtopic_cache_user_data',
			'core.viewtopic_modify_post_row' => 'viewtopic_modify_post_row',
			'core.memberlist_modify_view_profile_template_vars' => 'memberlist_modify_view_profile_template_vars',
			'core.page_header' => 'page_header',
			'core.modify_mcp_modules_display_option' => 'modify_mcp_modules_display_option',
		);
	}

	/**
	* Load language files
	*
	* @param \phpbb\event\data $event
	*/
	public function user_setup($event)
	{
		$this->user->add_lang('mcp');

		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'vinny/karma',
			'lang_set' => 'karma',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	* Register permissions
	*
	* @param \phpbb\event\data $event
	*/
	public function add_permissions($event)
	{
		$categories = $event['categories'];
		$categories['karma_system'] = 'ACL_CAT_KARMA_SYSTEM';
		$event['categories'] = $categories;

		$permissions = $event['permissions'];

		$permissions['u_karma_view'] = array('lang' => 'ACL_U_KARMA_VIEW', 'cat' => 'karma_system');
		$permissions['u_karma_vote'] = array('lang' => 'ACL_U_KARMA_VOTE', 'cat' => 'karma_system');
		$permissions['u_karma_ranking'] = array('lang' => 'ACL_U_KARMA_RANKING', 'cat' => 'karma_system');
		$permissions['m_karma_manage'] = array('lang' => 'ACL_M_KARMA_MANAGE', 'cat' => 'karma_system');

		$event['permissions'] = $permissions;
	}

	/**
	* Inject Karma URLs and data into viewtopic post rows
	*
	* @param \phpbb\event\data $event
	*/
	public function viewtopic_modify_post_row($event)
	{
		$row = $event['row'];
		$post_row = $event['post_row'];
		$topic_id = (int) $row['topic_id'];
		$post_id = (int) $row['post_id'];

		$poster_id = isset($row['poster_id']) ? (int) $row['poster_id'] : (isset($row['user_id']) ? (int) $row['user_id'] : (isset($event['user_poster_data']['user_id']) ? (int) $event['user_poster_data']['user_id'] : ANONYMOUS));

		$forum_id = isset($row['forum_id']) ? (int) $row['forum_id'] : 0;

		$enabled = isset($this->config['vinny_karma_enabled']) ? (bool) $this->config['vinny_karma_enabled'] : false;
		$excluded_forums = isset($this->config['vinny_karma_excluded_forums']) && $this->config['vinny_karma_excluded_forums'] !== '' ? explode(',', $this->config['vinny_karma_excluded_forums']) : array();

		// Keep track of permissions
		$can_view = $enabled && !in_array($forum_id, $excluded_forums) && $this->auth->acl_get('u_karma_view');
		$can_vote = $enabled && !in_array($forum_id, $excluded_forums) && $this->auth->acl_get('u_karma_vote');

		// Assign global template variable for script/stylesheet inclusion checks
		static $global_assigned = false;
		if (!$global_assigned)
		{
			$this->template->assign_vars(array(
				'S_SHOW_KARMA' => $can_view,
				'S_KARMA_ENABLE_DOWNVOTE' => isset($this->config['vinny_karma_enable_downvote']) ? (bool) $this->config['vinny_karma_enable_downvote'] : true,
				'L_KARMA_UPVOTE' => $this->user->lang('KARMA_UPVOTE'),
				'L_KARMA_DOWNVOTE' => $this->user->lang('KARMA_DOWNVOTE'),
				'L_KARMA_ALREADY_VOTED_UP' => $this->user->lang('KARMA_ALREADY_VOTED_UP'),
				'L_KARMA_ALREADY_VOTED_DOWN' => $this->user->lang('KARMA_ALREADY_VOTED_DOWN'),
				'L_KARMA_ERROR_VOTE_FAILED' => $this->user->lang('KARMA_ERROR_VOTE_FAILED'),
			));
			$global_assigned = true;
		}

		$post_karma = isset($row['post_karma']) ? (int) $row['post_karma'] : 0;

		$is_registered = ($this->user->data['is_registered'] && $this->user->data['user_id'] != ANONYMOUS);
		$is_own_post = ($poster_id === (int) $this->user->data['user_id']);

		// Initialize and cache topic votes for the current user
		if ($this->user_votes === null)
		{
			$this->user_votes = array();
			if ($is_registered)
			{
				$sql = 'SELECT post_id, vote_direction
					FROM ' . $this->table_prefix . 'vinny_karma_votes
					WHERE user_id = ' . (int) $this->user->data['user_id'] . '
						AND post_id IN (
							SELECT post_id
							FROM ' . POSTS_TABLE . '
							WHERE topic_id = ' . (int) $topic_id . '
						)';
				$result = $this->db->sql_query($sql);
				while ($vote = $this->db->sql_fetchrow($result))
				{
					$this->user_votes[(int) $vote['post_id']] = (int) $vote['vote_direction'];
				}
				$this->db->sql_freeresult($result);
			}
		}

		$vote_direction = isset($this->user_votes[$post_id]) ? $this->user_votes[$post_id] : 0;

		$user_poster_data = $event['user_poster_data'];
		$user_karma = isset($user_poster_data['user_karma']) ? (int) $user_poster_data['user_karma'] : 0;

		$can_manage_karma = $this->auth->acl_get('m_karma_manage');

		$karma_row_data = array(
			'S_KARMA_VIEW' => $can_view && !$is_own_post,
			'S_KARMA_VOTE' => $can_vote && $is_registered && !$is_own_post,
			'S_IS_OWN_POST' => $is_own_post,
			'POST_KARMA' => $post_karma,
			'POSTER_KARMA' => $user_karma,
			'S_SHOW_KARMA' => $can_view,
			'S_VOTED_UP' => ($vote_direction === 1),
			'S_VOTED_DOWN' => ($vote_direction === -1),
			'U_UPVOTE_API' => $this->helper->route('vinny_karma_vote_api', array('post_id' => $post_id, 'type' => 'up', 'hash' => generate_link_hash('vinny_karma'))),
			'U_DOWNVOTE_API' => $this->helper->route('vinny_karma_vote_api', array('post_id' => $post_id, 'type' => 'down', 'hash' => generate_link_hash('vinny_karma'))),
			'S_CAN_MANAGE_KARMA' => $can_manage_karma,
			'U_RESET_POST_KARMA' => $can_manage_karma ? $this->helper->route('vinny_karma_reset_post', array('post_id' => $post_id, 'hash' => generate_link_hash('vinny_karma'))) : '',
		);

		$event['post_row'] = array_merge($post_row, $karma_row_data);
	}

	/**
	* Inject user global karma into memberlist user profile page
	*
	* @param \phpbb\event\data $event
	*/
	public function memberlist_modify_view_profile_template_vars($event)
	{
		$template_ary = $event['template_ary'];
		$user_id = (int) $event['user_id'];

		$sql = 'SELECT user_karma
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int) $user_id;
		$result = $this->db->sql_query($sql);
		$user_karma = (int) $this->db->sql_fetchfield('user_karma');
		$this->db->sql_freeresult($result);

		$enabled = isset($this->config['vinny_karma_enabled']) ? (bool) $this->config['vinny_karma_enabled'] : false;
		$can_view = $enabled && $this->auth->acl_get('u_karma_view');
		$can_manage = $enabled && $this->auth->acl_get('m_karma_manage');

		$template_ary['USER_KARMA'] = $user_karma;
		$template_ary['S_SHOW_KARMA'] = $can_view;
		$template_ary['S_CAN_MANAGE_KARMA'] = $can_manage;
		$template_ary['U_MCP_KARMA_MANAGE'] = $can_manage ? append_sid($this->root_path . 'mcp.' . $this->php_ext, 'i=-vinny-karma-mcp-main_module&amp;mode=karma_user_details&amp;u=' . $user_id) : '';

		$event['template_ary'] = $template_ary;
	}

	/**
	* Cache post karma in viewtopic rowset
	*
	* @param \phpbb\event\data $event
	*/
	public function viewtopic_post_rowset_data($event)
	{
		$row = $event['row'];
		$rowset_data = $event['rowset_data'];

		$rowset_data['post_karma'] = isset($row['post_karma']) ? (int) $row['post_karma'] : 0;

		$event['rowset_data'] = $rowset_data;
	}

	/**
	* Cache user karma in viewtopic user cache
	*
	* @param \phpbb\event\data $event
	*/
	public function viewtopic_cache_user_data($event)
	{
		$row = $event['row'];
		$user_cache_data = $event['user_cache_data'];

		$user_cache_data['user_karma'] = isset($row['user_karma']) ? (int) $row['user_karma'] : 0;

		$event['user_cache_data'] = $user_cache_data;
	}

	/**
	* Hook to add navbar variables
	*
	* @param \phpbb\event\data $event
	*/
	public function page_header($event)
	{
		$enabled = isset($this->config['vinny_karma_enabled']) ? (bool) $this->config['vinny_karma_enabled'] : false;
		if ($enabled && $this->auth->acl_get('u_karma_ranking'))
		{
			$this->template->assign_vars(array(
				'S_ALLOW_KARMA_RANKING'	=> true,
				'U_KARMA_RANKING'		=> $this->helper->route('vinny_karma_ranking'),
			));
		}
	}

	/**
	* Toggle display of the karma_user_details tab based on user selection
	*
	* @param \phpbb\event\data $event
	*/
	public function modify_mcp_modules_display_option($event)
	{
		$user_id = (int) $event['user_id'];
		$username = $event['username'];
		$module = $event['module'];

		if (!$user_id && $username === '')
		{
			$module->set_display('\vinny\karma\mcp\main_module', 'karma_user_details', false);
		}
	}
}
