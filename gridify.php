<?php

/**
 *
 * @link              https://github.com/latheeshvmv/Gridify-for-Elementor
 * @since             10.0.0
 * @package           Pgggo
 *
 * @wordpress-plugin
 * Plugin Name:       Gridify for Elementor
 * Plugin URI:        https://github.com/latheeshvmv/Gridify-for-Elementor
 * Description:       Create Grid layout for posts, pages & custom post types. Unlimited design possiblites, supports custom fields as well.
 * Version:           10.0.0
 * Author:            Latheesh V M Villa
 * Author URI:        https://www.instagram.com/latheeshvmv/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pgggo
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
defined( 'ABSPATH' ) || exit;
define( 'PGGGO_PLUGIN_FILE', __FILE__ );
define( 'PGGGO_VERSION', '10.0.0' );

function pgggo_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pgggo-activator.php';
	Pgggo_Activator::pgggo_activate();
}

function pgggo_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pgggo-deactivator.php';
	Pgggo_Deactivator::pgggo_deactivate();
}

register_activation_hook( __FILE__, 'pgggo_activate' );
register_deactivation_hook( __FILE__, 'pgggo_deactivate' );

// Core plugin functionality
require plugin_dir_path( __FILE__ ) . 'includes/elementor/pgggo-elementor-widget-register.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-pgggo.php';
if ( ! did_action( 'elementor/loaded' ) ) {
			return;
}else{
	require plugin_dir_path( __FILE__ ) . 'includes/elementor/class-pgggo-image-render.php';
}
require plugin_dir_path( __FILE__ ) . 'includes/adv/class-pgggo-configurator.php';
require plugin_dir_path( __FILE__ ) . 'includes/adv/class-pgggo-plugin-specs.php';


// Elementor Widget Registration


function pgggo_run(){
	 $plugin = new PGGGOCORENS\Pgggo();
}

pgggo_run();


function pgggo_query_vars( $pgggo_qvars ) {
		$args = array(
		'public'   => true,);
		$output = 'objects';

		$taxonomies = get_taxonomies( $args,$output );
		$formated = array();
		if ( $taxonomies ) {
		foreach ( $taxonomies as $taxonomy ) {
				$formated[$taxonomy->name] =   $taxonomy->labels->name;
				}
		}
		if(is_array($formated) && !empty($formated)){
			foreach ($formated as $key => $value) {
				for ($i=0; $i < 5; $i++) {
					$pgggo_qvars[] = 'pgggo-taxon-select-'.$key.'-'.$i;
				}
			}
		}

		$formated_new = array();
		if ( $taxonomies ) {
		foreach ( $taxonomies as $taxonomy ) {
				$formated_new[$taxonomy->name] =   $taxonomy->labels->name;
				}
		}
		foreach ($formated_new as $key => $value) {
			$terms = get_terms([
					'taxonomy' => $key,
					'hide_empty' => false,
			]);
			$saveterm = $key;
			foreach ($terms as $keyterm => $valuekey) {
				$pgggo_qvars[] = 'pgggo-'.$saveterm.'-sep-'.$valuekey->term_id;
			}
		}

    $pgggo_qvars[] = 'pgggo-sort-acend';
		$pgggo_qvars[] = 'pgggo-sort-decend';
    return $pgggo_qvars;
}
add_filter( 'query_vars', 'pgggo_query_vars' );



function pgggo_transient_del_save_post_callback($post_id){
    global $post;
		require_once(ABSPATH . 'wp-admin/includes/screen.php');
		$screen = get_current_screen();
		if( !is_object($post) ){
			     return;
		}
    if ($post->post_type == 'acf-field-group'){
				delete_transient( 'pgggo_acf_list_transient' );
        return;
    }
}
add_action('save_post','pgggo_transient_del_save_post_callback');
add_action('after_delete_post','pgggo_transient_del_save_post_callback');



function pgggo_plugin_spec_run()
{
    require plugin_dir_path(__FILE__) . 'includes/config/pgggo-configurator.php';
    $pgggo_plugin_spec = new PGGGO_SPECS\pgggoSpecs($pgggo_data);
}
pgggo_plugin_spec_run();
