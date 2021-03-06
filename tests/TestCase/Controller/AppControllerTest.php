<?php
namespace App\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestCase;

class AppControllerTest extends IntegrationTestCase {
	public $fixtures = array(
		'app.users',
		'app.users_languages',
		'app.private_messages',
	);

	function setUp() {
		parent::setUp();

		Configure::write('UI.languages', [
			['cmn', 'Hans', '中文', ['chi']],
			['eng', null, 'English'],
			['jbo', null, 'Lojban'],
			['jpn', null, '日本語'],
			['pt_BR', 'BR', 'Português (BR)'],
		]);
		Configure::write('Security.cookieSalt', 'nCwygQoRC5EgFHDRNkdWS6hps74V3y9Z');
	}

	function setInterfaceLanguageCookie($lang = null) {
		$this->cookie('CakeCookie', ['interfaceLanguage' => $lang]);
	}

	function assertInterfaceLanguageCookie($lang) {
		$this->assertCookie(json_encode(['interfaceLanguage' => $lang]), 'CakeCookie');
	}

	function testBeforeFilter_redirectsToEnglishByDefault() {
		$this->get('/about');
		$this->assertRedirect('/eng/about');
	}

	function testBeforeFilter_redirectsToEnglishByDefaultWithIndexAction() {
		$this->get('/wall/index');
		$this->assertRedirect('/eng/wall/index');
	}

	function testBeforeFilter_redirectsToLanguageInCookie() {
		$this->setInterfaceLanguageCookie('jpn');
		$this->get('/eng/about');
		$this->assertRedirect('/jpn/about');
	}

	function testBeforeFilter_redirectsToLanguageInCookieWithoutLanguageInUrl() {
		$this->setInterfaceLanguageCookie('jpn');
		$this->get('/about');
		$this->assertRedirect('/jpn/about');
	}

	function testBeforeFilter_doesntRedirectIfLanguageInCookieEqualsLanguageInUrl() {
		$this->setInterfaceLanguageCookie('eng');
		$this->get('/eng/about');
		$this->assertNoRedirect();
	}

	function testBeforeFilter_doesntRedirectIfEnglishWithoutCookie() {
		$this->get('/eng/about');
		$this->assertNoRedirect();
		$this->assertInterfaceLanguageCookie('eng');
	}

	function testBeforeFilter_redirectsFromOldAliasWithLangInUrl() {
		$this->get('/chi/about');
		$this->assertRedirect('/cmn/about');
	}

	function testBeforeFilter_redirectsFromOldAliasWithCookie() {
		$this->setInterfaceLanguageCookie('chi');
		$this->get('/about');
		$this->assertRedirect('/chi/about');
	}

	function testBeforeFilter_redirectsFromOldAliasWithCookieWithLangInUrl() {
		$this->setInterfaceLanguageCookie('chi');
		$this->get('/chi/about');
		$this->assertRedirect('/cmn/about');
	}

	function testBeforeFilter_updatesCookieFromOldAlias() {
		$this->setInterfaceLanguageCookie('chi');
		$this->get('/cmn/about');
		$this->assertNoRedirect();
		$this->assertInterfaceLanguageCookie('cmn');
	}

	function setRememberMeCookie($username, $password) {
		$this->cookieEncrypted(
			'User',
			compact('username', 'password'),
			'aes',
			Configure::read('Security.cookieSalt')
		);
	}

	function testRememberMeAutomaticallyLogsInUser() {
		$this->setRememberMeCookie(
			'contributor',
			'0 $2a$10$Dn8/JT1xViULUEBCR5HiquLCXXB4/K3N2Nzc0PRZ.bfbmoApO55l6'
		);
		$this->get('/eng/about');

		$this->assertSession(4, 'Auth.User.id');
	}

	function testRememberMeFailsIfIncorrectPassword() {
		$this->setRememberMeCookie(
			'contributor',
			'0 $2a$10$Dn8/JT1xViULUEBCR5HiquLCXXB4/K3N2Nzc0PRZ.bfbmoApO55l4'
		);
		$this->get('/eng/about');

		$this->assertSession(null, 'Auth.User.id');
	}
}
