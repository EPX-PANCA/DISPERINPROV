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
/** The name of the database for WordPress */
define('DB_NAME', 'disperin');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ';?tCK$XbY.0Y|D#Cy{v6fdVFu6[d;>-[HTib~OmFfUM5s`26vp9|*=bIDyxX};1V');
define('SECURE_AUTH_KEY',  '4Vtm2d]*OuTYQg&4T*V<90eWaB.s.*m}B7U<^m5lDmXf1$C1&^v}hH=i5{mjQOQ0');
define('LOGGED_IN_KEY',    '.X+SF{ie S_ Hk!5a.95mG$NN3j:~V;/YdihM*%:CaTm.C*]#rUaW:ZmcVI;s@y=');
define('NONCE_KEY',        '@.K8iW,vd1#?~cV5`[c<?k8(n7K!c0AcT+Z#I0i.|[? {l A,5$o6CL)~k),6(a?');
define('AUTH_SALT',        '7U(BioX0BCN/H;:}*osIKAy&H/Yoju<o*Z,z{|`R%+1w]9)w9d]BN50EYkf)1iaP');
define('SECURE_AUTH_SALT', '$}qk9X}sw,U&r&VVVXjPJJ6~tCW;dT@*L-Hp_*f:SPTd2X!S&Hw>iA09rC`z5muP');
define('LOGGED_IN_SALT',   'AQL+gW;nbanv$Pk&Uep}bTiJ Xr4U:_+&R13wOA4yI6Z85Xn|^MNjxes{mTN-Tc]');
define('NONCE_SALT',       ',8;bY9!&2V./KHKYj!*3:`q7jYVelSdDO>xW&@0kUXOrDCOU((C%kwf2n-S&Ch^u');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
