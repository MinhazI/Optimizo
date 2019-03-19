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
		$this->createCache();
	}

	function deactivate() {

		$this->removeFromWPConfig();
		$this->removeFromHtaccess();
		$this->removeCache();

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

	function minifyCSS() {

	}

	function createCache() {

//		$cacheDir = WP_CONTENT_DIR . '/optimizoCache';

		$ctime  = time();
		$upload = array();

		$upload['basedir'] = WP_CONTENT_DIR . '/optimizoCache';
		$upload['baseurl'] = WP_CONTENT_DIR . '/optimizoCache';

		# create
		$uploadsdir   = $upload['basedir'];
		$uploadsurl   = $upload['baseurl'];
		$cachebase    = $uploadsdir . '/' . $ctime;
		$cachebaseurl = $uploadsurl . '/' . $ctime;
		$cachedir     = $cachebase . '/out';
		$tmpdir       = $cachebase . '/tmp';
		$headerdir    = $cachebase . '/header';
		$cachedirurl  = $cachebaseurl . '/out';

		# get permissions from uploads directory
		$dir_perms = 0777;
		if ( is_dir( $uploadsdir . '/cache' ) && function_exists( 'stat' ) ) {
			if ( $stat = @stat( $uploadsdir . '/cache' ) ) {
				$dir_perms = $stat['mode'] & 0007777;
			}
		}

		# mkdir and check if umask requires chmod
		$dirs = array( $cachebase, $cachedir, $tmpdir, $headerdir );
		foreach ( $dirs as $target ) {
			if ( ! is_dir( $target ) ) {
				if ( @mkdir( $target, $dir_perms, true ) ) {
					if ( $dir_perms != ( $dir_perms & ~umask() ) ) {
						$folder_parts = explode( '/', substr( $target, strlen( dirname( $target ) ) + 1 ) );
						for ( $i = 1, $c = count( $folder_parts ); $i <= $c; $i ++ ) {
							@chmod( dirname( $target ) . '/' . implode( '/', array_slice( $folder_parts, 0, $i ) ), $dir_perms );
						}
					}
				} else {
					# fallback
					wp_mkdir_p( $target );
				}
			}
		}

		# return
		return array( 'cachebase'   => $cachebase,
		              'tmpdir'      => $tmpdir,
		              'cachedir'    => $cachedir,
		              'cachedirurl' => $cachedirurl,
		              'headerdir'   => $headerdir
		);
	}

	function removeCache() {
		if ( is_dir( WP_CONTENT_DIR . '/optimizoCache' ) ) {
			rmdir( WP_CONTENT_DIR . '/optimizoCache' );
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

	function returnFullURL( $src, $wp_domain, $wp_home ) {
		# preserve empty source handles
		$furl = trim( $src );
		if ( empty( $furl ) ) {
			return $furl;
		}

# some fixes
		$furl = str_ireplace( array( '&#038;', '&amp;' ), '&', $furl );

		$default_protocol = get_option( 'fastvelocity_min_default_protocol', 'dynamic' );
		if ( $default_protocol == 'dynamic' || empty( $default_protocol ) ) {
			if ( ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ) || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
				$default_protocol = 'https://';
			} else {
				$default_protocol = 'http://';
			}
		} else {
			$default_protocol = $default_protocol . '://';
		}

#make sure wp_home doesn't have a forward slash
		$wp_home = rtrim( $wp_home, '/' );

# apply some filters
		if ( substr( $furl, 0, 2 ) === "//" ) {
			$furl = $default_protocol . ltrim( $furl, "/" );
		}  # protocol only
		if ( substr( $furl, 0, 4 ) === "http" && stripos( $furl, $wp_domain ) === false ) {
			return $furl;
		} # return if external domain
		if ( substr( $furl, 0, 4 ) !== "http" && stripos( $furl, $wp_domain ) !== false ) {
			$furl = $wp_home . '/' . ltrim( $furl, "/" );
		} # protocol + home

# prevent double forward slashes in the middle
		$furl = str_ireplace( '###', '://', str_ireplace( '//', '/', str_ireplace( '://', '###', $furl ) ) );

# consider different wp-content directory
		$proceed = 0;
		if ( ! empty( $wp_home ) ) {
			$alt_wp_content = basename( $wp_home );
			if ( substr( $furl, 0, strlen( $alt_wp_content ) ) === $alt_wp_content ) {
				$proceed = 1;
			}
		}

# protocol + home for relative paths
		if ( substr( $furl, 0, 12 ) === "/wp-includes" || substr( $furl, 0, 9 ) === "/wp-admin" || substr( $furl, 0, 11 ) === "/wp-content" || $proceed == 1 ) {
			$furl = $wp_home . '/' . ltrim( $furl, "/" );
		}

# make sure there is a protocol prefix as required
		$furl = $default_protocol . str_ireplace( array( 'http://', 'https://' ), '', $furl ); # enforce protocol

# no query strings
		if ( stripos( $furl, '.js?v' ) !== false ) {
			$furl = stristr( $furl, '.js?v', true ) . '.js';
		} # no query strings
		if ( stripos( $furl, '.css?v' ) !== false ) {
			$furl = stristr( $furl, '.css?v', true ) . '.css';
		} # no query strings

# make sure there is a protocol prefix as required
		$furl = $this->compatURL( $furl ); # enforce protocol

		return $furl;
	}

	function minifyInArray( $furl, $ignore ) {
		$furl = str_ireplace( array( 'http://', 'https://' ), '//', $furl ); # better compatibility
		$furl = strtok( urldecode( rawurldecode( $furl ) ), '?' ); # no query string, decode entities

		if ( ! empty( $furl ) && is_array( $ignore ) ) {
			foreach ( $ignore as $i ) {
				$i = str_ireplace( array( 'http://', 'https://' ), '//', $i ); # better compatibility
				$i = strtok( urldecode( rawurldecode( $i ) ), '?' ); # no query string, decode entities
				$i = trim( trim( trim( rtrim( $i, '/' ) ), '*' ) ); # wildcard char removal
				if ( stripos( $furl, $i ) !== false ) {
					return true;
				}
			}
		}
	}

	function checkIfInternalLink( $furl, $wp_home, $noxtra = null ) {
		if ( substr( $furl, 0, strlen( $wp_home ) ) === $wp_home ) {
			return true;
		}
		if ( stripos( $furl, $wp_home ) !== false ) {
			return true;
		}
		if ( isset( $_SERVER['HTTP_HOST'] ) && stripos( $furl, preg_replace( '/:\d+$/', '', $_SERVER['HTTP_HOST'] ) ) !== false ) {
			return true;
		}
		if ( isset( $_SERVER['SERVER_NAME'] ) && stripos( $furl, preg_replace( '/:\d+$/', '', $_SERVER['SERVER_NAME'] ) ) !== false ) {
			return true;
		}
		if ( isset( $_SERVER['SERVER_ADDR'] ) && stripos( $furl, preg_replace( '/:\d+$/', '', $_SERVER['SERVER_ADDR'] ) ) !== false ) {
			return true;
		}

		# allow specific external urls to be merged
		if ( $noxtra === null ) {
			$merge_allowed_urls = array_map( 'trim', explode( PHP_EOL, get_option( 'fastvelocity_min_merge_allowed_urls', '' ) ) );
			if ( is_array( $merge_allowed_urls ) && strlen( implode( $merge_allowed_urls ) ) > 0 ) {
				foreach ( $merge_allowed_urls as $e ) {
					if ( stripos( $furl, $e ) !== false && ! empty( $e ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	function getWPProtocol( $url ) {
		global $wp_domain;
		$url = ltrim( str_ireplace( array( 'http://', 'https://' ), '', $url ), '/' ); # better compatibility

		# enforce protocol if needed
		$default_protocol = get_option( 'fastvelocity_min_default_protocol', 'dynamic' );
		if ( $default_protocol == 'dynamic' || empty( $default_protocol ) ) {
			if ( ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ) || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
				$default_protocol = 'https://';
			} else {
				$default_protocol = 'http://';
			}
		} else {
			$default_protocol = $default_protocol . '://';
		}

		# return
		return $default_protocol . $url;
	}

	function fixPermissions( $file ) {
		if ( function_exists( 'stat' ) ) {
			if ( $stat = @stat( dirname( $file ) ) ) {
				$perms = $stat['mode'] & 0007777;
				@chmod( $file, $perms );

//				clearstatcache();
				return true;
			}
		}


		# get permissions from parent directory
		$perms = 0777;
		if ( function_exists( 'stat' ) ) {
			if ( $stat = @stat( dirname( $file ) ) ) {
				$perms = $stat['mode'] & 0007777;
			}
		}

		if ( file_exists( $file ) ) {
			if ( $perms != ( $perms & ~umask() ) ) {
				$folder_parts = explode( '/', substr( $file, strlen( dirname( $file ) ) + 1 ) );
				for ( $i = 1, $c = count( $folder_parts ); $i <= $c; $i ++ ) {
					@chmod( dirname( $file ) . '/' . implode( '/', array_slice( $folder_parts, 0, $i ) ), $perms );
				}
			}
		}

		return true;
	}

	function compatURL( $code ) {

		if ( ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ) || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
			$default_protocol = 'https://';
		} else {
			$default_protocol = 'http://';
		}
		$code = str_ireplace( array( 'http://', 'https://' ), $default_protocol, $code );
		$code = str_ireplace( $default_protocol . 'www.w3.org', 'http://www.w3.org', $code );

		return $code;

	}

	function getTempStore( $key ) {
		$cachepath = $this->createCache();
		$tmpdir    = $cachepath['tmpdir'];
		$f         = $tmpdir . '/' . $key . '.transient';
		clearstatcache();
		if ( file_exists( $f ) ) {
			return file_get_contents( $f );
		} else {
			return false;
		}
	}

	function setTempStore( $key, $code ) {
		if ( is_null( $code ) || empty( $code ) ) {
			return false;
		}
		$cachepath = $this->createCache();
		$tmpdir    = $cachepath['tmpdir'];
		$f         = $tmpdir . '/' . $key . '.transient';
		file_put_contents( $f, $code );
		$this->fixPermissions( $f );

		return true;
	}

	function downloadAndMinify( $furl, $inline, $type, $handle ) {
		global $wp_domain, $wp_home, $wp_home_path, $fvm_debug;

		# must have
		if ( is_null( $furl ) || empty( $furl ) ) {
			return false;
		}
		if ( ! in_array( $type, array( 'js', 'css' ) ) ) {
			return false;
		}

		# defaults
		if ( is_null( $inline ) || empty( $inline ) ) {
			$inline = '';
		}
		$printhandle = '';
		if ( is_null( $handle ) || empty( $handle ) ) {
			$handle = '';
		} else {
			$printhandle = "[$handle]";
		}

		# debug request
		$dreq = array(
			'furl'   => $furl,
			'inline' => $inline,
			'type'   => $type,
			'handle' => $handle
		);

		# filters and defaults
		$printurl = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '', $furl );

		# linux servers
		if ( stripos( $furl, $wp_domain ) !== false ) {
			# default
			$f = str_ireplace( rtrim( $wp_home, '/' ), rtrim( $wp_home_path, '/' ), $furl );
			clearstatcache();
			if ( file_exists( $f ) ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, file_get_contents( $f ) );
				} else {
					$code = fastvelocity_min_get_css( $furl, file_get_contents( $f ) . $inline );
				}

				# log, save and return
				$log = $printurl;
				if ( $fvm_debug == true ) {
					$log .= " --- Debug: $printhandle was opened from $f ---";
				}
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}

			# failover when home_url != site_url
			$nfurl = str_ireplace( site_url(), home_url(), $furl );
			$f     = str_ireplace( rtrim( $wp_home, '/' ), rtrim( $wp_home_path, '/' ), $nfurl );
			clearstatcache();
			if ( file_exists( $f ) ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, file_get_contents( $f ) );
				} else {
					$code = fastvelocity_min_get_css( $furl, file_get_contents( $f ) . $inline );
				}

				# log, save and return
				$log = $printurl;
				if ( $fvm_debug == true ) {
					$log .= " --- Debug: $printhandle was opened from $f ---";
				}
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}


		# fallback when home_url != site_url
		if ( stripos( $furl, $wp_domain ) !== false && home_url() != site_url() ) {
			$nfurl = str_ireplace( site_url(), home_url(), $furl );
			$code  = fastvelocity_download( $nfurl );
			if ( $code !== false && ! empty( $code ) && strtolower( substr( $code, 0, 9 ) ) != "<!doctype" ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, $code );
				} else {
					$code = fastvelocity_min_get_css( $furl, $code . $inline );
				}

				# log, save and return
				$log = $printurl;
				if ( $fvm_debug == true ) {
					$log .= " --- Debug: $printhandle was fetched from $furl ---";
				}
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}


		# if remote urls failed... try to open locally again, regardless of OS in use
		if ( stripos( $furl, $wp_domain ) !== false ) {
			# default
			$f = str_ireplace( rtrim( $wp_home, '/' ), rtrim( $wp_home_path, '/' ), $furl );
			clearstatcache();
			if ( file_exists( $f ) ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, file_get_contents( $f ) );
				} else {
					$code = fastvelocity_min_get_css( $furl, file_get_contents( $f ) . $inline );
				}

				# log, save and return
				$log = $printurl;
				if ( $fvm_debug == true ) {
					$log .= " --- Debug: $printhandle was opened from $f ---";
				}
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}

			# failover when home_url != site_url
			$nfurl = str_ireplace( site_url(), home_url(), $furl );
			$f     = str_ireplace( rtrim( $wp_home, '/' ), rtrim( $wp_home_path, '/' ), $nfurl );
			clearstatcache();
			if ( file_exists( $f ) ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, file_get_contents( $f ) );
				} else {
					$code = fastvelocity_min_get_css( $furl, file_get_contents( $f ) . $inline );
				}

				# log, save and return
				$log = $printurl;
				if ( $fvm_debug == true ) {
					$log .= " --- Debug: $printhandle was opened from $f ---";
				}
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}


		# else fail
		$log = $printurl;
		if ( $fvm_debug == true ) {
			$log .= " --- Debug: $printhandle failed. Tried wp_remote_get, curl and local file_get_contents. ---";
		}
		$return = array( 'request' => $dreq, 'log' => $log, 'code' => '', 'status' => false );

		return json_encode( $return );
	}

	# minify js on demand (one file at one time, for compatibility)
	function getJS( $url, $js ) {
		global $fvm_debug;

		# exclude minification on already minified files + jquery (because minification might break those)
		$excl = array( 'jquery.js', '.min.js', '-min.js', '/uploads/fusion-scripts/', '/min/', '.packed.js' );
		foreach ( $excl as $e ) {
			if ( stripos( basename( $url ), $e ) !== false ) {
				$disable_js_minification = true;
				break;
			}
		}

		# minify JS
		$js = fvm_compat_urls( $js );

		# try to remove source mapping files
		$filename = basename( $url );
		$remove   = array( "//# sourceMappingURL=$filename.map", "//# sourceMappingURL = $filename.map" );
		$js       = str_ireplace( $remove, '', $js );

		# needed when merging js files
		$js = trim( $js );
		if ( substr( $js, - 1 ) != ';' ) {
			$js = $js . ';';
		}

		# return html
		return $js . PHP_EOL;
	}


}