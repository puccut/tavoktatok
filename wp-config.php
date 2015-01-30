<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'tavoktatok');

/** MySQL database username */
define('DB_USER', 'tavoktatok');

/** MySQL database password */
define('DB_PASSWORD', 's6k!j5NGf93wE');

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
define('AUTH_KEY',         'tjut89h&lk!S+|XU|_% <i91&!In[Ru:cVw!~ZNMi3(,~dh,5y:J[*L;~WI&/V{|');
define('SECURE_AUTH_KEY',  'x5]Z/N?alJCd2r_SH@ky]&Q!4RUe|q{zt>!x-}S5G}]Hvkk6;# FW4)0=}|V,fg:');
define('LOGGED_IN_KEY',    'gwX9DXZgi!MY$~NL=0nL@O4h959X!=-j7w;B(<QMrOQ+ve`icEk5Ad4xyAjos$wx');
define('NONCE_KEY',        '7dDr< .F]_GJ(x*KmRB>C[/3+rq~0s3`0<nxN-z&hY%0*ukHSTgj[b+vyBX41d&E');
define('AUTH_SALT',        '[h#zP6HQ[oW(1h-[YuL:pM|Pa2-jED/|{EG@pO/K+&R@34TysxKB-Jy7Dh|&DT%l');
define('SECURE_AUTH_SALT', '9ul%&*[h0:n Y1g`LhAA*Q0{]ZiLr|gIQEx>qS~h0K&tyPs;vAl+/-:cPVu^B*b/');
define('LOGGED_IN_SALT',   '+GljlCCFE.T:l>G?<-&N3q#=BzXDy2sESWYAA.O7PRd-:Ub3OqF1Eah)]-<@O79$');
define('NONCE_SALT',       '>rTQe~^|84ugd#37%EaCa8S]8lcMTfqJm@Itar0p~M0V*V=i=-C%xkcETyb]}+~0');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
