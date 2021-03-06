<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

?>

<section class="no-results not-found">
	<header class="page-header">
		<?php // esc_html_e( 'Nothing Found', 'excursions' ); ?>
		<h1 class="page-title"><?php esc_html_e( 'Ничего не найдено по вашему запросу :(', 'excursions' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :

			printf(
				'<p>' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'excursions' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);

		elseif ( is_search() ) :
			?>

			<?php // esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'excursions' ); ?>
			<p>Ищите ещё!</p>
			<?php
			get_search_form();

		else :
			?>

			<p><?php // esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'excursions' ); ?>
			<?php esc_html_e( 'Так уж вышло, что на этой странице ничего нет. Но на других-то точно есть! :)', 'excursions' ); ?></p>
			<?php
			// get_search_form();

		endif;
		?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
