<?php
/*
 * /_publit-html/_commons/_php/encrypt.php
 *
 * Get info criptography
 *
 * @package    _commons/_php
 * @author     Elton Martins,Marcelo Belkiman
 * @copyright  All rights reserved
 * @version    1.0
 * @date       2014/06/28 : 14:10
 * Campinas-SP | Brazil
 */
 
 /*
  * bCrypt
  */
//Overall encrypt
function startEncrypt($info) {
	$cost = '10';
	$salt = 'Cf1zG1ePJhKlBJo040F6al';

	// Generate a hash based in bcrypt
	return crypt($info, "$2a$" . $cost . "$" . $salt) . $salt;
}

?>