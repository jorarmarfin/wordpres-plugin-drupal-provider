<?php
/*
	Plugin Name: Drupal Provider
	Plugin URI: https://drinux.com/

	Description: Api connect Drupal
	Tags: api, json, http
	Author: Luis Mayta
	Version: 1.0.1
	Requires PHP: 7.4
*/
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
use DrupalProvider\Service\ConectorDrupal;

add_action('admin_menu','drupal_provider_add_admin_menu');
add_action('admin_menu','drupal_provider_settings_init');

function drupal_provider_add_admin_menu(){
	add_menu_page(
		'Configuración Drupal Provider',//tiulo de pagina
		'Drupal provider',//titulo del menu
		'manage_options',//capatibility
		'drupal-provider',//slug
		'MostrarContenido',//funcion del contenido
		plugin_dir_url(__FILE__).'assets/icons/drupal-20x20-white.png',
		1
	);
}
function drupal_provider_settings_init(  ) { 

	register_setting( 'DrupalProvider', 'drupal_provider_settings' );

	add_settings_section(
		'drupal_provider_DrupalProvider_section', 
		__( '', 'hello' ), 
		'drupal_provider_settings_section_callback', 
		'DrupalProvider'
	);

	add_settings_field( 
		'drupal_provider_text_domain', 
		__( 'Ingresar dominio de drupal', 'hello' ), 
		'drupal_provider_text_domain_render', 
		'DrupalProvider', 
		'drupal_provider_DrupalProvider_section' 
	);
}
function drupal_provider_text_domain_render(  ) { 

	$options = get_option( 'drupal_provider_settings' );
	?>
	<input size='50' type='text' name='drupal_provider_settings[drupal_provider_text_domain]' value='<?php echo $options['drupal_provider_text_domain']; ?>'>
	<?php

}
function drupal_provider_settings_section_callback(  ) { 

	echo __( 'En esta seccion se realiza la configuracion para conectarse a drupal', 'hello' );

}
function MostrarContenido()
{
	?>
    <h2>Configuracion Drupal Provider</h2>
    <form action="options.php" method="post">
        <?php 
        settings_fields( 'DrupalProvider' );
        do_settings_sections( 'DrupalProvider' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    <?php
}

add_shortcode( 'drupal_conector', 'drupal_provider_conector' );
function drupal_provider_init(){
	function drupal_provider_conector($atts) {

		$nid = (isset($_GET['nid']))? $_GET['nombre'] : 13 ;

		$pairs = [
			'content_type'=>'normatividad',
			'campo'=>'title',
		];
		$node = shortcode_atts($pairs,$atts);
		$options = get_option( 'drupal_provider_settings' );
		$drupalEndpoint = $options['drupal_provider_text_domain'];
		$drupal = (new ConectorDrupal())->getServiceDrupal($atts['content_type'],$nid);
		return $drupal[$node['campo']];
	}
}
add_action('init', 'drupal_provider_init');
