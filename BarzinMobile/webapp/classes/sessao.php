<?php
session_name("barzin-mobile");
session_start();

class Sessao {

	public static function get($nome_variavel) {
		if (self::tem($nome_variavel)) {
			return $_SESSION[$nome_variavel];
		}
		else {
			return null;
		}
	}

	public static function set($nome_variavel, $valor) {
		$_SESSION[$nome_variavel] = $valor;
	}

	public static function tem($nome_variavel) {
		return isset($_SESSION[$nome_variavel]);
	}

	public static function destruir() {
		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (isset($_COOKIE[session_name()])) {
		    setcookie(session_name(), '', time() - 42000, '/');
		}

		// Finally, destroy the session.
		session_destroy();
	}

}
?>