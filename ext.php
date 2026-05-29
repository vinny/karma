<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\karma;

/**
* phpBB Karma Extension main class
*/
class ext extends \phpbb\extension\base
{
	/**
	* Run steps during enable
	*
	* @param mixed $old_state Old state of migration
	* @return mixed
	*/
	public function enable_step($old_state)
	{
		if ($old_state === false)
		{
			$this->container->get('notification_manager')
				->enable_notifications('vinny.karma.notification.type.karma_vote');
			return 'notification';
		}
		return parent::enable_step($old_state);
	}

	/**
	* Run steps during disable
	*
	* @param mixed $old_state Old state of migration
	* @return mixed
	*/
	public function disable_step($old_state)
	{
		if ($old_state === false)
		{
			$this->container->get('notification_manager')
				->disable_notifications('vinny.karma.notification.type.karma_vote');
			return 'notification';
		}
		return parent::disable_step($old_state);
	}

	/**
	* Run steps during purge
	*
	* @param mixed $old_state Old state of migration
	* @return mixed
	*/
	public function purge_step($old_state)
	{
		if ($old_state === false)
		{
			$this->container->get('notification_manager')
				->purge_notifications('vinny.karma.notification.type.karma_vote');
			return 'notification';
		}
		return parent::purge_step($old_state);
	}
}
