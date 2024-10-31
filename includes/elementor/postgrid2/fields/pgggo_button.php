<?php
if (!defined('ABSPATH')) {
  exit;
}

$pgggo_repeatergrid->add_control(
  'button_field_type',
  [
    'label'     => esc_html__('Button Field Type', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::SELECT,
    'default'   => 'linktopost',
    'options'   => [
      'linktopost'        => esc_html__('Link to post', 'pgggo'),
      'customfieldurlacfdonwload' => esc_html__('ACF File | On click Download file', 'pgggo'),
      'customfieldurlacf' => esc_html__('ACF | Link to Custom field url', 'pgggo'),
      'woocommercecart'   => esc_html__('WooCommerce add to cart button','pgggo'),
    ],
    'condition' => [
      'field_type' => 'button',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'customfieldurl_gen',
  [
    'label'       => esc_html__('Custom Field Name', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::SELECT,
    'options'   => $this->get_acf_list(),
    'description' => esc_html__('Note : the field should return a image url', 'pgggo'),
    'condition'   => [
      'field_type'        => 'button',
      'button_field_type' => ['customfieldurlacfdonwload','customfieldurlacf'],
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'button_field_type_text',
  [
    'label'     => esc_html__('Button Text', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::TEXT,
    'default'   => esc_html__('Read More', 'pgggo'),
    'condition' => [
      'field_type' => 'button',
    ],
  ]
);
