<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
if (strstr($_SERVER['SERVER_NAME'], 'toomoney.local')) {
    define( 'DB_NAME', 'local' );

    /** MySQL database username */
    define( 'DB_USER', 'root' );
    
    /** MySQL database password */
    define( 'DB_PASSWORD', 'root' );
    
    /** MySQL hostname */
    define( 'DB_HOST', 'localhost' );
}else{
    define( 'DB_NAME', 'digipock_db' );

    /** MySQL database username */
    define( 'DB_USER', 'digipocket_root' );
    
    /** MySQL database password */
    define( 'DB_PASSWORD', '@DigiPocket@2020' );
    // define( 'DB_PASSWORD', '7nkcrte17yky' );
    
    /** MySQL hostname */
    define( 'DB_HOST', 'localhost' );
}

// http://digipocket.ir/cpanel
// TlJm3iB5,&]&
// digipocket

// 7nkcrte17yky
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'CHc9U9XTS/oYFvXEh3WG/0tyG9Om5E/dzRqvTnwHBIqvjg7bR8bPFZ4N/6ykOvDpSHML+sc65BKS6yRfz0XZ0g==');
define('SECURE_AUTH_KEY',  'A1gIqczJqhNHEfKjW/8K2ORF+y/EeEXbLO7fedx0c3uZq7yfD9tdIYV6CRFdlnPZSqbs4ZfipABdIInXBUA4Yg==');
define('LOGGED_IN_KEY',    'Y4z5v7azj2wBEek06JfNAjGKZ0h5EjChu5s4ePLY1gyY3leMcHzZb2XThYca1A6kSj94vQXv3AF66BVrY40D3g==');
define('NONCE_KEY',        'flWGmL7sWgBOz0IoGtNJTxWhmNRksLmrYBz6nNiKX16VPlkQXAe1/p0N2nw9AVXB86QcWEC18Mcn0yjrO37xTw==');
define('AUTH_SALT',        '9l5qqCPzv6mMfUglQrZ+asEf6+2WaF646pzWBlNW89gV3dtLVQzZJoBJKz32q7eLfv8VycMm0iKkmID3q0n9XA==');
define('SECURE_AUTH_SALT', 'Ukd+5fG1gXHOLJWgSsf8nPIuV8kDiFQue25KyLuoJ82zwwlXCxPFzjq21BpZ4WIzflkjTbaU9Bxuv2QKsB+/KQ==');
define('LOGGED_IN_SALT',   'eF94p8FGEX8+P6FXvMTQCG/t5XeSB6vBn/745gBaUt1J3V/0o1T5KzOuspFFKVofRrto9Urk8jqe4SDaclhYzA==');
define('NONCE_SALT',       'zuXJLUGzmiGYFa7K75jRcZojh9wjThEBwXL6k4aUU7VEghLwGCNwqTvGuMqbC3gagLXRTwgQ3W889r3Xyb1DuQ==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';