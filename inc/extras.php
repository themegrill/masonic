<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package ThemeGrill
 * @subpackage Masonic
 * @since 1.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function masonic_body_classes($classes) {
// Adds a class of group-blog to blogs with more than 1 published author.
   if (is_multi_author()) {
      $classes[] = 'group-blog';
   }

   return $classes;
}

add_filter('body_class', 'masonic_body_classes');

function masonic_author_links() {
   $author_firstname = get_the_author_meta('first_name');

   $author_twitter = get_the_author_meta('twitter');
   $author_googleplus = get_the_author_meta('googleplus');
   $author_facebook = get_the_author_meta('facebook');
   if ($author_twitter || $author_googleplus || $author_facebook) {
      echo '<ul><li>';
   }
   if ($author_twitter) {
      echo '<a class="author-links" href="https://twitter.com/' . esc_attr($author_twitter) . '" title="' . sprintf(__('Follow %s on Twitter', 'masonic'), $author_firstname) . '"  target="_blank"><i class="fa fa-twitter"></i></a>';
   }
   if ($author_googleplus) {
      echo '<a class="author-links" href="' . esc_url($author_googleplus) . '" title="' . sprintf(__('Follow %s on Google+', 'masonic'), $author_firstname) . '"  rel="author" target="_blank"><i class="fa fa-google-plus"></i></a>';
   }
   if ($author_facebook) {
      echo '<a class="author-links" href="' . esc_url($author_facebook) . '" title="' . sprintf(__('Follow %s on Facebook', 'masonic'), $author_firstname) . '"  target="_blank"><i class="fa fa-facebook"></i></a>';
   }
   echo '</li></ul>';
}

if (!function_exists('masonic_header_title')) :

   /**
    * Show the title in header
    */
   function masonic_header_title() {
      if (is_archive()) {
         if (is_category()) :
            $masonic_header_title = single_cat_title('Category: ', FALSE);

         elseif (is_tag()) :
            $masonic_header_title = single_tag_title('Tag: ', FALSE);

         elseif (is_author()) :
            /* Queue the first post, that way we know
             * what author we're dealing with (if that is the case).
             */
            the_post();
            $masonic_header_title = sprintf(__('Author: %s', 'masonic'), '<span class="vcard">' . get_the_author() . '</span>');
            /* Since we called the_post() above, we need to
             * rewind the loop back to the beginning that way
             * we can run the loop properly, in full.
             */
            rewind_posts();

         elseif (is_day()) :
            $masonic_header_title = sprintf(__('Day: %s', 'masonic'), '<span>' . get_the_date() . '</span>');

         elseif (is_month()) :
            $masonic_header_title = sprintf(__('Month: %s', 'masonic'), '<span>' . get_the_date('F Y') . '</span>');

         elseif (is_year()) :
            $masonic_header_title = sprintf(__('Year: %s', 'masonic'), '<span>' . get_the_date('Y') . '</span>');

         else :
            $masonic_header_title = __('Archives', 'masonic');

         endif;
      }
      elseif (is_404()) {
         $masonic_header_title = __('404: Page Not Found', 'masonic');
      } elseif (is_search()) {
         $masonic_header_title = sprintf( __( 'Search Results For: %s', 'masonic' ), get_search_query() );
      } elseif (is_page()) {
         $masonic_header_title = get_the_title();
      } elseif (is_single()) {
         $masonic_header_title = get_the_title();
      } else {
         $masonic_header_title = '';
      }

      return $masonic_header_title;
   }

endif;

// Displays the site logo
if ( ! function_exists( 'masonic_the_custom_logo' ) ) {
  /**
   * Displays the optional custom logo.
   */
  function masonic_the_custom_logo() {
    if ( function_exists( 'the_custom_logo' )  && ( get_theme_mod( 'masonic_logo','' ) == '') ) {
      the_custom_logo();
    }
  }
}

/**
 * Function to transfer the Header Logo added in Customizer Options of theme to Site Logo in Site Identity section
 */
function masonic_site_logo_migrate() {
	if ( function_exists( 'the_custom_logo' ) && ! has_custom_logo( $blog_id = 0 ) ) {
		$logo_url = get_theme_mod( 'masonic_logo' );

		if ( $logo_url ) {
			$customizer_site_logo_id = attachment_url_to_postid( $logo_url );
			set_theme_mod( 'custom_logo', $customizer_site_logo_id );

			// Delete the old Site Logo theme_mod option.
			remove_theme_mod( 'masonic_logo' );
		}
	}
}

add_action( 'after_setup_theme', 'masonic_site_logo_migrate' );

/**************************************************************************************/

/**
  * Migrate any existing theme CSS codes added in Customize Options to the core option added in WordPress 4.7
  */
 function masonic_custom_css_migrate() {

   	if ( function_exists( 'wp_update_custom_css_post' ) ) {
		$custom_css = get_theme_mod( 'masonic_custom_css' );
		if ( $custom_css ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return = wp_update_custom_css_post( $core_css . $custom_css );
			if ( ! is_wp_error( $return ) ) {
				// Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
				remove_theme_mod( 'masonic_custom_css' );
			}
		}
	}
}

add_action( 'after_setup_theme', 'masonic_custom_css_migrate' );

/**************************************************************************************/
/**
 * Making the theme Woocommrece compatible
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_filter( 'woocommerce_show_page_title', '__return_false' );

add_action('woocommerce_before_main_content', 'masonic_wrapper_start', 10);
add_action('woocommerce_before_main_content', 'masonic_inner_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'masonic_inner_wrapper_end', 10);
add_action('woocommerce_after_main_content', 'masonic_wrapper_end', 10);

function masonic_wrapper_start() {
	echo '<div class="site-content"><div id="container" class="wrapper clear">';
}

function masonic_inner_wrapper_start() {
	if ( is_single() || is_page() ) {
		echo '<div class="primary">';
	}
}

function masonic_inner_wrapper_end() {
	if ( is_single() || is_page() ) {
		echo '</div>';
		get_sidebar();
	}
}

function masonic_wrapper_end() {
	echo '</div></div>';
}
