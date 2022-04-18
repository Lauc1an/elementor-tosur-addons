<?php
/**
 * Tosur Brevete class.
 *
 * @category   Class
 * @package    ElementorTosurAddons
 * @subpackage WordPress
 * @author     Laucian <alexander@aerastudio.pe>
 * @copyright  2022 Laucian
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://github.com/Lauc1an/elementor-tosur-addons,
 *             Elementor Widgets for Tosur Product Forms)
 * @since      1.0.0
 * php version 7.4.26
 */

namespace ElementorTosurAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * TosurForms widget class.
 *
 * @since 1.0.0
 */
class TosurCovid extends Widget_Base {

	/**
	 * Class constructor.
	 *
	 * @param array $data Widget data.
	 * @param array $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'tosur-covid', plugins_url( '/assets/css/tosur-covid.css', ELEMENTOR_TOSUR_ADDONS ), array(), '1.0.0' );
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tosur-covid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Tosur Covid', 'elementor-tosur-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'general' );
	}
	
	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return array( 'tosur-covid' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Contenido', 'elementor-tosur-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'form_name',
			array(
				'label'   => __( 'Form Name', 'elementor-tosur-addons' ),
				'type'    => Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Form Name', 'elementor-tosur-addons' ),
			)
		);

		$this->end_controls_section();

	}
    
	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$name = $settings['form_name'];

		$args = [
			'status' => 'publish',
			'category' => ['covid-19']
		];
		$products = wc_get_products($args);

		// echo "<pre>";
		// print_r($products[0]->get_data());
		// echo "</pre>";

		$zones = \WC_Shipping_Zones::get_zones();
		$methods = array_column($zones, 'shipping_methods');

		// foreach ($methods[0] as $method) {
		// 	echo "<pre>";
		// 	print_r($method->title);
		// 	print_r($method->instance_id);
		// 	echo "</pre>";
		// }

		?>
		<form name="<?= $name; ?>" method="POST" action="">

			<div class="input-group-tosur">
				<input name="<?= $name; ?>_name" type="text" placeholder="Nombre completo">
			</div>

			<div class="input-group-tosur">
				<input name="<?= $name; ?>_dni" type="text" placeholder="DNI">
				<input name="<?= $name; ?>_phone" type="text" placeholder="Teléfono">
			</div>

			<div class="input-group-tosur">
				<select name="<?= $name; ?>_type">
					<option selected disabled>Seleccionar Tipo de Prueba</option>
					<?php foreach($products as $product) {
					$data = $product->get_data();
					?>
					<option value="<?= $data['slug']; ?>"><?= $data['name']; ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="input-group-tosur">
				<input name="<?= $name; ?>_quantity" type="number" placeholder="Cantidad">
				<select name="<?= $name; ?>_district">
					<option selected disabled>Seleccionar Distrito</option>
					<?php foreach($methods[0] as $method) { ?>
					<option value="<?= $method->instance_id; ?>"><?= $method->title; ?></option>
					<?php } ?>
				</select>
			</div>
			
			<div class="input-group-tosur">
				<input name="<?= $name; ?>_date" type="date" placeholder="Fecha">
				<input name="<?= $name; ?>_hour" type="time" placeholder="Hora">
			</div>

			<button type="submit">Enviar</button>

		</form>
		<?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<form name="{{ settings.title }}" method="POST" action="">
			<label for="{{ settings.title }}_name">Nombre</label>
			<input id="{{ settings.title }}_name" type="text">
			<button type="submit">Enviar</button>
		</form>
		<?php
	}
	
}