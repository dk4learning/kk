<?php
define( 'WP_CACHE', true );
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u591164756_ata4M' );

/** MySQL database username */
define( 'DB_USER', 'u591164756_nwfqJ' );

/** MySQL database password */
define( 'DB_PASSWORD', 'K7axqDn4pQ' );

/** MySQL hostname */
define( 'DB_HOST', 'mysql' );

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
define( 'AUTH_KEY',          '~p$CU8E:&0ONdIF8s8!:aejsi}.vXi^=`Oi{lFV{/$K4wa=0H|G4c$U+;;=`nFiU' );
define( 'SECURE_AUTH_KEY',   '<M3`:Gns^EYEzLqb_g48C#5t9o@tcZ%|NAL)Gv, 8eyw9)G<D{W`?Ky=.Et1 2Xd' );
define( 'LOGGED_IN_KEY',     '<S8bu3+yycUvey*&NuVHI@}~A-EI){*(XcY?jRhNqJ5;<N18yj`NX8M(XauSmNoP' );
define( 'NONCE_KEY',         'kf[!J<%h9Y C,g57F khF{XzERNY]yenl,EU*.q;!b!D1V`KUGMT9(Qs|Nyc#gVv' );
define( 'AUTH_SALT',         ')Ep,56bvP7ti)XVrvqA$_@lRqeEFz,O*pSWL;NF{U/$LiUzwy]bQV8Ohq]Y--~[I' );
define( 'SECURE_AUTH_SALT',  '_#Rt)V}kjxYh?}T=.BPJ!;:sV+YZA8@d/@&%d62DFo]/^wAQR@Z$d%% zEAeOlgr' );
define( 'LOGGED_IN_SALT',    'AhBFbv[]HFNxRhVYQzgoWby%`^-i+S81XF*i4MWt$9r4_Oqk7XX4SJD=AlDRtE+,' );
define( 'NONCE_SALT',        '..uqUr1E*K2yMO$gmy4%<1DhPb?_=F7>#4a^Le}2yf>W5v#UiWd+@mJ7Mx7ZP{6t' );
define( 'WP_CACHE_KEY_SALT', 'T-{]8sIiWv L,u0a{H5t*VIn`weRra}81e^JT;<;o^B1TJ4d,[]=ZqEx[+{TH:3s' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




define( 'WP_AUTO_UPDATE_CORE', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
