<?php
$pgggo_repeatergrid->add_control(
  'pgggo_meta_to_load',
  [
    'label'   => __('Meta to Load', 'pgggo'),
    'type'    => \Elementor\Controls_Manager::SELECT,
    'default' => 'author',
    'options' => [
      'author'       => __('Author', 'pgggo'),
      'taxonomy'     => __('Taxonomy', 'pgggo'),
      'date'         => __('Date', 'pgggo'),
      'commentcount' => __('Comment Count', 'pgggo'),
    ],
    'condition' => [
      'field_type' => 'metadatasingle',
    ],
  ]
);


$pgggo_txon_array  = $this->pgggo_meta_data_retrievier_taxonomy();

$pgggo_repeatergrid->add_control(
  'pgggo_metadata_taxonmomny_array',
  [
    'label'     => __('Select Taxonomy', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::SELECT,
    'default'   => 'category',
    'options'   => $pgggo_txon_array,
    // 'condition' => ['pgggo_meta_to_load' => 'taxonomy'],
    'condition' => [
      'field_type' => 'metadatasingle',
      'pgggo_meta_to_load' => 'taxonomy',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_metadata_count',
  [
    'label'     => __('Number of terms to load', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::NUMBER,
    'default'   => 10,
    'condition' => [
      'field_type' => 'metadatasingle',
      'pgggo_meta_to_load' => 'taxonomy',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_metadata_load_links',
  [
    'label'        => __('Show As Links', 'pgggo'),
    'type'         => \Elementor\Controls_Manager::SWITCHER,
    'label_on'     => __('Yes', 'pgggo'),
    'label_off'    => __('No', 'pgggo'),
    'return_value' => 'yes',
    'default'      => 'no',
    'condition' => [
      'field_type' => 'metadatasingle',
      'pgggo_meta_to_load' => 'taxonomy',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_metadata_childless',
  [
    'label'        => __('Childless?', 'pgggo'),
    'type'         => \Elementor\Controls_Manager::SWITCHER,
    'label_on'     => __('Yes', 'pgggo'),
    'label_off'    => __('No', 'pgggo'),
    'return_value' => 'yes',
    'default'      => 'no',
    'condition' => [
      'field_type' => 'metadatasingle',
      'pgggo_meta_to_load' => 'taxonomy',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_metadata_hirarachical',
  [
    'label'        => __('Hierarchical?', 'pgggo'),
    'type'         => \Elementor\Controls_Manager::SWITCHER,
    'label_on'     => __('Yes', 'pgggo'),
    'label_off'    => __('No', 'pgggo'),
    'return_value' => 'yes',
    'default'      => 'yes',
    'condition' => [
      'field_type' => 'metadatasingle',
      'pgggo_meta_to_load' => 'taxonomy',
    ],
  ]
);
