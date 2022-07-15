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
define( 'DB_NAME', 'u591164756_AqBej' );

/** MySQL database username */
define( 'DB_USER', 'u591164756_Og1g8' );

/** MySQL database password */
define( 'DB_PASSWORD', 'TW2PjZqjjJ' );

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
define( 'AUTH_KEY',          '%+~ja0T9`<vb}_/2r^WK+,-;]&HUhLl@YbJpdGr{V||;s,4U-dGD;p{$I:mkr3@_' );
define( 'SECURE_AUTH_KEY',   'Bv.wfFB2ve]TjzTuZipvzD$Lnq-m-pP=@Oa3i?GHmqC4n(RJXMRu<K,%~%q;W)&V' );
define( 'LOGGED_IN_KEY',     'l=w!|ggg5@AT>aMTaM}}2$?Q)Yzs];{;bH,pTU6XC^WIg68F4_*%^*,XhI^(c=G;' );
define( 'NONCE_KEY',         '/X(nT,d@Trt??~Y.vX3GLuU>hH oi{,Dmen&{8Kx+!g?Rdr3dy@+0E~&{lCf00V$' );
define( 'AUTH_SALT',         '7cvZb,Xo`GR^(OMnr*wKFlK6u2W/_wz/B;,H%~-G9S,+,:[Wmrh]vj]2-XIHYxR|' );
define( 'SECURE_AUTH_SALT',  'C!+Sa8Dz7R%*tMSB1:#0X*n~T_D4_uB9{z6Tl0R*75/]n[]D_|SH*cuvMj~.gb2-' );
define( 'LOGGED_IN_SALT',    '&?-MGAP]61u39-kljTE;R_%-F(KnO`G&B#i}eB6-ZQP`^y:OrAuPB0`N (TXB7X&' );
define( 'NONCE_SALT',        'fa<_@0ie-yz(S:(P-xf`]~ z3JA>QS($$#nU[hlK(SQhHHd]z2N.KTa4UZNIS/XN' );
define( 'WP_CACHE_KEY_SALT', 'qfu_,0<^d;Q>Yu@Oza6Vt&.OQ&{AQL6Q5_D#6Yz<<S]v32:0[IlW (SRuAGPg$-~' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
