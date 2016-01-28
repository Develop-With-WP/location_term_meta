<?php
/*
Plugin Name: Term Meta Series
Plugin URI: http://www.github.com/mrbobbybryant/location_term_meta
Description: Used to demonstrate how to work with the term meta feature introduced in WordPress 4.4.
Author: Bobby Bryant
Version: 1.0.0
Author URI: twitter.com/mrbobbybryant
*/

namespace Location_Term_Meta;

function setup() {
	add_action( 'init', __NAMESPACE__ . '\register_location_taxonomy' );
	add_action( 'location_add_form_fields', __NAMESPACE__  . '\new_location_social_metadata' );
	add_action( 'location_edit_form_fields', __NAMESPACE__  . '\edit_location_social_metadata' );
	add_action( 'edit_location', __NAMESPACE__  . '\save_location_social_metadata' );
	add_action( 'create_location', __NAMESPACE__  . '\save_location_social_metadata' );
}

setup();

function register_location_taxonomy() {
	$labels = array(
		'name'              => _x( 'Locations', 'text-domain' ),
		'singular_name'     => _x( 'Location', 'text-domain' ),
		'search_items'      => __( 'Search Locations', 'text-domain' ),
		'all_items'         => __( 'All Locations', 'text-domain' ),
		'parent_item'       => __( 'Parent Location', 'text-domain' ),
		'parent_item_colon' => __( 'Parent Location:', 'text-domain' ),
		'edit_item'         => __( 'Edit Location', 'text-domain' ),
		'update_item'       => __( 'Update Location', 'text-domain' ),
		'add_new_item'      => __( 'Add New Location', 'text-domain' ),
		'new_item_name'     => __( 'New Location Name', 'text-domain' ),
		'menu_name'         => __( 'Location', 'text-domain' ),
	);

	register_taxonomy( 'location', 'post', array( 'labels' => $labels ) );
}

function supported_social_networks() {
	return array(
			'facebook'  => esc_html__( 'Facebook', 'text-domain' ),
			'twitter'   => esc_html__( 'Twitter', 'text-domain' ),
			'linkedin'  => esc_html__( 'LinkedIn', 'text-domain' )
	);
}

function new_location_social_metadata() {
	wp_nonce_field( basename( __FILE__ ), 'location_social_nonce' );
	$social_networks = supported_social_networks(); ?>

	<th scope="row" valign="top" colspan="2">
		<h3><?php esc_html_e( 'Social Network Options', 'text-domain' ); ?></h3>
	</th>

	<?php foreach ( $social_networks as $network => $value ) { ?>
		<div class="form-field location-metadata">
			<label for="<?php printf( esc_html__( '%s-metadata', 'text-domain' ), $network ); ?>">
				<?php printf( esc_html__( '%s URL', 'text-domain' ), esc_html( $value ) ); ?>
			</label>
			<input
				type="text"
				name="<?php printf( esc_html__( 'location_%s_metadata', 'text-domain' ), esc_attr( $network ) ); ?>"
				id="<?php printf( esc_html__( '%s-metadata', 'text-domain' ), esc_attr( $network ) ); ?>"
				value=""
				class="social-metadata-field"
			/>
		</div>
	<?php }
}
function edit_location_social_metadata( $term ) {
	wp_nonce_field( basename( __FILE__ ), 'location_social_nonce' );
	$social_networks = supported_social_networks(); ?>

	<th scope="row" valign="top" colspan="2">
		<h3><?php esc_html_e( 'Social Network Options', 'text-domain' ); ?></h3>
	</th>

	<?php foreach ( $social_networks as $network => $value ) {
		$term_key = sprintf( 'location_%s_metadata', $network );
		$metadata = get_term_meta( $term->term_id, $term_key, true ); ?>

		<tr class="form-field location-metadata">
			<th scope="row">
				<label for="<?php printf( esc_html__( '%s-metadata', 'text-domain' ), $network ); ?>">
					<?php printf( esc_html__( '%s URL', 'text-domain' ), esc_html( $value ) ); ?>
				</label>
			</th>
			<td>
				<input type="text"
				       name="<?php printf( esc_html__( 'location_%s_metadata', 'text-domain' ), esc_attr( $network ) ); ?>"
				       id="<?php printf( esc_html__( '%s-metadata', 'text-domain' ), esc_attr( $network ) ); ?>"
				       value="<?php echo ( ! empty( $metadata ) ) ? esc_attr( $metadata ) : ''; ?>"
				       class="social-metadata-field"
				/>
			</td>
		</tr>
	<?php }
}

function save_location_social_metadata( $term_id ) {
	/**
	 * Check if nonce is set
	 */
	if ( ! isset( $_POST[ 'location_social_nonce' ] ) ) {
		return;
	}
	/**
	 * Verify Nonce
	 */
	if ( ! wp_verify_nonce( $_POST['location_social_nonce'], basename( __FILE__ ) ) ) {
		return;
	}
	$social_networks = supported_social_networks();
	foreach ( $social_networks as $network => $value ) {
		$term_key = sprintf( 'location_%s_metadata', $network );
		if ( isset( $_POST[ $term_key ] ) ) {
			update_term_meta( $term_id, esc_attr( $term_key ), esc_url_raw( $_POST[ $term_key ] ) );
		}
	}
}

