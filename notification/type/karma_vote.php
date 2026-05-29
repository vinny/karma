<?php
/**
*
* @package karma
* @copyright (c) 2026 Vinny
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace vinny\karma\notification\type;

/**
* phpBB Karma Extension Notification Type
*/
class karma_vote extends \phpbb\notification\type\base
{
	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\user_loader */
	protected $user_loader;

	/**
	* Set controller helper
	*
	* @param \phpbb\controller\helper $helper
	*/
	public function set_helper(\phpbb\controller\helper $helper)
	{
		$this->helper = $helper;
	}

	/**
	* Set user loader
	*
	* @param \phpbb\user_loader $user_loader
	*/
	public function set_user_loader(\phpbb\user_loader $user_loader)
	{
		$this->user_loader = $user_loader;
	}

	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
		return 'vinny.karma.notification.type.karma_vote';
	}

	/**
	* Notification option settings for UCP/ACP
	*
	* @var array
	*/
	static public $notification_option = array(
		'lang'	=> 'NOTIFICATION_TYPE_KARMA_VOTE',
		'group'	=> 'NOTIFICATION_GROUP_POSTING',
	);

	/**
	* Check if notification type is available
	*
	* @return bool
	*/
	public function is_available()
	{
		return true;
	}

	/**
	* Get item ID (post ID)
	*
	* @param array $data Notification data
	* @return int
	*/
	static public function get_item_id($data)
	{
		return (int) $data['post_id'];
	}

	/**
	* Get item parent ID (topic ID)
	*
	* @param array $data Notification data
	* @return int
	*/
	static public function get_item_parent_id($data)
	{
		return (int) $data['topic_id'];
	}

	/**
	* Find users to notify
	*
	* @param array $data Notification data
	* @param array $options Options array
	* @return array
	*/
	public function find_users_for_notification($data, $options = array())
	{
		$users = array((int) $data['post_author_id']);

		if ($users[0] === ANONYMOUS)
		{
			return array();
		}

		return $this->check_user_notification_options($users, $options);
	}

	/**
	* Get users to query during loading
	*
	* @return array
	*/
	public function users_to_query()
	{
		return array((int) $this->get_data('voter_id'));
	}

	/**
	* Prepare data for database insertion
	*
	* @param array $data Data array
	* @param array $pre_insert_data Pre-insertion data
	* @return array
	*/
	public function create_insert_array($data, $pre_insert_data = array())
	{
		$this->set_data('post_id', (int) $data['post_id']);
		$this->set_data('topic_id', (int) $data['topic_id']);
		$this->set_data('voter_id', (int) $data['voter_id']);
		$this->set_data('vote_type', $data['vote_type']);

		return parent::create_insert_array($data, $pre_insert_data);
	}

	/**
	* Get notification title/text
	*
	* @return string
	*/
	public function get_title()
	{
		$voter_id = (int) $this->get_data('voter_id');
		$username = $this->user_loader->get_username($voter_id, 'no_profile');
		$vote_type = $this->get_data('vote_type');

		if ($vote_type === 'up')
		{
			return $this->language->lang('NOTIFICATION_KARMA_UPVOTE', $username);
		}
		else
		{
			return $this->language->lang('NOTIFICATION_KARMA_DOWNVOTE', $username);
		}
	}

	/**
	* Get notification URL
	*
	* @return string
	*/
	public function get_url()
	{
		return append_sid($this->phpbb_root_path . 'viewtopic.' . $this->php_ext, 'p=' . (int) $this->get_data('post_id')) . '#p' . (int) $this->get_data('post_id');
	}

	/**
	* Get the voter's avatar
	*
	* @return string
	*/
	public function get_avatar()
	{
		return $this->user_loader->get_avatar((int) $this->get_data('voter_id'), false, true);
	}

	/**
	* Get email template name
	*
	* @return bool|string
	*/
	public function get_email_template()
	{
		return false;
	}

	/**
	* Get email template variables
	*
	* @return array
	*/
	public function get_email_template_variables()
	{
		return array();
	}
}
