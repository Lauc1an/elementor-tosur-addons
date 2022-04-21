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
 * TosurBrevete widget class.
 *
 * @since 1.0.0
 */
class TosurBrevete extends Widget_Base {

	/**
	 * Class constructor.
	 *
	 * @param array $data Widget data.
	 * @param array $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'tosur-brevete', plugins_url( '/assets/css/tosur-brevete.css', ELEMENTOR_TOSUR_ADDONS ), array(), '1.0.0' );
		wp_register_script( 'tosur-brevete', plugins_url( '/assets/js/tosur-brevete.js', ELEMENTOR_TOSUR_ADDONS ) );
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
		return 'tosur-brevete';
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
		return __( 'Tosur Brevete', 'elementor-tosur-addons' );
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
		return array( 'tosur-brevete' );
	}

	/**
	 * Enqueue scripts.
	 */
	public function get_script_depends() {
		return array( 'tosur-brevete' );
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
			'category' => ['brevete']
		];
		$products = wc_get_products($args);

		$zones = \WC_Shipping_Zones::get_zones();
		$methods = array_column($zones, 'shipping_methods');

		// echo "<pre>";
		// var_dump($methods[1]);
		// echo "<pre>";

		?>
		<form name="<?= $name; ?>" id="tosur-form-brevete" method="POST">

			<div class="input-group-tosur">
				<input name="nombre" type="text" placeholder="Nombre completo">
			</div>

			<div class="input-group-tosur">
				<input name="correo" type="email" placeholder="Correo electrónico">
			</div>

			<div class="input-group-tosur">
				<input name="dni" type="text" placeholder="DNI">
				<input name="telefono" type="text" placeholder="Teléfono">
			</div>

			<div class="input-group-tosur">
				<select name="product_id">
					<option selected disabled>Seleccionar Servicio</option>
					<?php foreach($products as $product) {
					$data = $product->get_data();
					?>
					<option value="<?= $data['id']; ?>"><?= $data['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			
			<div class="input-group-tosur">
				<select name="categoria">
					<option selected disabled>Seleccionar Categoría</option>
					
				</select>
			</div>

			<div class="input-group-tosur">
				<select name="distrito">
					<option selected disabled>Seleccionar Sede</option>
					<?php foreach($methods[1] as $method) { ?>
					<option value="<?= $method->instance_id; ?>"><?= $method->title; ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="input-group-tosur">
				<input name="fecha" type="date" placeholder="Fecha">
				<input name="hora" type="time" placeholder="Hora">
			</div>

			<button type="submit">Enviar</button>
		</form>
		<?php
	}

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