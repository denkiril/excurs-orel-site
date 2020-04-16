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
 * Add guidebook route fields for special pages ('routes', 'map')
 *
 * @return void
 */
function crb_fields_for_gb_routes() {
	$map_page    = get_page_by_path( 'map' );
	$map_page_id = $map_page ? $map_page->ID : null;
	Container::make( 'post_meta', 'gbroutes', 'Поля для раздела "Маршруты, тематические подборки"' )
		// ->show_on_post_type( 'guidebook' )
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
							Field::make( 'image', 'image', 'Картинка' )->set_width( 25 ),
							Field::make( 'textarea', 'desription', 'Описание' )->set_width( 75 ),
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
