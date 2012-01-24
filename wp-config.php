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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'v1510n');

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
define('AUTH_KEY',         '`F<)>uR 4q-jCs[0e:F)Wc|xcb:f8{u{V:S>UD&8(Oio3>im}+di8@SSg;J.5-)R');
define('SECURE_AUTH_KEY',  '8&)uPqP7i+j)R>qKteu@fX4;HAVe|OJ}V]+%|`XU< WanyEYDwGWrfw7%(|60Zk=');
define('LOGGED_IN_KEY',    'OWgD~GKV/J(n?GXTD{iY&P7dv>ODQEYNqdKMhT+6]*e=;yByv.MN[>+J=QkfKp]W');
define('NONCE_KEY',        '5cqKr7X60d8|zZeGE`)tAqh+`g-S_O6P&yLzj3V1W8^o:d]$X~dHUlO:%<?K`&Ia');
define('AUTH_SALT',        '-Y=Mbx]-a/28bc}7GL~DhMH&.3OkDu7S|W*G6y:m0<&3oz%PaaXpWx~Gz|FMmiGP');
define('SECURE_AUTH_SALT', 'Pk3 W;$%GO6 kM9v4yOR&?y|0+jg.x2-F)OkzI/WNroVW(dQK=-]%n#/e1rDlP##');
define('LOGGED_IN_SALT',   'G-.qdz.3v`b%PhS|fP%ey&m,Lpo2-u/7hP3C2|BPka&_i5y5v-Kx@M9-i|&~-kVw');
define('NONCE_SALT',       '6q49r3?ZTe)1=IPiKVmUf<XV>B|HFly+H ^w)9SiPewpoGxtDn9)7Q``?>5n(HM<');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'tehclsc_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define( 'WP_ALLOW_MULTISITE', false );
//define( 'MULTISITE', true );
//define( 'SUBDOMAIN_INSTALL', true );
//$base = '/';
//define( 'DOMAIN_CURRENT_SITE', 'theclsc.com' );
//define( 'PATH_CURRENT_SITE', '/' );
//define( 'SITE_ID_CURRENT_SITE', 1 );
//define( 'BLOG_ID_CURRENT_SITE', 1 );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
