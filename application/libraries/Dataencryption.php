<?php defined('BASEPATH') || exit('No direct script access allowed');
class Dataencryption
{
	
	public function encryptIt1($message, $encode = true)
	{
		$method='aes-256-ctr';
		$key = hex2bin('2e512ca7d95494376b2b77f1f16c14a8d0276e5feb705aeee428f7bab960e7d3');
		$nonceSize = openssl_cipher_iv_length($method);
		$nonce = openssl_random_pseudo_bytes($nonceSize);

		$ciphertext = openssl_encrypt(
			$message,
			$method,
			$key,
			OPENSSL_RAW_DATA,
			$nonce
		);

		// Now let's pack the IV and the ciphertext together
		// Naively, we can just concatenate
		if ($encode) {
			return base64_encode($nonce.$ciphertext);
		}
		return $nonce.$ciphertext;
	}

	/**
	 * Decrypts (but does not verify) a message
	 * 
	 * @param string $message - ciphertext message
	 * @param string $key - encryption key (raw binary expected)
	 * @param boolean $encoded - are we expecting an encoded string?
	 * @return string
	 */
	public function decryptIt1($message, $encoded = true)
	{
		$method='aes-256-ctr';
		$key = hex2bin('2e512ca7d95494376b2b77f1f16c14a8d0276e5feb705aeee428f7bab960e7d3');
		if ($encoded) {
			$message = base64_decode($message, true);
			if ($message === false) {
				throw new Exception('Encryption failure');
			}
		}

		$nonceSize = openssl_cipher_iv_length($method);
		$nonce = mb_substr($message, 0, $nonceSize, '8bit');
		$ciphertext = mb_substr($message, $nonceSize, null, '8bit');

		$plaintext = openssl_decrypt(
			$ciphertext,
			$method,
			$key,
			OPENSSL_RAW_DATA,
			$nonce
		);

		return $plaintext;
	}
	
	public static function encryptIt( $q ) {
		$cryptKey  = 'b49c394398fc75115db34fa33fd3243ad35e5e61eae1f65e932e566115fac9d8';
		$qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
		return( $qEncoded );
		}

	public static function decryptIt( $q ) {
		$cryptKey  = 'b49c394398fc75115db34fa33fd3243ad35e5e61eae1f65e932e566115fac9d8';
		$qDecoded      = trim (rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), ""));
		return( $qDecoded );
	}	
}
?>