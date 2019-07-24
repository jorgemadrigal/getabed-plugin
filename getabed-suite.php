<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ndo.mx/jorge-hm/
 * @since             1.0.0
 * @package           Getabed_Suite
 *
 * @wordpress-plugin
 * Plugin Name:       GetABed Suite
 * Plugin URI:        https://laboratoriowp.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Jorge Iván Hernández
 * Author URI:        https://ndo.mx/jorge-hm/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       getabed-suite
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Adds Foo_Widget widget.
 */
class GetABed extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'getabed_widget', // Base ID
			esc_html__( 'Buscador de Getabed', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'Muestra un bloque de búsqueda de Getabed', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		wp_enqueue_style( 'flatpickr-css', '//cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',false,'1.1','all');

		wp_enqueue_script('flatpickr-js', '//unpkg.com/flatpickr', '', '1', true );
		wp_enqueue_script('flatpickr-l10n-es-js', '//npmcdn.com/flatpickr/dist/l10n/es.js', '', '1', true );

		if ( ! empty( $instance['country'] ) ) {
			if ($instance['country'] == 'es') {
				$lang_form = 'es_MX';
				$locale_js = 'es';
			}
			if ($instance['country'] == 'en') {
				$lang_form = 'en_US';
				$locale_js = 'en';
			}
		}

		$random_var = rand();
	?>

		<?php echo $args['before_widget']; ?>
		<style>
		form.gab { background-color:rgba(0,0,0,0.8); }
		form.gab fieldset { width:100%; margin:0 auto; padding:20px 0; text-align: center }
		form.gab fieldset input { width:33%; }
		form.gab fieldset button { width:25%; }
		form.gab { margin-bottom:50px; }
		@media screen and (max-width: 767px) {
		 form.gab fieldset { width:100%; }
		 form.gab fieldset input { width:70vw; padding:2vw 10vw; margin-left:5vw; margin-bottom:3vw; }
		 form.gab fieldset button { width:90vw; margin-left:5vw; }
		}
		</style>


		<form class="gab" action="//<?php if ( ! empty( $instance['username'] ) ) { echo $instance['username']; } ?>.gabsuite.com/rooms.php" style="background: <?php if ( ! empty( $instance['bg_color'] ) ) { echo $instance['bg_color']; } ?>">
		<fieldset>

			<input type="hidden" name="lang" value="<?php echo $lang_form; ?>">
			<input type="text" id="gab_check_in_<?php echo $random_var; ?>" name="check_in" placeholder="<?php if ( ! empty( $instance['checkin_text'] ) ) { echo $instance['checkin_text']; } ?>">
			<input type="text" id="gab_check_out_<?php echo $random_var; ?>" name="check_out" placeholder="<?php if ( ! empty( $instance['checkout_text'] ) ) { echo $instance['checkout_text']; } ?>">
			<button type="submit"><?php if ( ! empty( $instance['submit_text'] ) ) { echo $instance['submit_text']; } ?></button>
		</fieldset>
		</form>

		<script>
		document.addEventListener('DOMContentLoaded', () => {
			flatpickr(document.querySelector("#gab_check_in_<?php echo $random_var; ?>"), {
				dateFormat: 'd-m-Y',
				minDate: 'today',
				disableMobile: 'true',
				locale: '<?php echo $locale_js; ?>'
			});
			flatpickr(document.querySelector("#gab_check_out_<?php echo $random_var; ?>"), {
				dateFormat: 'd-m-Y',
				minDate: 'today',
				disableMobile: 'true',
				locale: '<?php echo $locale_js; ?>'
			});
		});
		</script>


		<?php echo $args['after_widget']; ?>

	<?php
	} 
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$username = ! empty( $instance['username'] ) ? $instance['username'] : 'demo';
		$bg_color = ! empty( $instance['bg_color'] ) ? $instance['bg_color'] : '#F0F0F0';
		$country = ! empty( $instance['country'] ) ? $instance['country'] : 'es';

		$checkin_text = ! empty( $instance['checkin_text'] ) ? $instance['checkin_text'] : 'Entrada';
		$checkout_text = ! empty( $instance['checkout_text'] ) ? $instance['checkout_text'] : 'Salida';
		$submit_text = ! empty( $instance['submit_text'] ) ? $instance['submit_text'] : 'Reservar';

		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_attr_e( 'Nombre de usuario:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>"><?php esc_attr_e( 'Color de Fondo:', 'text_domain' ); ?></label> 
		<input class="widefat" data-default-color="#f0f0f0" id="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bg_color' ) ); ?>" type="text" value="<?php echo esc_attr( $bg_color ); ?>" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'checkin_text' ) ); ?>"><?php esc_attr_e( 'Texto de Llegada:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'checkin_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'checkin_text' ) ); ?>" type="text" value="<?php echo esc_attr( $checkin_text ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'checkout_text' ) ); ?>"><?php esc_attr_e( 'Texto de Salida:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'checkout_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'checkout_text' ) ); ?>" type="text" value="<?php echo esc_attr( $checkout_text ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'submit_text' ) ); ?>"><?php esc_attr_e( 'Texto de Botón:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'submit_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'submit_text' ) ); ?>" type="text" value="<?php echo esc_attr( $submit_text ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'country' ) ); ?>"><?php esc_attr_e( 'Idioma:', 'text_domain' ); ?></label> 
		<select id="<?php echo $this->get_field_id('country'); ?>" name="<?php echo $this->get_field_name('country'); ?>" class="widefat" style="width:100%;"> 
			<option <?php selected( $instance['country'], 'es'); ?> value="es">Español</option>
			<option <?php selected( $instance['country'], 'en'); ?> value="en">Inglés</option> 

		</select>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['username'] = ( ! empty( $new_instance['username'] ) ) ? sanitize_text_field( $new_instance['username'] ) : '';
		$instance['bg_color'] = ( ! empty( $new_instance['bg_color'] ) ) ? sanitize_text_field( $new_instance['bg_color'] ) : '';
		$instance['country'] = ( ! empty( $new_instance['country'] ) ) ? sanitize_text_field( $new_instance['country'] ) : '';

		$instance['checkin_text'] = ( ! empty( $new_instance['checkin_text'] ) ) ? sanitize_text_field( $new_instance['checkin_text'] ) : '';
		$instance['checkout_text'] = ( ! empty( $new_instance['checkout_text'] ) ) ? sanitize_text_field( $new_instance['checkout_text'] ) : '';
		$instance['submit_text'] = ( ! empty( $new_instance['submit_text'] ) ) ? sanitize_text_field( $new_instance['submit_text'] ) : '';

		return $instance;
	}

} // class GetABed_Widget




