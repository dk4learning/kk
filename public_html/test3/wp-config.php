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
define( 'DB_NAME', 'u591164756_25BtE' );

/** MySQL database username */
define( 'DB_USER', 'u591164756_HACww' );

/** MySQL database password */
define( 'DB_PASSWORD', 'i96L4Rknt0' );

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
define( 'AUTH_KEY',          'sAA$RlP )Z}u/L>]lY;(|#D[`FLJ7TF.V5]w#0tW[y )p.k[(]u)KF(HT48k(/6~' );
define( 'SECURE_AUTH_KEY',   '{`3 #-aE{Xy*IPr_epoH5UaS9H3/)*_2KW :kf*May{trb}X32vMjY*|gyjB8<Jm' );
define( 'LOGGED_IN_KEY',     'P3,@/z`=o u5dQb`b,f.!4i9S|U/Ek%{Rsi6h[@ls<ynR?xFVM{hPEa;i+P<l>RI' );
define( 'NONCE_KEY',         'lY4U{,gsp,d,ms^{.J@S&.Hm$VGu+DO5f/)Dw[?[C[?q]wyR~CS{D^&D1_!(.Xe?' );
define( 'AUTH_SALT',         '1jh;0q-:R{Pbk$Y|6Xl1WZ#^dJ(ulHb0(bWM$N0j>),5);-f(qn&VR;ghu7Kkp6H' );
define( 'SECURE_AUTH_SALT',  'DGjH3x+.L~bU[$Y:Q}f^iGW$Z_ABNkD7^PAyM!=QfTO;V._#K8cs!gJq.OGKu&85' );
define( 'LOGGED_IN_SALT',    'cA-I,^2*a8`eJ7 WZ~pEAX8VU4a&q(bSC:Rrp4c#C%=9$0|S6/a5>K=tl$emI6r[' );
define( 'NONCE_SALT',        '3FkaYJ&1l?TF*3iBS,9a~hX~LvHZqL64W&g(22w{fIVR_bg<jH#Tc/lk<&|CfHQh' );
define( 'WP_CACHE_KEY_SALT', '=|;G,e~>>eJ_L7[HJ_#0ruE6a-Res8eV2Ur#N(L.v,0acPp<}I_JLDs-=4xEa*;%' );

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
