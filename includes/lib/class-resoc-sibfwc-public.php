<?php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . 'class-resoc-sibfwc-utils.php';

class Resoc_SIBfWC_Public {

  const OG_IMAGE_WIDTH = 1200;
  const OG_IMAGE_HEIGHT = 630;

	public function __construct () {
		// Disable Jetpack Open Graph markups
    add_filter( 'jetpack_enable_open_graph', '__return_false' );

    if ( Resoc_SIBfWC_Utils::is_yoast_seo_active() ) {
      add_filter(
        'wpseo_add_opengraph_images',
        array( $this, 'add_opengraph_image_for_wpseo' )
      );
      add_filter(
        'wpseo_twitter_image',
        array( $this, 'add_twitter_image_for_wpseo' )
      );
      // Work completed
      return;
    }

    if ( Resoc_SIBfWC_Utils::is_wonderm00ns_active() ) {
      $fb_image = add_filter(
        'fb_og_image',
        array( $this, 'add_opengraph_image_for_wonderm00ns' )
      );
      // Work completed
      return;
    }

    // If we are here, it means the markups were not included
    add_action( 'wp_head', array( $this, 'add_social_markups' ) );
  }

  public function add_social_markups() {
    $post_id = get_the_ID();

    if ( ! Resoc_SIBfWC_Utils::is_product( $post_id ) ) {
      error_log("Not a product");
      return;
    }

    $product_url = wp_get_canonical_url( $post_id );
    if ( ! $product_url ) {
      error_log("No product canonical URL");
      return;
    }
    $separator = strpos( $post_url, '?' ) ? '&' : '?';
    $product_url = $product_url . $separator . 'origin=shared_on_facebook';

    $product_page_title = get_the_title( $post_id );

    // OpenGraph (Facebook, LinkedIn...)
    $facebook_image_url = Resoc_SIBfWC_Utils::get_facebook_image_url( $post_id );
    if ( $facebook_image_url ) {
      echo '<meta property="og:image" content="' .
      $facebook_image_url .
        '">' . "\n";
      echo '<meta property="og:image:width" content="' .
        Resoc_SIBfWC_Public::OG_IMAGE_WIDTH . '">' . "\n";
      echo '<meta property="og:image:height" content="' .
        Resoc_SIBfWC_Public::OG_IMAGE_HEIGHT . '">' . "\n";
      echo '<meta property="og:url" content="' . $product_url . '">' . "\n";
    }

    // Twitter card
    $twitter_image_url = Resoc_SIBfWC_Utils::get_twitter_image_url( $post_id );
    if ( $twitter_image_url ) {
      echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
      echo '<meta name="twitter:title" content="' . htmlspecialchars( $product_page_title ) . '">' . "\n";
      echo '<meta name="twitter:image" content="' .
        $twitter_image_url . '">' . "\n";
    }
  }

  // Yoast

  public function add_opengraph_image_for_wpseo( $wpseo_opengraph_image ) {
    $post_id = get_the_ID();

    if ( ! Resoc_SIBfWC_Utils::is_product( $post_id ) ) {
      return;
    }

    $facebook_image_url = Resoc_SIBfWC_Utils::get_facebook_image_url( $post_id );

    if ( $facebook_image_url ) {
      $wpseo_opengraph_image->add_image( array(
        'url'    => $facebook_image_url,
        'width'  => Resoc_SIBfWC_Public::OG_IMAGE_WIDTH,
        'height' => Resoc_SIBfWC_Public::OG_IMAGE_HEIGHT
      ) );
    }
  }

  public function add_twitter_image_for_wpseo( ) {
    $post_id = get_the_ID();

    if ( ! Resoc_SIBfWC_Utils::is_product( $post_id ) ) {
      return;
    }

    return Resoc_SIBfWC_Utils::get_twitter_image_url( $post_id );
  }

  public function add_opengraph_image_for_wonderm00ns() {
    $post_id = get_the_ID();

    if ( ! Resoc_SIBfWC_Utils::is_product( $post_id ) ) {
      return;
    }

    return Resoc_SIBfWC_Utils::get_facebook_image_url( $post_id );
  }
}
