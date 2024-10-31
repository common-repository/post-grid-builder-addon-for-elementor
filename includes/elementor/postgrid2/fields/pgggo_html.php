<?php
if (!defined('ABSPATH')) {
  exit;
}

$pgggo_repeatergrid->add_control(
  'field_html',
  [
    'label'     => esc_html__('Custom HTML', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::CODE,
    'language'  => 'html',
    'rows'      => 5,
    'condition' => [
      'field_type' => 'html',
    ],
  ]
);
