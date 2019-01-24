<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'wp_exorel');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'hSkw~Wc+1s )-krn1H`k?y2x`R%wO/A^ELo$KnmZ$qmwIO`ibb$FAXmU?PS^h4Da');
define('SECURE_AUTH_KEY',  'qN7W4j@-b>)N6Ews1uT;%4/671b&p=fmV-ox1=Z#j=7d<Ujg:Rgc>89N%30wAx>r');
define('LOGGED_IN_KEY',    'QgNMG3Zo3xv|G/oq;DCB#^t6~n<@,nNqF^?[/T[y_?qFr4Gcn}hL!_+Sj47DCDSG');
define('NONCE_KEY',        '}5ptRbjrI.[;kF)E_acQ[4ItxyZ6KBk%7c;Rp_=o>[yb!EZ27X[u}lU2{#=.jBU8');
define('AUTH_SALT',        'fN:%{ 2(g.]0F4Q.O}`Q>d;)ZdP;{Nt?E,b!byA8^L^5onI$5wYGfA[46bYP}Ct@');
define('SECURE_AUTH_SALT', 'OO/a9#sEGl(~*N{orbH+YL>St~UDg-6^O2`I;+v/t&}yN X.2{o<OHS:V)/AK^#?');
define('LOGGED_IN_SALT',   'FTVZ66e #)/(4k|hE@a^8a/U79>CiatAB^5?xHlb<3Yy%[gvq:!N<7px.Ro$KfkW');
define('NONCE_SALT',       'uf#|VI</7o##t)bs/dc:)It_8w Z{,<<cKAA^sUca>5N0rAR-<4tiA1I|K341xkt');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
