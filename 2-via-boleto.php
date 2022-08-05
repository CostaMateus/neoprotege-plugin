<?php
/**
 * Plugin Name:          #Neo Protege 2ª via do boleto
 * Description:          Gera 2ª via do boleto a partir do CPF do cliente.
 * Author:               Mateus Costa
 * Author URI:           https://www.linkedin.com/in/costamateus6/
 * Version:              1.1
 * Text Domain:          np_2_via
 * WC requires at least: 5.8
 * WC tested up to:      6.0.1
 *
 * @package NP_2_VIA
 */

defined( "ABSPATH" ) || exit;

if ( ! class_exists( "NP_2_VIA" ) ) include_once dirname( __FILE__ ) . "/includes/classes/class-np-2-via.php";
