<?php
class Resoc_SIBfWC_Utils {
  public static function is_yoast_seo_active() {
		return is_plugin_active( 'wordpress-seo/wp-seo.php' );
  }

  // For Open Graph for Facebook, Google+ and Twitter Card Tags, by webdados/wonderm00n
  // See https://wordpress.org/plugins/wonderm00ns-simple-facebook-open-graph-tags/
  public static function is_wonderm00ns_active() {
		return is_plugin_active( 'wonderm00ns-simple-facebook-open-graph-tags/wonderm00n-open-graph.php' );
  }

  public static function add_image_to_media_library( $image_data, $post_id, $filename = 'og-image.jpg', $attach_id = NULL ) {
    $upload_dir = wp_upload_dir();

    if (wp_mkdir_p($upload_dir['path'])) {
      $file = $upload_dir['path'] . '/' . $filename;
    }
    else {
      $file = $upload_dir['basedir'] . '/' . $filename;
    }

    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null);

    if (! $attach_id) {
      // Create new attachement if there is none
      // (else, the image is attached to the existing attachement)
      $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
      );

      $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
  }

  public static function generate_resoc_image($api_entry_point_url, $request, $filename = NULL, $attach_id = NULL) {
		$response = wp_remote_post($api_entry_point_url, array(
      'body' => json_encode( $request ),
      'timeout' => 10
		));

		if ( is_wp_error( $response ) ) {
      error_log( "Error while generating: " . $response->get_error_message() );
			throw new Exception( $response->get_error_message() );
    }

    return Resoc_SIBfWC_Utils::add_image_to_media_library( $response['body'], $post_id, $filename, $attach_id );
  }

  public static function get_image_content_by_id( $image_id ) {
		$image_url = wp_get_attachment_url( $image_id );
		$result = wp_remote_get( $image_url );
		if (is_wp_error( $result )) {
			error_log( "Cannot download image: " . $result->get_error_message() );
			throw new Exception( $result->get_error_message() );
		}
		return wp_remote_retrieve_body( $result );
  }

  // Returns '20181030-114327'
  public static function time_to_filename_fragment() {
    return date('Ymd-his');
  }


  public static function get_post_image_url( $post_id ) {
    $image_id = get_post_meta( $post_id, '_thumbnail_id', true );
    if ( ! $image_id ) {
      error_log("No image id for post " . $post_id );
      return NULL;
    }

    $image_url = wp_get_attachment_image_url( $image_id, 'full' );
    if (! $image_url ) {
      error_log("No image URL for post " . $post_id . " and image " . $image_id );
      return NULL;
    }

    return $image_url;
  }

  public static function get_facebook_image_url( $post_id ) {
    return Resoc_SIBfWC_Utils::get_social_network_image_url( 'fb', $post_id );
  }

  public static function get_twitter_image_url( $post_id ) {
    return Resoc_SIBfWC_Utils::get_social_network_image_url( 'twitter', $post_id );
  }

  public static function get_social_network_image_url( $social_network, $post_id ) {
    $image_url = Resoc_SIBfWC_Utils::get_post_image_url( $post_id );
    if ( ! $image_url ) {
      return NULL;
    }

    $site_string = '';
    $site_id = get_option( Resoc_SIBfWC::OPTION_RESOC_SITE_ID );
    if ( $site_id ) {
      $site_string = 'merchant=' . $site_id . '&';
    }

    $product = wc_get_product( $post_id );
    $stars = NULL;
    if ( $product ) {
      $rating_count = $product->get_rating_count();
      $stars = $product->get_average_rating();
      error_log(
        "Product " . $post_id . " has " . $rating_count .
        " reviews, with an average score of " . $stars
      );
      if ( $rating_count <= 0 ) {
        $stars = NULL;
      }
    }
    else {
      error_log("Post " . $post_id . " is not a WooCommerce product");
    }

    return 'http://resoc.io/api/to-' . $social_network . '.jpg' .
      '?' . $site_string . 'imageUrl=' . $image_url .
      ( ( $stars !== NULL ) ? '&stars=' . $stars : '' );
  }

  public static function is_product( $post_id ) {
    return ( get_post_field( 'post_type', $post_id ) === 'product' );
  }
}
