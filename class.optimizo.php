<?php
/**
 * Created by PhpStorm.
 * User: minhaz
 * Date: 2/14/19
 * Time: 10:54 AM
 */

global $pluginDirectory, $googleFontsWhiteList;

#To avoid PHP regular expression issues
@ini_set( 'pcre.backtrack_limit', 5000000 );
@ini_set( 'pcre.recursion_limit', 5000000 );

require_once ("class.optimizo.minify.php");

class OptimizoFunctions extends OptimizoMinify {

	protected function activate() {
		$this->addToWPConfig();
		$this->writeToHtaccess();
		$this->createCache();
	}

	protected function deactivate() {

		$this->removeFromWPConfig();
		$this->removeFromHtaccess();
		$this->removeCache();

	}

	protected function uninstallPlugin() {
		$this->removeFromWPConfig();
		$this->removeFromHtaccess();
		$this->removeCache();
		$this->removeDirectory( WP_CONTENT_DIR . "/plugins/Optimizo" );
	}

	protected function addToWPConfig() {

		/*
		 * This is a function which will be used to add anything to WordPress WP-Config file which includes all the configurations for a WordPress installation.
		 * Currently it only includes the function to add the "WP-CACHE" as true in the file.
		 */

		$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );

		if ( preg_match( "/Optimizo's configuration for cache/", $wp_config_file ) ) {

		} else if ( preg_match( "/WP_CACHE/", $wp_config_file ) ) {
			$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );
			$wp_config_file = str_replace( "define('WP_CACHE', true);", "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);", $wp_config_file );
			if ( ! @file_put_contents( ABSPATH . "wp-config.php", $wp_config_file ) ) {

			}
		} else {
			$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );
			$wp_config_file = str_replace( "/** MySQL hostname */", "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);\n\n/** MySQL hostname */", $wp_config_file );
			if ( ! @file_put_contents( ABSPATH . "wp-config.php", $wp_config_file ) ) {

			}
		}
	}

	protected function writeToHtaccess() {

		/*
		 * This function will be used to write the rewrite access rules and other rules for caching and G-Zip to the server's .htaccess file
		 * The first part of the IF condition is to see if the WordPress installation directory has the .htaccess file which includes the rules for any server.
		 * IF the server doesn't include it, Optimizo will automatically create and save the .htaccess file on the server and it will also include the rules for caching and GZip and other stuff to reduce the PLT
		 * IF the server does include .htaccess file, Optimizo will just add the rules that are essential to help reduction of PLT and it will save the file.
		 */
		if ( ! file_exists( ABSPATH . ".htaccess" ) ) {
			$htaccessData = "# BEGIN WordPress" . "\n" .
			                '<IfModule mod_rewrite.c>' . "\n" .
			                'RewriteEngine On' . "\n" .
			                'RewriteBase /' . "\n" .
			                'RewriteRule ^index\.php$ - [L]' . "\n" .
			                'RewriteCond %{REQUEST_FILENAME} !-f' . "\n" .
			                'RewriteCond %{REQUEST_FILENAME} !-d' . "\n" .
			                'RewriteRule . /index.php [L]' . "\n" .
			                '</IfModule>' . "\n" .
			                "# END WordPress" . "\n\n" .
			                "# BEGIN Optimizo's rules" . "\n" .
			                '<FilesMatch "\.(webm|ogg|mp4|ico|pdf|flv|jpg|jpeg|png|gif|webp|js|css|swf|x-html|css|xml|js|woff|woff2|ttf|svg|eot)(\.gz)?$">' . "\n" .
			                '<IfModule mod_expires.c>' . "\n" .
			                'AddType application/font-woff2 .woff2' . "\n" .
			                'ExpiresActive On' . "\n" .
			                'ExpiresDefault A0' . "\n" .
			                'ExpiresByType video/webm A10368000' . "\n" .
			                'ExpiresByType video/ogg A10368000' . "\n" .
			                'ExpiresByType video/mp4 A10368000' . "\n" .
			                'ExpiresByType image/webp A10368000' . "\n" .
			                'ExpiresByType image/gif A10368000' . "\n" .
			                'ExpiresByType image/png A10368000' . "\n" .
			                'ExpiresByType image/jpg A10368000' . "\n" .
			                'ExpiresByType image/jpeg A10368000' . "\n" .
			                'ExpiresByType image/ico A10368000' . "\n" .
			                'ExpiresByType image/svg+xml A10368000' . "\n" .
			                'ExpiresByType text/css A10368000' . "\n" .
			                'ExpiresByType text/javascript A10368000' . "\n" .
			                'ExpiresByType application/javascript A10368000' . "\n" .
			                'ExpiresByType application/x-javascript A10368000' . "\n" .
			                'ExpiresByType application/font-woff2 A10368000' . "\n" .
			                '</IfModule>' . "\n" .
			                '<IfModule mod_headers.c>' . "\n" .
			                'Header set Expires "max-age=A10368000, public"' . "\n" .
			                'Header unset ETag' . "\n" .
			                'Header set Connection keep-alive' . "\n" .
			                'FileETag None' . "\n" .
			                '</IfModule>' . "\n" .
			                '</FilesMatch>' . "\n" .
			                '<IfModule mod_deflate.c>' . "\n" .
			                "# Compress HTML, CSS, JavaScript, Text, XML and fonts" . "\n" .
			                'AddOutputFilterByType DEFLATE application/javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE application/rss+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE application/vnd.ms-fontobject' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-opentype' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-otf' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-truetype' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-ttf' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE application/xhtml+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE application/xml' . "\n" .
			                'AddOutputFilterByType DEFLATE font/opentype' . "\n" .
			                'AddOutputFilterByType DEFLATE font/otf' . "\n" .
			                'AddOutputFilterByType DEFLATE font/ttf' . "\n" .
			                'AddOutputFilterByType DEFLATE image/svg+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE image/x-icon' . "\n" .
			                'AddOutputFilterByType DEFLATE text/css' . "\n" .
			                'AddOutputFilterByType DEFLATE text/html' . "\n" .
			                'AddOutputFilterByType DEFLATE text/javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE text/plain' . "\n" .
			                'AddOutputFilterByType DEFLATE text/xml' . "\n" .

			                "# Remove browser bugs (only needed for really old browsers)" . "\n" .
			                'BrowserMatch ^Mozilla/4 gzip-only-text/html' . "\n" .
			                'BrowserMatch ^Mozilla/4\.0[678] no-gzip' . "\n" .
			                'BrowserMatch \bMSIE !no-gzip !gzip-only-text/html' . "\n" .
			                'Header append Vary User-Agent' . "\n" .
			                '</IfModule>' . "\n" .
			                "# END Optimizo's rules" . "\n";

			fopen( ABSPATH . ".htaccess", "r+" );
			file_put_contents( ABSPATH . ".htaccess", $htaccessData );


			fclose( ABSPATH . ".htaccess" );
		} else {
			$htaccessData = "#BEGIN Optimizo's rules" . "\n" .
			                '<FilesMatch "\.(webm|ogg|mp4|ico|pdf|flv|jpg|jpeg|png|gif|webp|js|css|swf|x-html|css|xml|js|woff|woff2|ttf|svg|eot)(\.gz)?$">' . "\n" .
			                '<IfModule mod_expires.c>' . "\n" .
			                'AddType application/font-woff2 .woff2' . "\n" .
			                'ExpiresActive On' . "\n" .
			                'ExpiresDefault A0' . "\n" .
			                'ExpiresByType video/webm A10368000' . "\n" .
			                'ExpiresByType video/ogg A10368000' . "\n" .
			                'ExpiresByType video/mp4 A10368000' . "\n" .
			                'ExpiresByType image/webp A10368000' . "\n" .
			                'ExpiresByType image/gif A10368000' . "\n" .
			                'ExpiresByType image/png A10368000' . "\n" .
			                'ExpiresByType image/jpg A10368000' . "\n" .
			                'ExpiresByType image/jpeg A10368000' . "\n" .
			                'ExpiresByType image/ico A10368000' . "\n" .
			                'ExpiresByType image/svg+xml A10368000' . "\n" .
			                'ExpiresByType text/css A10368000' . "\n" .
			                'ExpiresByType text/javascript A10368000' . "\n" .
			                'ExpiresByType application/javascript A10368000' . "\n" .
			                'ExpiresByType application/x-javascript A10368000' . "\n" .
			                'ExpiresByType application/font-woff2 A10368000' . "\n" .
			                '</IfModule>' . "\n" .
			                '<IfModule mod_headers.c>' . "\n" .
			                'Header set Expires "max-age=A10368000, public"' . "\n" .
			                'Header unset ETag' . "\n" .
			                'Header set Connection keep-alive' . "\n" .
			                'FileETag None' . "\n" .
			                '</IfModule>' . "\n" .
			                '</FilesMatch>' . "\n" .
			                '<IfModule mod_deflate.c>' . "\n" .
			                "# Compress HTML, CSS, JavaScript, Text, XML and fonts" . "\n" .
			                'AddOutputFilterByType DEFLATE application/javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE application/rss+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE application/vnd.ms-fontobject' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-opentype' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-otf' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-truetype' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-ttf' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE application/xhtml+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE application/xml' . "\n" .
			                'AddOutputFilterByType DEFLATE font/opentype' . "\n" .
			                'AddOutputFilterByType DEFLATE font/otf' . "\n" .
			                'AddOutputFilterByType DEFLATE font/ttf' . "\n" .
			                'AddOutputFilterByType DEFLATE image/svg+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE image/x-icon' . "\n" .
			                'AddOutputFilterByType DEFLATE text/css' . "\n" .
			                'AddOutputFilterByType DEFLATE text/html' . "\n" .
			                'AddOutputFilterByType DEFLATE text/javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE text/plain' . "\n" .
			                'AddOutputFilterByType DEFLATE text/xml' . "\n" .

			                "# Remove browser bugs (only needed for really old browsers)" . "\n" .
			                'BrowserMatch ^Mozilla/4 gzip-only-text/html' . "\n" .
			                'BrowserMatch ^Mozilla/4\.0[678] no-gzip' . "\n" .
			                'BrowserMatch \bMSIE !no-gzip !gzip-only-text/html' . "\n" .
			                'Header append Vary User-Agent' . "\n" .
			                '</IfModule>' . "\n" .
			                "# END Optimizo's rules" . "\n";

			$htaccessContents    = @file_get_contents( ABSPATH . ".htaccess" );
			$htaccessReplacement = str_replace( "# END WordPress", "# END WordPress\n\n" . $htaccessData, $htaccessContents );

			if ( ! @file_put_contents( ABSPATH . ".htaccess", $htaccessReplacement ) ) {

			}
		}

	}

	protected function removeFromWPConfig() {
		/*
		 * This is the function that will be used and called upon the deactivation of the plugin.
		 * Currently this function removes the "WP-CACHE" statement from the WP-Config file if it's available.
		 */

		$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );
		$wp_config_file = str_replace( "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);", null, $wp_config_file );
		if ( ! @file_put_contents( ABSPATH . "wp-config.php", $wp_config_file ) ) {
			add_action( 'admin_notices', 'failed_to_add_wp_config' );
		}
	}

	protected function removeFromHtaccess() {
		/*
		 * This is the function that will remove all the custom added rules from .htaccess file which was added upon the activation of the plugin.
		 */

		$htaccess_file = @file_get_contents( ABSPATH . ".htaccess" );

		$htaccessData = "#BEGIN Optimizo's rules" . "\n" .
		                '<FilesMatch "\.(webm|ogg|mp4|ico|pdf|flv|jpg|jpeg|png|gif|webp|js|css|swf|x-html|css|xml|js|woff|woff2|ttf|svg|eot)(\.gz)?$">' . "\n" .
		                '<IfModule mod_expires.c>' . "\n" .
		                'AddType application/font-woff2 .woff2' . "\n" .
		                'ExpiresActive On' . "\n" .
		                'ExpiresDefault A0' . "\n" .
		                'ExpiresByType video/webm A10368000' . "\n" .
		                'ExpiresByType video/ogg A10368000' . "\n" .
		                'ExpiresByType video/mp4 A10368000' . "\n" .
		                'ExpiresByType image/webp A10368000' . "\n" .
		                'ExpiresByType image/gif A10368000' . "\n" .
		                'ExpiresByType image/png A10368000' . "\n" .
		                'ExpiresByType image/jpg A10368000' . "\n" .
		                'ExpiresByType image/jpeg A10368000' . "\n" .
		                'ExpiresByType image/ico A10368000' . "\n" .
		                'ExpiresByType image/svg+xml A10368000' . "\n" .
		                'ExpiresByType text/css A10368000' . "\n" .
		                'ExpiresByType text/javascript A10368000' . "\n" .
		                'ExpiresByType application/javascript A10368000' . "\n" .
		                'ExpiresByType application/x-javascript A10368000' . "\n" .
		                'ExpiresByType application/font-woff2 A10368000' . "\n" .
		                '</IfModule>' . "\n" .
		                '<IfModule mod_headers.c>' . "\n" .
		                'Header set Expires "max-age=A10368000, public"' . "\n" .
		                'Header unset ETag' . "\n" .
		                'Header set Connection keep-alive' . "\n" .
		                'FileETag None' . "\n" .
		                '</IfModule>' . "\n" .
		                '</FilesMatch>' . "\n" .
		                '<IfModule mod_deflate.c>' . "\n" .
		                "# Compress HTML, CSS, JavaScript, Text, XML and fonts" . "\n" .
		                'AddOutputFilterByType DEFLATE application/javascript' . "\n" .
		                'AddOutputFilterByType DEFLATE application/rss+xml' . "\n" .
		                'AddOutputFilterByType DEFLATE application/vnd.ms-fontobject' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font-opentype' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font-otf' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font-truetype' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font-ttf' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-javascript' . "\n" .
		                'AddOutputFilterByType DEFLATE application/xhtml+xml' . "\n" .
		                'AddOutputFilterByType DEFLATE application/xml' . "\n" .
		                'AddOutputFilterByType DEFLATE font/opentype' . "\n" .
		                'AddOutputFilterByType DEFLATE font/otf' . "\n" .
		                'AddOutputFilterByType DEFLATE font/ttf' . "\n" .
		                'AddOutputFilterByType DEFLATE image/svg+xml' . "\n" .
		                'AddOutputFilterByType DEFLATE image/x-icon' . "\n" .
		                'AddOutputFilterByType DEFLATE text/css' . "\n" .
		                'AddOutputFilterByType DEFLATE text/html' . "\n" .
		                'AddOutputFilterByType DEFLATE text/javascript' . "\n" .
		                'AddOutputFilterByType DEFLATE text/plain' . "\n" .
		                'AddOutputFilterByType DEFLATE text/xml' . "\n" .

		                "# Remove browser bugs (only needed for really old browsers)" . "\n" .
		                'BrowserMatch ^Mozilla/4 gzip-only-text/html' . "\n" .
		                'BrowserMatch ^Mozilla/4\.0[678] no-gzip' . "\n" .
		                'BrowserMatch \bMSIE !no-gzip !gzip-only-text/html' . "\n" .
		                'Header append Vary User-Agent' . "\n" .
		                '</IfModule>' . "\n" .
		                "# END Optimizo's rules" . "\n";

		$htaccess_file = str_replace( $htaccessData, null, $htaccess_file );

		if ( ! @file_put_contents( ABSPATH . ".htaccess", $htaccess_file ) ) {

		}
	}

	public function removeCache() {
		if ( @is_dir( WP_CONTENT_DIR . '/optimizoCache' ) ) {
			$this->removeDirectory( WP_CONTENT_DIR . '/optimizoCache' );
		}
	}

	protected function getWebsiteHTTPResponse( $url ) {
		if ( $url == null ) {
			return false;
		}

		$curl = curl_init( url );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 5 );
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 3 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

		$websiteData = curl_exec( $curl );

		$httpResponseCode = curl_getinfo( $curl, $websiteData );

		curl_close( $curl );

		if ( $httpResponseCode >= 200 && $httpResponseCode < 300 ) {
			return true;
		} else {
			return false;
		}

	}

	protected function returnFullURL( $code, $wpDomain, $wpHome ) {
		# preserve empty source handles
		$url = trim( $code );
		if ( empty( $url ) ) {
			return $url;
		}

# some fixes
		$url = str_ireplace( array( '&#038;', '&amp;' ), '&', $url );

		if ( ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ) || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
			$defaultProtocol = 'https://';
		} else {
			$defaultProtocol = 'http://';
		}

