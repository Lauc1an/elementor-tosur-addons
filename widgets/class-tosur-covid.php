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
		wp_register_style( 'tosur-covid', plugins_url( '/assets/css/tosur-forms.css', ELEMENTOR_TOSUR_ADDONS ), array(), '1.0.0' );
		wp_register_script( 'tosur-covid', plugins_url( '/assets/js/tosur-covid.js', ELEMENTOR_TOSUR_ADDONS ) );
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
	 * Enqueue scripts.
	 */
	public function get_script_depends() {
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
			'category' => ['covid-19'],
			'orderby' => 'menu_order',
			'order' => 'ASC',
		];
		$products = wc_get_products($args);

		$zones = \WC_Shipping_Zones::get_zones();
		$methods = array_column($zones, 'shipping_methods');

		?>
		<form name="<?= $name; ?>" class="tosur-form-covid" method="POST">
			<div class="input-group-tosur">
				<input name="nombre" type="text" placeholder="Nombre completo" required>
			</div>
			<div class="input-group-tosur">
				<input name="correo" type="email" placeholder="Correo electrónico" required>
			</div>
			<div class="input-group-tosur">
				<input name="dni" type="text" placeholder="DNI" required>
				<input name="telefono" type="text" placeholder="Teléfono" required>
			</div>
			<div class="input-group-tosur">
				<select name="product_id" required>
					<option selected disabled>Seleccionar Tipo de Prueba</option>
					<?php foreach($products as $product) {
					$data = $product->get_data();
					?>
					<option value="<?= $data['id']; ?>"><?= $data['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="input-group-tosur">
				<input name="quantity" type="number" placeholder="Cantidad" required>
				<select name="distrito" required>
					<option selected disabled>Seleccionar Distrito</option>
<<<<<<< HEAD
					<?php foreach($methods[0] as $method) { ?>
=======
					<?php foreach($methods[0] as $method) {
						if ($method->instance_id == 20) {
							continue;
						} ?>
>>>>>>> 9147ad65f058579f1e283a668559c6684e5373a1
					<option value="<?= $method->id.$method->instance_id; ?>"><?= $method->title; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="input-group-tosur">
				<input name="direccion" type="text" placeholder="Dirección">
			</div>
			<div class="input-group-tosur">
				<input name="fecha" type="date" placeholder="Fecha" required>
				<input name="hora" type="time" placeholder="Hora" required>
			</div>
			<button type="submit" class="btn-tosur">Agendar Cita</button>
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
