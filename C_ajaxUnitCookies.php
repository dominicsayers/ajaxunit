<?php
// ---------------------------------------------------------------------------
// 		ajaxUnitCookies
// ---------------------------------------------------------------------------
/**
 * @package	ajaxUnit
 * @author	Dominic Sayers <dominic_sayers@hotmail.com>
 * @copyright	2009 Dominic Sayers
 * @license	http://www.opensource.org/licenses/cpal_1.0 Common Public Attribution License Version 1.0 (CPAL) license
 * @link	http://code.google.com/p/ajaxunit/
 * @version	0.17 - Now with XInclude so you can componentize your test scripts (see examples)
 */

class ajaxUnitCookies {
	public static function get($name) {return (isset($_COOKIE[$name])) ? $_COOKIE[$name] : '';}

	public static function set($name, $value, $days = 0, $path = '/', $domain = '') {
		$expiry = ($days === 0) ? 0 : time() + 60 * 60 * 24 * $days;
		if ($domain = '') setcookie($name, $value, $expiry, $path); else setcookie($name, $value, $expiry, $path, $domain);
		return "Setting cookie '$name' to [$value] until " . date(DateTime::COOKIE, $expiry);
	}

	public static function remove($name) {
		if (!isset($_COOKIE[$name])) return "Cookie $name doesn't exist";
		return self::set($name, false, -1);
	}

	public static function show($name = '', $tableTop = true, $tableBottom = true) {
		if ($tableTop) echo "<table>\n";

		if ($name === '') {
			$cookieCount	= count($_COOKIE);
			$keys		= array_keys($_COOKIE);

			for ($i = 0; $i < $cookieCount; $i++) {
				$name = $keys[$i];
				self::show($name, false, false);
			}
		} else {
			$value = self::get($name);
			echo "<tr><td>$name</td><td>$value</td></tr>\n";
		}

		if ($tableBottom) echo "</table>\n";
	}
}
?>