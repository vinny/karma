<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\karma\tests\controller;

class vote_test extends \phpbb_test_case
{
	protected $controller;
	protected $auth;
	protected $helper;
	protected $db;
	protected $request;
	protected $user;
	protected $notification_manager;
	protected $config;
	protected $log;

	public function setUp(): void
	{
		parent::setUp();

		$this->auth = $this->getMockBuilder('\phpbb\auth\auth')
			->disableOriginalConstructor()->getMock();
		$this->helper = $this->getMockBuilder('\phpbb\controller\helper')
			->disableOriginalConstructor()->getMock();
		$this->db = $this->getMockBuilder('\phpbb\db\driver\driver_interface')
			->disableOriginalConstructor()->getMock();
		$this->request = $this->getMockBuilder('\phpbb\request\request')
			->disableOriginalConstructor()->getMock();
		$this->user = $this->getMockBuilder('\phpbb\user')
			->disableOriginalConstructor()->getMock();
		$this->notification_manager = $this->getMockBuilder('\phpbb\notification\manager')
			->disableOriginalConstructor()->getMock();
		$this->config = $this->getMockBuilder('\phpbb\config\config')
			->disableOriginalConstructor()->getMock();
		$this->log = $this->getMockBuilder('\phpbb\log\log')
			->disableOriginalConstructor()->getMock();

		$this->controller = new \vinny\karma\controller\vote(
			$this->auth,
			$this->helper,
			$this->db,
			$this->request,
			$this->user,
			$this->notification_manager,
			$this->config,
			$this->log,
			'./',
			'php',
			'phpbb_'
		);

		global $user;
		$user = $this->user;
	}

	public function test_handle_vote_not_logged_in()
	{
		$this->user->data = array(
			'is_registered' => false,
			'user_id' => 1 // ANONYMOUS
		);

		$this->user->expects($this->any())
			->method('lang')
			->will($this->returnCallback(function($key) {
				return $key;
			}));

		$response = $this->controller->handle_vote(123, 'up');
		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\JsonResponse', $response);

		$data = json_decode($response->getContent(), true);
		$this->assertEquals('error', $data['status']);
		$this->assertEquals('KARMA_ERROR_MUST_LOG_IN', $data['message']);
	}

	public function test_handle_vote_invalid_csrf()
	{
		$this->user->data = array(
			'is_registered' => true,
			'user_id' => 2,
			'user_form_salt' => 'some_salt',
		);

		$this->user->expects($this->any())
			->method('lang')
			->will($this->returnCallback(function($key) {
				return $key;
			}));

		$this->request->expects($this->once())
			->method('variable')
			->with('hash', '')
			->willReturn('invalid_hash');

		$response = $this->controller->handle_vote(123, 'up');
		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\JsonResponse', $response);

		$data = json_decode($response->getContent(), true);
		$this->assertEquals('error', $data['status']);
		$this->assertEquals('FORM_INVALID', $data['message']);
	}

	public function test_handle_vote_no_forum_read_permission()
	{
		$this->user->data = array(
			'is_registered' => true,
			'user_id' => 2,
			'user_form_salt' => 'some_salt',
		);

		$this->user->expects($this->any())
			->method('lang')
			->will($this->returnCallback(function($key) {
				return $key;
			}));

		$valid_hash = generate_link_hash('vinny_karma');

		$this->request->expects($this->once())
			->method('variable')
			->with('hash', '')
			->willReturn($valid_hash);

		$this->db->expects($this->once())
			->method('sql_query')
			->willReturn('result_resource');

		$this->db->expects($this->once())
			->method('sql_fetchrow')
			->willReturn(array(
				'poster_id' => 10,
				'forum_id' => 5,
				'topic_id' => 20,
				'post_karma' => 0,
			));

		$this->auth->expects($this->once())
			->method('acl_get')
			->with('f_read', 5)
			->willReturn(false);

		$response = $this->controller->handle_vote(123, 'up');
		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\JsonResponse', $response);

		$data = json_decode($response->getContent(), true);
		$this->assertEquals('error', $data['status']);
		$this->assertEquals('KARMA_ERROR_NO_PERMISSION', $data['message']);
	}
}
