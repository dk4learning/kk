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
define( 'DB_NAME', 'u591164756_iGgy6' );

/** MySQL database username */
define( 'DB_USER', 'u591164756_hAx8k' );

/** MySQL database password */
define( 'DB_PASSWORD', 's2OuNlQdQi' );

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
define( 'AUTH_KEY',          'n*<G`d g)N<4h0j?1o4/F+T-4>.w.M8b{]Z4W$xmj!MVvGVGfJu9P&keSSQ(<Dd(' );
define( 'SECURE_AUTH_KEY',   'F*t2(]r&R|clsV2ORPT=SDi}Eu|tttf<9=N+FZ{Q@,tdD+Y]*s)uOQ#_?f[Mcp^2' );
define( 'LOGGED_IN_KEY',     '15o;L*w%a+<%Rj#W3t$new.i3k5onA2G&y?}lt-WG03}p$1}P]zEuoh4{jp,|h!e' );
define( 'NONCE_KEY',         ',zPqfmqlr;64^p7c!9fS|nRaw(zBtcSk{q=AtmR;@pFzc-8ofd+C|!n:!X([53@+' );
define( 'AUTH_SALT',         'FT~/SRd2g,-01<7pPKPqJ3;5Zh;YU1lHX|M-Lo 2i4#/ 8nVSRI-?$@-RkRO`L|V' );
define( 'SECURE_AUTH_SALT',  'S<&eZ<.TXvKq0 d]9-LHBPQ{;f .wV8`TI!3pE8`0pQD+j|dm> {Ku1k.f1j^_T*' );
define( 'LOGGED_IN_SALT',    '+#@.{78!vg%>DuKu=Wy8WVzCrZ+(IUut:8b;mO6KfamP9_}U}_-^*nN~{}M#tr,y' );
define( 'NONCE_SALT',        '?P(thWzoun$MU76fK}[Rf&h>)qbL4Fz$qnN`BL!5oQDM@ DD,S0{%bC8=N!?:S#!' );
define( 'WP_CACHE_KEY_SALT', '4*z+x0QE&^}HIyqLKHCU v;Y01T{dRAnpPP[fvep{GwV_F=eQiT6UCxP0<Pk<_H:' );

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
