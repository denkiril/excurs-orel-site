<?php
/**
 * Carbon Fields for Excursions theme
 *
 * @package excursions
 */

/**
 * Carbon_Fields loading
 *
 * @return void
 */
function crb_load() {
	require_once get_template_directory() . '/vendor/autoload.php';
	\Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'after_setup_theme', 'crb_load' );

// Carbon_Fields for post_type=guidebook, taxonomy=sections, term->slug=routes.
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Field\Complex_Field;

/**
 * Add fields for guidebook posts (wip)
 *
 * @return void
 */
function crb_guidebook_fields() {
	Container::make( 'post_meta', 'gba_fields', 'Путеводитель (статья) v.2' )
		->where( 'post_type', '=', 'guidebook' )
		->where(
			'post_term',
			'!=',
			array(
				'field'    => 'slug',
				'value'    => 'routes',
				'taxonomy' => 'sections',
			)
		)
		->add_fields(
			array(
				Field::make( 'text', 'gba_rating', 'Рейтинг (1...10)' )
					->set_width( 33 ),
				Field::make( 'text', 'gba_created', 'Датировка' )
					->set_width( 33 ),
				Field::make( 'multiselect', 'gba_recorded', 'Год постановки на охрану' )
					->set_width( 33 )
					->add_options(
						array(
							'1960' => '1960',
							'1974' => '1974',
							'1995' => '1995',
						)
					),
			)
		);
}
add_action( 'carbon_fields_register_fields', 'crb_guidebook_fields' );

/**
 * Add guidebook route fields for special pages ('routes', 'map')
 *
 * @return void
 */
function crb_fields_for_gb_routes() {
	$map_page    = get_page_by_path( 'map' );
	$map_page_id = $map_page ? $map_page->ID : null;
	Container::make( 'post_meta', 'gbroutes', 'Поля для раздела "Маршруты, тематические подборки"' )
		// ->show_on_taxonomy_term( 'routes', 'sections' )
		->where( 'post_type', '=', 'guidebook' )
		->where(
			'post_term',
			'=',
			array(
				'field'    => 'slug',
				'value'    => 'routes',
				'taxonomy' => 'sections',
			)
		)
		->or_where( 'post_id', '=', $map_page_id )
		->add_fields(
			array(
				Field::make( 'textarea', 'gbr_intro', 'Введение (html и шорткоды)' )
					->set_rows( 10 ),
				Field::make( 'separator', 'map_place', '[ Карта ]' ),
				Field::make( 'textarea', 'gbr_content', 'Контент (html и шорткоды)' )
					->set_rows( 20 ),
				Field::make( 'complex', 'gbr_sights', 'Достопримечательности' )
					->setup_labels(
						array(
							'plural_name'   => 'Объекты',
							'singular_name' => 'Объект',
						)
					)
					->add_fields(
						array(
							Field::make( 'text', 'title', 'Название' )
								->set_width( 75 ),
							Field::make( 'text', 'latlng_text', 'Координаты (если нет Статьи ПВ)' ) // add to mini-map.
								->set_attribute( 'placeholder', 'lat, lng' )
								->set_width( 25 ),
							Field::make( 'association', 'gba', 'Статья ПВ' ) // add <a></a> to title.
								->set_types(
									array(
										array(
											'type'      => 'post',
											'post_type' => 'guidebook',
										),
									)
								)
								->set_max( 1 ),
							Field::make( 'image', 'image', 'Картинка' )
								->set_width( 25 ),
							Field::make( 'textarea', 'desription', 'Описание' )
								->set_width( 75 ),
						)
					)
					->set_header_template( '<%- title %> <%- latlng_text || gba.length ? "" : "[geo?]" %>' ),
				// end of complex 'gbr_sights'.
				Field::make( 'text', 'gbr_sort', '№ п/п' ),
			)
		);
}
add_action( 'carbon_fields_register_fields', 'crb_fields_for_gb_routes' );

/**
 * Add 'see also' fields for some posts ('guidebook')
 *
 * @return void
 */
