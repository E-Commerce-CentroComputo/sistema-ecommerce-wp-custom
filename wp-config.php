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
define( 'DB_NAME', 'ecommerce_db' );

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
define( 'AUTH_KEY',         '(41Y!ZO(qlI>2Jqd],O:&:_XhQg3+2 C(h+zU3cV&:>l0[WcITp*`;dl}h4en)hi' );
define( 'SECURE_AUTH_KEY',  'Hcpx22L{m!,4t.uQQgH5+p9q};~]4fpe wNw 39)YG{p@Z_~ @KG5%S9N{=Y/,d ' );
define( 'LOGGED_IN_KEY',    'sqjar2Oyo]b(0~Rb&,Y:tm9|A1CY~ej|@e]_h5SEXsdGS7K<Eztz7h K@[t.$7Ug' );
define( 'NONCE_KEY',        't9A@5<B(R_3MLos&6$ tr@c.1,58a)+ikVcGJ6m#nFcTqUsUS2GAmil6$4#B6I=*' );
define( 'AUTH_SALT',        '%Y(=#3=tXg!rJEWt9Aadu[uikEXB,52)84K;apHBUP2!8(EOdt`?i>SR8qM}w}%O' );
define( 'SECURE_AUTH_SALT', 'SdP#EB!;T]Z0<O)#>?HNDj0uhJ)~77*!2j41>3j|Y8m@;Yinzq~b+SoC>oX>_c*x' );
define( 'LOGGED_IN_SALT',   'v=#GE%gZ;1N:,9-z)E:em}V[sDZk4FE/O~VCl>dpGSc_Vdg(lRQ%McPcg:+wOPq?' );
define( 'NONCE_SALT',       '|>SK;6fg2.ja;}~>iJgx`2 Y<t-q)9CSrXO%T/}/IOnD8,O`5],,9XV`pt_Vw!UN' );

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
