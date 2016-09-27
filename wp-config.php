<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'tedLovett-agency');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'VQv[}^[:#$nO(yx8 3EF-Af~G)[XEi&}czVy?kNp1R<wZxjLo7Ki:A{PLghtxL?G');
define('SECURE_AUTH_KEY',  'XFzx=~ 4S[w}8f.yE}%&n+E)fFdF:z8(MvuRS>HU2Wrnt~cM6]K`G@Q*.n8Twr+m');
define('LOGGED_IN_KEY',    '}|>-~Hps}>lFN-gwjmf+*HVvInJxM5fNBY.W`GB?tSs|XL_kfQM2GmK|>ql|+jhd');
define('NONCE_KEY',        'y>PiRa|Yr$oGtfK0e+y_q5xXr`p:i6]jN]3P[_Ypjg[ubn6Y5CHNl|DF[&1T+/dF');
define('AUTH_SALT',        '*UM9p(FY-lAjp+AjFA*-XAW]Lu8N,DNTA:De+vJ |i[M/>x>p@+3bN+E+{%0YK5X');
define('SECURE_AUTH_SALT', 'tjFWxt6q`e?e$F]-BJpx+-,LrVSWhdh3/Y1v*M|AM8+w;U-aGio)#Z$bh1uydSF[');
define('LOGGED_IN_SALT',   '6uOQ&3k(vLF@Sk:{*M~dxXLNw+@FkCk J?!p Ixvj|a#E1$+3e8gJ_aa]4zR{: {');
define('NONCE_SALT',       ')xx{K}C{0UVM5&qW|?e.vJj;0/K:-joBoj|RA)Zu!oIUJ4] Gs+bM&x1Y$U|5S}&');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'tla_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