#make sure wp_home doesn't have a forward slash
		$wpHome = rtrim( $wpHome, '/' );

# apply some filters
		if ( substr( $url, 0, 2 ) === "//" ) {
			$url = $defaultProtocol . ltrim( $url, "/" );
		}  # protocol only
		if ( substr( $url, 0, 4 ) === "http" && stripos( $url, $wpDomain ) === false ) {
			return $url;
		} # return if external domain
		if ( substr( $url, 0, 4 ) !== "http" && stripos( $url, $wpDomain ) !== false ) {
			$url = $wpHome . '/' . ltrim( $url, "/" );
		} # protocol + home

# prevent double forward slashes in the middle
		$url = str_ireplace( '###', '://', str_ireplace( '//', '/', str_ireplace( '://', '###', $url ) ) );

# consider different wp-content directory
		$proceed = 0;
		if ( ! empty( $wpHome ) ) {
			$altWPContent = basename( $wpHome );
			if ( substr( $url, 0, strlen( $altWPContent ) ) === $altWPContent ) {
				$proceed = 1;
			}
		}

# protocol + home for relative paths
		if ( substr( $url, 0, 12 ) === "/wp-includes" || substr( $url, 0, 9 ) === "/wp-admin" || substr( $url, 0, 11 ) === "/wp-content" || $proceed == 1 ) {
			$url = $wpHome . '/' . ltrim( $url, "/" );
		}

