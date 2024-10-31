<?php
if (!defined('ABSPATH')) {
  exit;
}
$pgggo_repeatergrid->add_control(
  'pgggo_metaldata_show_date',
  [
      'label'        => esc_html__('Show Date', 'pgggo'),
      'type'         => \Elementor\Controls_Manager::SWITCHER,
      'label_on'     => esc_html__('Show', 'pgggo'),
      'label_off'    => esc_html__('Hide', 'pgggo'),
      'return_value' => 'yes',
      'default'      => 'yes',
      'condition'    => [
          'field_type' => 'metadata',
      ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_metaldata_show_tags',
  [
      'label'        => esc_html__('Show Tags', 'pgggo'),
      'type'         => \Elementor\Controls_Manager::SWITCHER,
      'label_on'     => esc_html__('Show', 'pgggo'),
      'label_off'    => esc_html__('Hide', 'pgggo'),
      'return_value' => 'yes',
      'default'      => 'yes',
      'condition'    => [
          'field_type' => 'metadata',
      ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_metaldata_show_taxonomy',
  [
      'label'        => esc_html__('Show Category/ Taxonomy', 'pgggo'),
      'type'         => \Elementor\Controls_Manager::SWITCHER,
      'label_on'     => esc_html__('Show', 'pgggo'),
      'label_off'    => esc_html__('Hide', 'pgggo'),
      'return_value' => 'yes',
      'default'      => 'yes',
      'condition'    => [
          'field_type' => 'metadata',
      ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_metaldata_show_author',
  [
      'label'        => esc_html__('Show Author', 'pgggo'),
      'type'         => \Elementor\Controls_Manager::SWITCHER,
      'label_on'     => esc_html__('Show', 'pgggo'),
      'label_off'    => esc_html__('Hide', 'pgggo'),
      'return_value' => 'yes',
      'default'      => 'yes',
      'condition'    => [
          'field_type' => 'metadata',
      ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_metaldata_show_comment',
  [
      'label'        => esc_html__('Show Comment', 'pgggo'),
      'type'         => \Elementor\Controls_Manager::SWITCHER,
      'label_on'     => esc_html__('Show', 'pgggo'),
      'label_off'    => esc_html__('Hide', 'pgggo'),
      'return_value' => 'yes',
      'default'      => 'yes',
      'condition'    => [
          'field_type' => 'metadata',
      ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_metaldata_select_taxonomy',
  [
      'label'     => esc_html__('Select Taxonomy', 'pgggo'),
      'type'      => \Elementor\Controls_Manager::SELECT,
      'default'   => 'category',
      'options'   => $pgggo_taxonomy_tax_mata,
      'condition' => [
          'field_type' => 'metadata',
      ],
  ]
);
