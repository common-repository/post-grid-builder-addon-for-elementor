<?php
if (!defined('ABSPATH')) {
  exit;
}
$pgggo_repeatergrid->add_control(
  'field_content_type',
  [
    'label'     => esc_html__('Content type', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::SELECT,
    'default'   => 'editor',
    'options'   => [
      'editor'          => esc_html__('Editor Content', 'pgggo'),
      'excerpt'         => esc_html__('Excerpt', 'pgggo'),
      'customfield_acf' => esc_html__('ACF Custom Field', 'pgggo'),
      'customfield'     => esc_html__('Custom Field', 'pgggo'),
    ],
    'condition' => [
      'field_type' => ['content'],
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'field_content_custom_value_acf',
  [
    'label'     => esc_html__('Custom field name', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::TEXT,
    'condition' => [
      'field_type'         => ['content'],
      'field_content_type' => ['customfield'],
    ],
  ]
);


$pgggo_repeatergrid->add_control(
  'field_content_type_acf',
  [
    'label'     => esc_html__('ACF Field Selector', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::SELECT,
    'options'   => $this->get_acf_list(),
    'condition' => [
      'field_type'         => ['content'],
      'field_content_type' => ['customfield_acf'],
    ],
  ]
);


$pgggo_repeatergrid->add_control(
  'trim_content',
  [
    'label'        => esc_html__('Enable Excerpt mode', 'pgggo'),
    'type'         => \Elementor\Controls_Manager::SWITCHER,
    'label_on'     => esc_html__('Enable', 'pgggo'),
    'label_off'    => esc_html__('Disbale', 'pgggo'),
    'return_value' => 'yes',
    'default'      => 'yes',
    'condition'    => [
      'field_type' => ['content'],
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'excerpt_length',
  [
    'label'     => esc_html__('Word Count', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::NUMBER,
    'default'   => 30,
    'condition' => [
      'field_type' => ['content'],
    ],
  ]
);
