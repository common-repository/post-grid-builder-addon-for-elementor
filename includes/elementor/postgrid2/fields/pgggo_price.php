<?php
if (!defined('ABSPATH')) {
  exit;
}


//version 1.1.0 Update for content
// Allows text content to be placed befor content
$pgggo_repeatergrid->add_control(
  'pgggo_content_before_placer_variable',
  [
    'label' => esc_html__( 'Variable Product | Content Before', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::TEXTAREA,
    'rows' => 2,
    'placeholder' => esc_html__( 'Adds the content before field type', 'pgggo' ),
    'condition'    => [
      'field_type' => 'wooprice',
    ],
  ]
);

//version 1.0.1 Update for content
// Allows text content to placed after text
$pgggo_repeatergrid->add_control(
  'pgggo_content_after_placer_variable',
  [
    'label' => esc_html__( 'Variable Product | Content After', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::TEXTAREA,
    'rows' => 2,
    'placeholder' => esc_html__( 'Adds the content after field type', 'pgggo' ),
    'condition'    => [
      'field_type' => 'wooprice',
    ],
  ]
);
