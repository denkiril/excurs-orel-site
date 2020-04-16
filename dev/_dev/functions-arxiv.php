<?php
/**
 * Arxiv of functions of Excursions theme
 *
 * @package excursions
 */

/**
 * Function get_lazy_attachment_image() is mod of standart wp_get_attachment_image()
 *
 * @param int          $attachment_id Image attachment ID.
 * @param string|int[] $size          Optional. Image size. Accepts any valid image size name, or an array of width
 *                                    and height values in pixels (in that order). Default 'thumbnail'.
 * @param bool         $icon          Optional. Whether the image should fall back to a mime type icon. Default false.
 * @param string|array $attr {
 *     Optional. Attributes for the image markup.
 *
 *     @type string $src    Image attachment URL.
 *     @type string $class  CSS class name or space-separated list of classes.
 *                          Default `attachment-$size_class size-$size_class`,
 *                          where `$size_class` is the image size being requested.
 *     @type string $alt    Image description for the alt attribute.
 *     @type string $srcset The 'srcset' attribute value.
 *     @type string $sizes  The 'sizes' attribute value.
 * }
 * @return string HTML img element or empty string on failure.
 */
function get_lazy_attachment_image( $attachment_id, $size = 'thumbnail', $icon = false, $attr = '' ) {
	$html  = '';
	$image = wp_get_attachment_image_src( $attachment_id, $size, $icon );
	if ( $image ) {
		list( $src, $width, $height ) = $image;
		$hwstring                     = image_hwstring( $width, $height );
		$size_class                   = $size;
		if ( is_array( $size_class ) ) {
			$size_class = join( 'x', $size_class );
		}
		$attachment   = get_post( $attachment_id );
		$default_attr = array(
			'src'      => '/wp-content/themes/excursions/assets/include/ajax-loader.gif', // for validation.
			'data-src' => $src, // was: 'src' => $src.
			'class'    => "attachment-$size_class size-$size_class",
			'alt'      => trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
		);

		$attr = wp_parse_args( $attr, $default_attr );

		// Generate 'srcset' and 'sizes' if not already present.
		if ( empty( $attr['srcset'] ) ) {
			$image_meta = wp_get_attachment_metadata( $attachment_id );

			if ( is_array( $image_meta ) ) {
				$size_array = array( absint( $width ), absint( $height ) );
				$srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $attachment_id );
				$sizes      = wp_calculate_image_sizes( $size_array, $src, $image_meta, $attachment_id );

				if ( $srcset && ( $sizes || ! empty( $attr['sizes'] ) ) ) {
					$attr['data-srcset'] = $srcset;

					if ( empty( $attr['sizes'] ) ) {
						$attr['data-sizes'] = $sizes;
					}
				}
			}
		}

		/**
		 * Filters the list of attachment image attributes.
		 *
		 * @since 2.8.0
		 *
		 * @param array        $attr       Attributes for the image markup.
		 * @param WP_Post      $attachment Image attachment post.
		 * @param string|array $size       Requested size. Image size or array of width and height values
		 *                                 (in that order). Default 'thumbnail'.
		 */
		$attr = apply_filters( 'get_lazy_attachment_image_attributes', $attr, $attachment, $size );
		$attr = array_map( 'esc_attr', $attr );
		$html = rtrim( "<img $hwstring" );
		foreach ( $attr as $name => $value ) {
			$html .= " $name=" . '"' . $value . '"';
		}
		$html .= ' />';
	}

	return $html;
}
