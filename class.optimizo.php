<?php
/**
 * Created by PhpStorm.
 * User: minhaz
 * Date: 2/14/19
 * Time: 10:54 AM
 */

class OptimizoClass {

	function activate() {
		$this->addToWPConfig();
		$this->writeToHtaccess();
	}

	function deactivate() {

		$this->removeFromWPConfig();
		$this->removeFromHtaccess();

	}

	function addToWPConfig() {

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

	function writeToHtaccess() {

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

	function removeFromWPConfig() {
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

	function removeFromHtaccess() {
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

	function minifyJS() {

	}

	function minifyCSS() {

	}

	function createCache() {

		$cacheDir = get_site_url() . '/wp-content/cache';

		if ( ! is_dir( $cacheDir ) ) {
			if ( @mkdir( get_site_url() . '/wp-content/cache', 0755 ) ) {
				$startTime = microtime( true );


				add_action( 'wp', array( $this, "detect_current_page_type" ) );
				add_action( 'get_footer', array( $this, "detect_current_page_type" ) );
				add_action( 'get_footer', array( $this, "wp_print_scripts_action" ) );

				ob_start( array( $this, "callback" ) );
			}
		} else {
			$startTime = microtime( true );


			add_action( 'wp', array( $this, "detect_current_page_type" ) );
			add_action( 'get_footer', array( $this, "detect_current_page_type" ) );
			add_action( 'get_footer', array( $this, "wp_print_scripts_action" ) );

			ob_start( array( $this, "callback" ) );
		}
	}

	function getWebsiteHTTPResponse( $url ) {
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


}