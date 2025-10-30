<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'centrocomputo' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ble(z;VG<ORXGp7L4;$C8,ucxn1g=:isJXv2N$Js].h}j2y/P !Q2A}v^P>./tg&' );
define( 'SECURE_AUTH_KEY',  'k0XkCUY%!a<M:TSC*b)`ORa>&@fj9+?=^~)W=&:9CR8vNE$;l8&Z}IwOIts7BVNT' );
define( 'LOGGED_IN_KEY',    'h Ob/>3-&W:#~V).o;L/BZjBT=b*3]M[JTP.NQQrCPSP@bQaQ!cz=S=0tJ)}%@(i' );
define( 'NONCE_KEY',        'L~I7vr#[I<.`efLbDVw@4@rZJ:4}9XZ[+nq&D~(cLAv4+>D(7NGGRL4q2Ow}R:o3' );
define( 'AUTH_SALT',        'TXq|_qsjER[KIqrDZ+3EgC:#7Fzkxj;4-Bq{WuOwUC9]vykyawXt?8El}+Fj{Jht' );
define( 'SECURE_AUTH_SALT', '7jk1=~pPi(_8>g tm|SGLxhM/o(^F6I<-ZEl=x7Nd$j;XE& nH:)I9:YL`>iyk_i' );
define( 'LOGGED_IN_SALT',   'c  #cr<>4~a6PhNPbL[e-x,G#xhB}Ggh81VzYqn*W,,B3nNO.TeDiyG_]ea+f>]K' );
define( 'NONCE_SALT',       'zG43i258!yq#Jo)EG5#0M vy]_?/YU>KP2~;s<o81A&8Z9@xxPc>u)Z^P[2X$.Oa' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
