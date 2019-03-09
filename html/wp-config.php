<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
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
// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );
/** MySQL database username */
define( 'DB_USER', 'wordpress' );
/** MySQL database password */
define( 'DB_PASSWORD', 'znWyr7WT' );
/** MySQL hostname */
define( 'DB_HOST', 'localhost' );
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
define( 'AUTH_KEY',          '> 8rm#ismQ;uC|-f|gyQrU{2Ly<*UVFFg6T$;%vn9!x5=i8?0|{$,6:t9PR=J6ss' );
define( 'SECURE_AUTH_KEY',   'mXt}M:*f`24w/vYDTp]P%J:ZTJ-e6G<fgHHf*mX]o.(HQHIt6n^(l8vrxW:c!SNn' );
define( 'LOGGED_IN_KEY',     'Ojspy-%$eLd]mI-Rd^q=9]w!$3Qzw=$ bzTR1lUm$X~RiGY/wrglvr5-<nmuQ@0B' );
define( 'NONCE_KEY',         ':45O+ObFgJdA31vgQLyq}[6So$V]4/4xZ{y3aX))f`6cS3%.cG+%9MJpT&u8D(1*' );
define( 'AUTH_SALT',         'f*/a=1A-4`,h%pDzi688R<F `<fkb`_$9h1.uQ-]~/Y!5up]/<zF3cIx[5)oa0w^' );
define( 'SECURE_AUTH_SALT',  '}FB:)K%^,Bn=hQj:[d9DckO^nO/v2F!s0LiipG5V(@ sxLd4BVSpifKpiWIH!/G&' );
define( 'LOGGED_IN_SALT',    'HaTI&rMp*Z*TghJ8c_XUywbb/hUPA<9e(TC4`F91A]rzlO/A5FVk+SW3/HH[aVp;' );
define( 'NONCE_SALT',        '(Wj;E4(Y&ls>$#GL$j?t/.y!  )D2Ul/E7;.B@IxOQ9^U(]<&#kk/r@EfGG[xY*^' );
define( 'WP_CACHE_KEY_SALT', ' UM32#vhykCK7=Yu`2)5L1HALT$qVPcMfZMO)+p{A7_x.v^#F]~)6-&wA^@qT?zp' );
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