function crb_seealso_fields() {
	Container::make( 'post_meta', 'seealso', 'См. также' )
		->where( 'post_type', '=', 'guidebook' )
		->add_fields(
			array(
				Field::make( 'association', 'seealso_posts', 'Пост / Страница / Статья ПВ' )
					->set_types(
						array(
							array(
								'type'      => 'post',
								'post_type' => 'page',
							),
							array(
								'type'      => 'post',
								'post_type' => 'post',
							),
							array(
								'type'      => 'post',
								'post_type' => 'guidebook',
							),
						)
					),
			)
		);
}
add_action( 'carbon_fields_register_fields', 'crb_seealso_fields' );

/**
 * Add fields for 'sources' posts (show on 'citata' page)
 *
 * @return void
 */
function crb_fields_for_citata() {
	Container::make( 'post_meta', 'citatas', 'Цитаты' )
		->show_on_post_type( 'sources' )
		->add_fields(
			array(
				Field::make( 'text', 'source_text', 'Источник (подпись к цитатам)' )
					->set_attribute( 'placeholder', 'Иван Тургенев, «Рудин», 1856.' )
					->set_width( 75 ),
				Field::make( 'text', 'date', 'Год (для сортировки)' )
					->set_width( 25 ),
				Field::make( 'complex', 'citata_list', 'Список цитат' )
					->setup_labels(
						array(
							'plural_name'   => 'Цитаты',
							'singular_name' => 'цитату',
						)
					)
					->set_collapsed( true )
					->add_fields(
						array(
							Field::make( 'textarea', 'text', 'Текст' )
								->set_rows( 8 )
								->set_width( 80 ),
							Field::make( 'set', 'tags', 'Тэги (для фильтра)' )
								->add_options( 'citata_get_tags_array' )
								->set_width( 20 ),
							Field::make( 'image', 'image', 'Картинка' )
								->set_width( 20 ),
							Field::make( 'textarea', 'comment', 'Комментарий (подпись к цитате)' )
								->set_width( 60 ),
							Field::make( 'text', 'slug', 'Ярлык (#slug)' )
								->set_width( 20 ),
						)
					)
					->set_header_template( '<%- text.substring(0, 60) %> / <%- slug ? "#" + slug : "" %>' ),
			)
		);
}
add_action( 'carbon_fields_register_fields', 'crb_fields_for_citata' );

/**
 * Add carbon fields to the front page
 *
 * @return void
 */
function crb_fields_for_front_page() {
	Container::make( 'post_meta', 'newscards_2', 'Секция Карточки № 2 (Путеводитель по Орлу)' )
		->where( 'post_id', '=', get_option( 'page_on_front' ) )
		->add_fields(
			array(
				Field::make( 'text', 'nc2_title', 'Заголовок секции' )
					->set_width( 50 ),
				Field::make( 'text', 'nc2_title_link', 'Ссылка заголовка секции' )
					->set_width( 50 ),
				Field::make( 'complex', 'nc2_posts', 'Карточки постов (статьи Путеводителя и т.п.)' )
					->setup_labels(
						array(
							'plural_name'   => 'Посты',
							'singular_name' => 'Пост',
						)
					)
					->set_collapsed( true )
					->add_fields(
						array(
							Field::make( 'association', 'post', 'Пост / Страница / Статья ПВ' )
								->set_types(
									array(
										array(
											'type'      => 'post',
											'post_type' => 'page',
										),
										array(
											'type'      => 'post',
											'post_type' => 'post',
										),
										array(
											'type'      => 'post',
											'post_type' => 'guidebook',
										),
									)
								)
								->set_max( 1 ),
							Field::make( 'text', 'admin_title', 'Заголовок для админки' ),
							Field::make( 'text', 'card_alt_title', 'Альтернативный заголовок карточки' ),
						)
					)
					->set_header_template( '<%- admin_title %> <%- card_alt_title ? "(" + card_alt_title + ")" : "" %>' ),
			)
		);
}
add_action( 'carbon_fields_register_fields', 'crb_fields_for_front_page' );

/**
 * Add fields for events posts
 *
 * @return void
 */
function crb_events_fields() {
	Container::make( 'post_meta', 'evnt_fields', 'Данные события (v.2)' )
		->where( 'post_type', '=', 'events' )
		->add_fields(
			array(
				Field::make( 'text', 'evnt_seasons', 'Сезоны (2018,2019):' ),
				Field::make( 'textarea', 'evnt_alt_texts', 'Альтернативные текста к фоткам (index=alt_title=alt_description)' )
					->set_rows( 4 ),
			)
		);
}
add_action( 'carbon_fields_register_fields', 'crb_events_fields' );
