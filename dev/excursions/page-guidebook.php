<?php
/**
 * The template for displaying guidebook pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package excursions
 */

get_header();

do_action( 'guidebook_map_scripts' );
?>

<main id="main" class="site-main page-guidebook">

	<div class="obj_map">
		<div class="om_block omb_panel" style="display: none;">
			<button class="OpenMap_btn" data-state="open">[ Показать на карте ]</button>
		</div>
		<div class="om_content">
			<div class="om_block omb_topFilter" style="display: none;">
                <form>
					<?php 
					$cat_f_checked 			= isset($_GET['cat_f']) ? 'checked' : '';
					$numberposts_1_checked 	= isset($_GET['numberposts_1']) ? 'checked' : '';
					$numberposts_2_checked 	= isset($_GET['numberposts_2']) ? 'checked' : '';
					$pagenum1_checked 		= (!isset($_GET['pagenum']) || $_GET['pagenum'] == 1) ? 'checked' : '';
					$pagenum2_checked 		= $_GET['pagenum'] == 2 ? 'checked' : '';
					?>
                    <label><input type="checkbox" name="cat_f" <?=$cat_f_checked?>>Ф</label>
                    <label><input type="checkbox" name="numberposts_1" <?=$numberposts_1_checked?>>1</label>
                    <label><input type="checkbox" name="numberposts_2" <?=$numberposts_2_checked?>>2</label>
					<label><input type="radio" name="pagenum" value="1" <?=$pagenum1_checked?>>1</label>
					<label><input type="radio" name="pagenum" value="2" <?=$pagenum2_checked?>>2</label>
                    <button type="submit">Применить</button>
                </form>
            </div>
		</div>
	</div>

	<?php
	// $taxonomy_names = get_object_taxonomies('guidebook');
	$tax_name = 'sections';
	$terms = get_terms( $tax_name );
	if( $terms && ! is_wp_error($terms) ):
		// echo "<ul>";
		$term_counter = 0;
		foreach( $terms as $term ):
			$numberposts = 10;
			if($term_counter == 0){
				if(isset($_GET['numberposts_1'])){
					$numberposts = 1;
				}
				if(isset($_GET['numberposts_2'])){
					$numberposts = 2;
				}
			}
			$myposts = get_guidebook_posts( $term->slug, $numberposts );

			if( $myposts ):
				$term_link = get_term_link( (int)$term->term_id );
				$term_name = $term->name;
				// echo '<li><a href="'.$term_link.'" title="Ссылка на '.$term_name.'">'.$term_name.'</a></li>';
				if($term_counter == 1) echo '<hr />';
				if($term_counter == 0){
					$annocard_title = '<h2 class="annocard-title">'.$term_name.'</h2>';
				}
				else{
					$annocard_title = '<h2 class="annocard-title"><a href="'.$term_link.'" title="Ссылка на '.$term_name.'">'.$term_name.'</a></h2>';
				}
				?>
				<div class="section-container">
					<?=$annocard_title?>
					<div class="row">
					<?php
					foreach( $myposts as $post ):
						setup_postdata( $post );
						$permalink = get_the_permalink(); 
						$title = esc_html( get_the_title() );
						// $title = get_field('gba_rating').' '.$title;
						?>
						<div class="anno-card col-6 col-sm-6 col-md-4 col-lg-3">
							<a href="<?=$permalink?>" title="Ссылка на: <?=$title?>" tabindex="-1">
							<?php 
								// the_post_thumbnail('medium'); 
								$thumb_id = get_post_thumbnail_id();
								echo get_attachment_picture( $thumb_id, 'medium', false, null, true, true ); // medium_large 
							?>
							</a>
							<h3 class="annocard-caption"><a href="<?=$permalink?>" title="Ссылка на: <?=$title?>"><?=$title?></a></h3>
						</div>
					<?php 
					endforeach; 
					wp_reset_postdata();
					?>
					</div> <!-- .anno-card -->

					<?php
					// После первой секции (Достопримечательности) выводим её пагинацию
					if($term_counter == 0){
						// the_posts_pagination();
						$all_posts = get_guidebook_posts(null, -1);
						$total = count($all_posts)/$numberposts;
						// print_r($all_posts);
						$current = (!isset($_GET['pagenum']) || $_GET['pagenum'] == 1) ? 1 : (int)$_GET['pagenum'];
						$args = array(
							'base'         			=> get_url_wo_pagenum().'%_%',
							'format'       			=> '&pagenum=%#%',
							'total'        			=> $total,
							'current'      			=> $current,
							'mid_size'     			=> 1,
							'prev_text'    			=> '<<',
							'next_text'    			=> '>>',
							'screen_reader_text' 	=> __( 'Posts navigation' ),
						); 
						$links = paginate_links( $args );
						if( $links ){
							echo _navigation_markup( $links, 'pagination', $args['screen_reader_text'] );
						}
					}
					?>

				</div> <!-- .section-container -->

				<?php
				// Если это не главная страница Путеводителя (первая стр. пагинации), Описание и другие разделы не выводим
				if($current > 1) break;

				// После первой секции (Достопримечательности) выводим Описание путеводителя 
				if($term_counter == 0):
					if( have_posts() ): ?>
						<div class="main-section">
						<?php
						while( have_posts() ){
							the_post();
							the_content();
						}
						?>
						</div>
					<?php endif;
				endif;

			endif; // $myposts

			$term_counter++;

		endforeach; // $terms as $term
		
		if($term_counter > 1) echo '<hr />';

	endif; // $terms
	?>

</main><!-- #main -->

<?php
// get_sidebar();
get_footer();