// register Foo_Widget widget
function register_getabed_widget() {
    register_widget( 'GetABed' );
}
add_action( 'widgets_init', 'register_getabed_widget' );



function getabed_shortcode( $atts, $content = null ) {
	wp_enqueue_style( 'flatpickr-css', '//cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',false,'1.1','all');

	wp_enqueue_script('flatpickr-js', '//unpkg.com/flatpickr', '', '1', true );
	wp_enqueue_script('flatpickr-l10n-es-js', '//npmcdn.com/flatpickr/dist/l10n/es.js', '', '1', true );

	$atts = shortcode_atts( array(
		'username' => 'demo',
		'bg_color' => '#F0F0F0',
		'country' => 'es',
		'checkin_text' => 'Entrada',
		'checkout_text' => 'Salida',
		'submit_text' => 'Reservar',
		'country' => 'es',
	), $atts, 'getabed' );

	if ( ! empty( $atts['country'] ) ) {
		if ($atts['country'] == 'es') {
			$lang_form = 'es_MX';
			$locale_js = 'es';
		}
		if ($atts['country'] == 'en') {
			$lang_form = 'en_US';
			$locale_js = 'en';
		}
	}

	$random_var = rand();

	$shortcode_var =	'<form class="gab" action="//'. $atts['username'] .'.gabsuite.com/rooms.php" style="background: '. $atts['bg_color'] .'">';
	$shortcode_var .=	'<fieldset>';

	$shortcode_var .=	'	<input type="hidden" name="lang" value="' . $lang_form . ' ">';
	$shortcode_var .=	'	<input type="text" id="gab_check_in_'.$random_var.'" name="check_in" placeholder="'. $atts['checkin_text'] .'">';
	$shortcode_var .=	'	<input type="text" id="gab_check_out_'.$random_var.'" name="check_out" placeholder="'. $atts['checkout_text'] .'">';
	$shortcode_var .=	'	<button type="submit">'. $atts['submit_text'] .'</button>';
	$shortcode_var .=	'</fieldset>';
	$shortcode_var .=	'</form>';

	$shortcode_var .=	'	<script>';
	$shortcode_var .=	'	document.addEventListener(\'DOMContentLoaded\', () => {';
	$shortcode_var .=	'		flatpickr(document.querySelector("#gab_check_in_'.$random_var.'"), {';
	$shortcode_var .=	'			dateFormat: \'d-m-Y\',';
	$shortcode_var .=	'			minDate: \'today\',';
	$shortcode_var .=	'			disableMobile: \'true\',';
	$shortcode_var .=	'			locale: \''. $locale_js .'\'';
	$shortcode_var .=	'		});';
	$shortcode_var .=	'		flatpickr(document.querySelector("#gab_check_out_'.$random_var.'"), {';
	$shortcode_var .=	'			dateFormat: \'d-m-Y\',';
	$shortcode_var .=	'			minDate: \'today\',';
	$shortcode_var .=	'			disableMobile: \'true\',';
	$shortcode_var .=	'			locale: \''. $locale_js .'\'';
	$shortcode_var .=	'		});';
	$shortcode_var .=	'	});';
	$shortcode_var .=	'	</script>';


	return $shortcode_var;
}
add_shortcode( 'getabed', 'getabed_shortcode' );