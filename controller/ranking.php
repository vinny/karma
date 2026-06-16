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

use Symfony\Component\HttpFoundation\Response;

/**
* phpBB Karma Extension Ranking Controller
*/
class ranking
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

	/** @var \phpbb\pagination */
	protected $pagination;

	/** @var \phpbb\config\config */
	protected $config;

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
	* @param \phpbb\controller\helper $helper
	* @param \phpbb\db\driver\driver_interface $db
	* @param \phpbb\request\request $request
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param \phpbb\pagination $pagination
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
		\phpbb\pagination $pagination,
		\phpbb\config\config $config,
		$root_path,
		$php_ext,
		$table_prefix
	)
	{
		$this->auth = $auth;
		$this->helper = $helper;
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->pagination = $pagination;
		$this->config = $config;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->table_prefix = $table_prefix;
	}

	/**
	* Display Karma Ranking page
	*
	* @return Response
	*/
	public function display()
	{
		// Verify Enabled Config
		$enabled = isset($this->config['vinny_karma_enabled']) ? (bool) $this->config['vinny_karma_enabled'] : false;
		if (!$enabled || !$this->auth->acl_get('u_karma_ranking'))
		{
			trigger_error('NO_PERMISSION');
		}

		// 2. Load Display Functions for Avatar helper
		if (!function_exists('phpbb_get_user_avatar'))
		{
			include($this->root_path . 'includes/functions_display.' . $this->php_ext);
		}

		// 3. Fetch Top 50 Ranking Rows
		$ranking_data = array();
		$sql = 'SELECT user_id, username, user_colour, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_karma
			FROM ' . USERS_TABLE . '
			WHERE user_type IN (' . USER_NORMAL . ', ' . USER_FOUNDER . ')
				AND user_id <> ' . ANONYMOUS . '
				AND user_karma <> 0
			ORDER BY user_karma DESC, username_clean ASC';
		$result = $this->db->sql_query_limit($sql, 50, 0, 300);

		$position = 1;
		while ($row = $this->db->sql_fetchrow($result))
		{
			$avatar = phpbb_get_user_avatar(array(
				'avatar'		=> $row['user_avatar'],
				'avatar_type'	=> $row['user_avatar_type'],
				'avatar_width'	=> $row['user_avatar_width'],
				'avatar_height'	=> $row['user_avatar_height'],
			));

			$ranking_data[] = array(
				'POSITION'		=> $position,
				'AVATAR'		=> $avatar,
				'USER_ID'		=> (int) $row['user_id'],
				'USERNAME'		=> $row['username'],
				'USER_COLOUR'	=> $row['user_colour'],
				'USER_FULL'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'USER_PROFILE'	=> get_username_string('profile', $row['user_id'], $row['username'], $row['user_colour']),
				'TOTAL_KARMA'	=> (int) $row['user_karma'],
			);
			$position++;
		}
		$this->db->sql_freeresult($result);

		// 4. Assign Template Loop
		foreach ($ranking_data as $row_data)
		{
			$this->template->assign_block_vars('ranking_row', $row_data);
		}

		return $this->helper->render('@vinny_karma/karma_ranking.html', 'KARMA_RANKING');
	}
}
