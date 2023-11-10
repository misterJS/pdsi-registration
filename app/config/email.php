<?php
class EmailConfig {
	
	// Pilih salah satu provider:
	
	// public $provider = 'Standard';
	public $provider = 'Google';
	// public $provider = 'AmazonSES';

	public $client = [	'standard' => [
										'host' => 'mail.pdsionline.org'
										, 'username' => 'admin@pdsionline.org'
										, 'password' => 'pdsionlineemail'
									]
						,'google' => ['client_id' => '848706937765-sbmpa0bk7qk06fh7tr7q2na5sv3n8g0s.apps.googleusercontent.com'
										, 'client_secret' => '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'
										, 'refresh_token' => '1//0gdbDZhcj6CKYCgYIARAAGBASNwF-L9IrKk6IOna5Oip6J64lC0aP-s3rrdo9KU2IJKF1oot17y8kYIcQV4qqvrkX2ZIwX62R7lY'
									]
						, 'ses' => ['username' => ''
										, 'password' => ''
									]
					];
	
	// Disesuaikan dengan konfigurasi username
	public $from = 'yusdion3007@gmail.com';
}