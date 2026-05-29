<?php
/**
*
* Karma System extension for the phpBB Forum Software package.
*
* @copyright (c) _Vinny_ <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\karma\tests\functional;

class ranking_test extends \phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('vinny/karma');
	}

	public function test_ranking_page()
	{
		$crawler = $this->request('GET', 'app.php/karma/ranking');
		$this->assertStringContainsString('Karma Ranking', $this->get_contents());
	}
}