# make sure there is a protocol prefix as required
		$url = $defaultProtocol . str_ireplace( array( 'http://', 'https://' ), '', $url ); # enforce protocol

# no query strings
		if ( stripos( $url, '.js?v' ) !== false ) {
			$url = stristr( $url, '.js?v', true ) . '.js';
		} # no query strings
		if ( stripos( $url, '.css?v' ) !== false ) {
			$url = stristr( $url, '.css?v', true ) . '.css';
		} # no query strings

# make sure there is a protocol prefix as required
		$url = $this->compatURL( $url ); # enforce protocol

		return $url;
	}

	protected function checkIfInternalLink( $url, $wpHome, $noExtra = null ) {

		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$serverHttpHost = $_SERVER['HTTP_HOST'];
		} else {
			$serverHttpHost = false;
		}

		if ( isset( $_SERVER['SERVER_NAME'] ) ) {
			$serverName = $_SERVER['SERVER_NAME'];
		} else {
			$serverName = false;
		}

		if ( isset( $_SERVER['SERVER_ADDR'] ) ) {
			$serverAddress = $_SERVER['SERVER_ADDR'];
		} else {
			$serverAddress = false;
		}

		if ( substr( $url, 0, strlen( $wpHome ) ) === $wpHome ) {
			return true;
		}
		if ( stripos( $url, $wpHome ) !== false ) {
			return true;
		}
		if ( $serverHttpHost && stripos( $url, preg_replace( '/:\d+$/', '', $_SERVER['HTTP_HOST'] ) ) !== false ) {
			return true;
		}
		if ( $serverName && stripos( $url, preg_replace( '/:\d+$/', '', $_SERVER['SERVER_NAME'] ) ) !== false ) {
			return true;
		}
		if ( $serverAddress && stripos( $url, preg_replace( '/:\d+$/', '', $_SERVER['SERVER_ADDR'] ) ) !== false ) {
			return true;
		}

		# allow specific external urls to be merged
		if ( $noExtra === null ) {
			$mergeAllowedURLs = array_map( 'trim', explode( PHP_EOL ) );
			if ( is_array( $mergeAllowedURLs ) && strlen( implode( $mergeAllowedURLs ) ) > 0 ) {
				foreach ( $mergeAllowedURLs as $e ) {
					if ( stripos( $url, $e ) !== false && ! empty( $e ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	protected function getWPProtocol( $url ) {
		$url = ltrim( str_ireplace( array( 'http://', 'https://' ), '', $url ), '/' ); # better compatibility

		# enforce protocol if needed
		if ( ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ) ||
		     ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
			$default_protocol = 'https://';
		} else {
			$default_protocol = 'http://';
		}

		# return
		return $default_protocol . $url;
	}

	protected function fixPermissions( $fileName ) {
		if ( function_exists( 'stat' ) ) {
			if ( $stat = @stat( dirname( $fileName ) ) ) {
				$permission = $stat['mode'] & 0007777;
				@chmod( $fileName, $permission );

				clearstatcache();

				return true;
			}
		}


		# get permissions from parent directory
		$permission = 0777;
		if ( function_exists( 'stat' ) ) {
			if ( $stat = @stat( dirname( $fileName ) ) ) {
				$permission = $stat['mode'] & 0007777;
			}
		}

		if ( file_exists( $fileName ) ) {
			if ( $permission != ( $permission & ~umask() ) ) {
				$folderParts = explode( '/', substr( $fileName, strlen( dirname( $fileName ) ) + 1 ) );
				for ( $i = 1, $c = count( $folderParts ); $i <= $c; $i ++ ) {
					@chmod( dirname( $fileName ) . '/' . implode( '/', array_slice( $folderParts, 0, $i ) ), $permission );
				}
			}
		}

		return true;
	}

	protected function compatURL( $code ) {

		if ( ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) )
		     || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
			$defaultProtocol = 'https://';
		} else {
			$defaultProtocol = 'http://';
		}
		$code = str_ireplace( array( 'http://', 'https://' ), $defaultProtocol, $code );
		$code = str_ireplace( $defaultProtocol . 'www.w3.org', 'http://www.w3.org', $code );

		return $code;

	}

	protected function addToLog( $log ) {

		$cachefunc = $this->createCache();

		$pathToLog = $cachefunc['cachedir'];

		$dataToAddToLog = $log . "\n#End of log";

		if ( ! file_exists( $pathToLog . "/optimizo-log.txt" ) ) {
			@fopen( $pathToLog . "/optimizo-log.txt" );
			@file_put_contents( $pathToLog . "/optimizo-log.txt", $dataToAddToLog );
		} else {
			$currentLog = @file_get_contents( $pathToLog . "/optimizo-log.txt" );
			$newLogData = @str_replace( "#End of log", $dataToAddToLog, $currentLog );
			@file_put_contents( $pathToLog . "/optimizo-log.txt", $newLogData );
		}
	}

	protected function removeDirectory( $directory ) {
		if ( is_dir( $directory ) ) {
			$objects = scandir( $directory );
			foreach ( $objects as $object ) {
				if ( $object != "." && $object != ".." ) {
					if ( is_dir( $directory . "/" . $object ) ) {
						rrmdir( $directory . "/" . $object );
					} else {
						unlink( $directory . "/" . $object );
					}
				}
			}
			rmdir( $directory );
		}
	}

}