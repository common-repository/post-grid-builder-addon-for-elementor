<?php
if (!defined('ABSPATH')) {
  exit;
}

$pgggo_repeatergrid->start_controls_tabs(
  'coretab'
);

$pgggo_repeatergrid->start_controls_tab(
  'corestyling',
  [
    'label' => esc_html__( 'Styling', 'pgggo' ),
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_color',
  [
    'label'     => esc_html__('Color', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::COLOR,
    'default'   => '#54595f',
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'color: {{VALUE}}',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_color_link',
  [
    'label'     => esc_html__('Link Color', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::COLOR,
    'default'   => '#13682e',
    'condition' => [
      'field_type'=> ['metadata','button','metadatasingle'],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner a' => 'color: {{VALUE}}',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_background_color',
  [
    'label'     => esc_html__('Background Color', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::COLOR,
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'background-color: {{VALUE}}',
    ],
  ]
);

$pgggo_repeatergrid->add_responsive_control(
  'pgggo_repeater_width',
  [
    'label' => esc_html__( 'Width', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SLIDER,
    'size_units' => [ 'px', '%' ],
    'range' => [
      'px' => [
        'min' => 0,
        'max' => 1000,
        'step' => 5,
      ],
      '%' => [
        'min' => 0,
        'max' => 100,
      ],
    ],
    'default' => [
      'unit' => '%',
      'size' => 100,
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'width: {{SIZE}}{{UNIT}};',
    ],
  ]
);

$pgggo_repeatergrid->add_responsive_control(
  'pgggo_repeater_min_height',
  [
    'label' => esc_html__( 'Min Height', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SLIDER,
    'size_units' => ['px'],
    'range' => [
      'px' => [
        'min' => 0,
        'max' => 1000,
        'step' => 1,
      ],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'min-height: {{SIZE}}{{UNIT}};',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_background_z_index',
  [
    'label' => esc_html__( 'z-index', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::NUMBER,
    'default' => 1,
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'z-index: {{VALUE}}',
    ],
  ]
);

$pgggo_repeatergrid->add_responsive_control(
  'pgggo_repeater_background_position',
  [
    'label' => esc_html__( 'Position', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SELECT,
    'default' => 'relative',
    'options' => [
      'relative'  => esc_html__( 'Relative', 'pgggo' ),
      'absolute' => esc_html__( 'Absolute', 'pgggo' ),
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'position: {{VALUE}}',
    ],
  ]
);

$pgggo_repeatergrid->add_responsive_control(
  'pgggo_repeater_background_postion_top',
  [
    'label' => esc_html__( 'Top', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SLIDER,
    'size_units' => [ 'px', '%' ],
    'range' => [
      'px' => [
        'min' => 0,
        'max' => 1000,
        'step' => 5,
      ],
      '%' => [
        'min' => 0,
        'max' => 100,
      ],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'top: {{SIZE}}{{UNIT}};',
    ],
    'condition' => [
      'pgggo_repeater_background_position'=> 'absolute',
    ],
  ]
);
$pgggo_repeatergrid->add_responsive_control(
  'pgggo_repeater_background_postion_left',
  [
    'label' => esc_html__( 'Left', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SLIDER,
    'size_units' => [ 'px', '%' ],
    'range' => [
      'px' => [
        'min' => 0,
        'max' => 1000,
        'step' => 5,
      ],
      '%' => [
        'min' => 0,
        'max' => 100,
      ],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'left: {{SIZE}}{{UNIT}};',
    ],
    'condition' => [
      'pgggo_repeater_background_position'=> 'absolute',
    ],
  ]
);

$pgggo_repeatergrid->add_responsive_control(
  'pgggo_repeater_background_postion_bottom',
  [
    'label' => esc_html__( 'Bottom', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SLIDER,
    'size_units' => [ 'px', '%' ],
    'range' => [
      'px' => [
        'min' => 0,
        'max' => 1000,
        'step' => 5,
      ],
      '%' => [
        'min' => 0,
        'max' => 100,
      ],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'bottom: {{SIZE}}{{UNIT}};',
    ],
    'condition' => [
      'pgggo_repeater_background_position'=> 'absolute',
    ],
  ]
);
$pgggo_repeatergrid->add_responsive_control(
  'pgggo_repeater_background_postion_right',
  [
    'label' => esc_html__( 'Right', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SLIDER,
    'size_units' => [ 'px', '%' ],
    'range' => [
      'px' => [
        'min' => 0,
        'max' => 1000,
        'step' => 5,
      ],
      '%' => [
        'min' => 0,
        'max' => 100,
      ],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'right: {{SIZE}}{{UNIT}};',
    ],
    'condition' => [
      'pgggo_repeater_background_position'=> 'absolute',
    ],
  ]
);



$pgggo_repeatergrid->add_responsive_control(
  'pgggo_repeater_background_margin',
  [
    'label'      => esc_html__('Margin', 'pgggo'),
    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
    'size_units' => ['px', '%', 'em'],
    'selectors'  => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    ],
  ]
);

$pgggo_repeatergrid->add_responsive_control(
  'pgggo_repeater_background_padding',
  [
    'label'      => esc_html__('Padding', 'pgggo'),
    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
    'size_units' => ['px', '%', 'em'],
    'selectors'  => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_color_border',
  [
    'label'     => esc_html__('Border Color', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::COLOR,
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'border-color: {{VALUE}}',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_border_size',
  [
    'label' => esc_html__( 'Border Style', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SELECT,
    'default' => 'solid',
    'options' => [
      'solid'  => esc_html__( 'Solid', 'pgggo' ),
      'dotted' => esc_html__( 'Dotted', 'pgggo' ),
      'dashed' => esc_html__( 'Dashed', 'pgggo' ),
      'double' => esc_html__( 'Double', 'pgggo' ),
      'groove' => esc_html__( 'Groove', 'pgggo' ),
      'ridge' => esc_html__( 'Ridge', 'pgggo' ),
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'border-style: {{VALUE}}',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_background_border_size',
  [
    'label'      => esc_html__('Border Size', 'pgggo'),
    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
    'size_units' => ['px', '%', 'em'],
    'default'    => [
      'top'      => '0',
      'right'    => '0',
      'bottom'   => '0',
      'left'     => '0',
      'isLinked' => true,
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_background_border_radius',
  [
    'label'      => esc_html__('Border Radius', 'pgggo'),
    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
    'size_units' => ['px', '%', 'em'],
    'default'    => [
      'top'      => '0',
      'right'    => '0',
      'bottom'   => '0',
      'left'     => '0',
      'isLinked' => true,
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
    ],
  ]
);



$pgggo_repeatergrid->add_control(
  'pgggo_repeater_font_family_notice_img',
  [
    'label' => esc_html__( 'Note', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::RAW_HTML,
    'raw' => esc_html__( 'Use controls below to modify the border parameters of image', 'pgggo' ),
    'content_classes' => 'your-class',
    'condition' => [
      'field_type'=> ['image'],
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_color_border_img',
  [
    'label'     => esc_html__('Border Color', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::COLOR,
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner img' => 'border-color: {{VALUE}}',
    ],
    'condition' => [
      'field_type'=> ['image'],
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_border_size_img',
  [
    'label' => esc_html__( 'Border Style', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SELECT,
    'default' => 'solid',
    'options' => [
      'solid'  => esc_html__( 'Solid', 'pgggo' ),
      'dotted' => esc_html__( 'Dotted', 'pgggo' ),
      'dashed' => esc_html__( 'Dashed', 'pgggo' ),
      'double' => esc_html__( 'Double', 'pgggo' ),
      'groove' => esc_html__( 'Groove', 'pgggo' ),
      'ridge' => esc_html__( 'Ridge', 'pgggo' ),
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner img' => 'border-style: {{VALUE}}',
    ],
    'condition' => [
      'field_type'=> ['image'],
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_background_border_size_img',
  [
    'label'      => esc_html__('Border Size', 'pgggo'),
    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
    'size_units' => ['px', '%', 'em'],
    'default'    => [
      'top'      => '0',
      'right'    => '0',
      'bottom'   => '0',
      'left'     => '0',
      'isLinked' => true,
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
    ],
    'condition' => [
      'field_type'=> ['image'],
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_repeater_background_border_radius_img',
  [
    'label'      => esc_html__('Border Radius', 'pgggo'),
    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
    'size_units' => ['px', '%', 'em'],
    'default'    => [
      'top'      => '0',
      'right'    => '0',
      'bottom'   => '0',
      'left'     => '0',
      'isLinked' => true,
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-repeater-container-inner img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
    ],
    'condition' => [
      'field_type'=> ['image'],
    ],
  ]
);


$pgggo_repeatergrid->add_control(
  'pgggo_repeater_alignment',
  [
    'label'   => esc_html__('Text Alignment', 'pgggo'),
    'type'    => \Elementor\Controls_Manager::CHOOSE,
    'options' => [
      'left'   => [
        'title' => esc_html__('Left', 'pgggo'),
        'icon'  => 'fa fa-align-left',
      ],
      'center' => [
        'title' => esc_html__('Center', 'pgggo'),
        'icon'  => 'fa fa-align-center',
      ],
      'right'  => [
        'title' => esc_html__('Right', 'pgggo'),
        'icon'  => 'fa fa-align-right',
      ],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
    ],
    'default' => 'center',
    'toggle'  => true,
  ]
);



$pgggo_repeatergrid->add_control(
  'pgggo_repeater_alignment_content',
  [
    'label'   => esc_html__('Content Alignment', 'pgggo'),
    'type'    => \Elementor\Controls_Manager::CHOOSE,
    'options' => [
      'left'   => [
        'flex-start' => esc_html__('Left', 'pgggo'),
        'icon'  => 'fa fa-align-left',
      ],
      'center' => [
        'title' => esc_html__('Center', 'pgggo'),
        'icon'  => 'fa fa-align-center',
      ],
      'flex-end'  => [
        'title' => esc_html__('Right', 'pgggo'),
        'icon'  => 'fa fa-align-right',
      ],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'justify-content: {{VALUE}}',
      '{{WRAPPER}} {{CURRENT_ITEM}} .pgggo-containter-image-hold' => 'justify-content: {{VALUE}}',
    ],
    'default' => 'center',
    'toggle'  => true,
  ]
);

$pgggo_repeatergrid->end_controls_tab();

$pgggo_repeatergrid->start_controls_tab(
  'core_typo',
  [
    'label' => esc_html__( 'Typography', 'pgggo' ),
  ]
);


$pgggo_repeatergrid->add_control(
  'pgggo_repeater_more_options',
  [
    'label' => esc_html__( 'Typography', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::HEADING,
    'separator' => 'after',
  ]
);



$pgggo_repeatergrid->add_control(
  'pgggo_repeater_font_family',
  [
    'label' => esc_html__( 'Font Family', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::FONT,
    'default' => "'Open Sans', sans-serif",
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'font-family: {{VALUE}}',
    ],
  ]
);


$pgggo_repeatergrid->add_responsive_control(
  'pgggo_font_size',
  [
    'label' => esc_html_x( 'Size', 'Typography Control', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SLIDER,
    'size_units' => [ 'px', 'em', 'rem', 'vw' ],
    'range' => [
      'px' => [
        'min' => 1,
        'max' => 200,
      ],
      'vw' => [
        'min' => 0.1,
        'max' => 10,
        'step' => 0.1,
      ],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'font-size: {{SIZE}}{{UNIT}}',
    ],
  ]
);

foreach ( array_merge( [ 'normal', 'bold' ], range( 100, 900, 100 ) ) as $weight ) {
  $typo_weight_options[ $weight ] = ucfirst( $weight );
}

$pgggo_repeatergrid->add_control(
  'pgggo_font_weight',
  [
    'label' => esc_html_x( 'Weight', 'Typography Control', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SELECT,
    'default' => '',
    'options' => $typo_weight_options,
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'font-weight: {{VALUE}}',
    ],
  ]
);


$pgggo_repeatergrid->add_control(
  'pgggo_font_transform',
  [
    'label' => esc_html_x( 'Transform', 'Typography Control', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SELECT,
    'default' => '',
    'options' => [
      '' => esc_html__( 'Default', 'pgggo' ),
      'uppercase' => esc_html_x( 'Uppercase', 'Typography Control', 'pgggo' ),
      'lowercase' => esc_html_x( 'Lowercase', 'Typography Control', 'pgggo' ),
      'capitalize' => esc_html_x( 'Capitalize', 'Typography Control', 'pgggo' ),
      'none' => esc_html_x( 'Normal', 'Typography Control', 'pgggo' ),
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-transform: {{VALUE}}',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
  'pgggo_font_style',
  [
    'label' => esc_html_x( 'Style', 'Typography Control', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SELECT,
    'default' => '',
    'options' => [
      '' => esc_html__( 'Default', 'pgggo' ),
      'normal' => esc_html_x( 'Normal', 'Typography Control', 'pgggo' ),
      'italic' => esc_html_x( 'Italic', 'Typography Control', 'pgggo' ),
      'oblique' => esc_html_x( 'Oblique', 'Typography Control', 'pgggo' ),
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'font-style: {{VALUE}}',
    ],
  ]
);


$pgggo_repeatergrid->add_control(
  'pgggo_font_decoration',
  [
    'label' => esc_html_x( 'Decoration', 'Typography Control', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SELECT,
    'default' => 'None',
    'options' => [
      '' => esc_html__( 'Default', 'pgggo' ),
      'underline' => esc_html_x( 'Underline', 'Typography Control', 'pgggo' ),
      'overline' => esc_html_x( 'Overline', 'Typography Control', 'pgggo' ),
      'line-through' => esc_html_x( 'Line Through', 'Typography Control', 'pgggo' ),
      'none' => esc_html_x( 'None', 'Typography Control', 'pgggo' ),
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-decoration: {{VALUE}}',
    ],
  ]
);

$pgggo_repeatergrid->add_responsive_control(
  'pgggo_font_lineheight',
  [
    'label' => esc_html_x( 'Line-Height', 'Typography Control', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SLIDER,
    'desktop_default' => [
      'unit' => 'em',
    ],
    'tablet_default' => [
      'unit' => 'em',
    ],
    'mobile_default' => [
      'unit' => 'em',
    ],
    'range' => [
      'px' => [
        'min' => 1,
      ],
    ],
    'size_units' => [ 'px', 'em' ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'line-height: {{SIZE}}{{UNIT}}',
    ],
  ]
);


$pgggo_repeatergrid->add_responsive_control(
  'pgggo_font_letterspacing',
  [
    'label' => esc_html_x( 'Letter Spacing', 'Typography Control', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::SLIDER,
    'range' => [
      'px' => [
        'min' => -5,
        'max' => 10,
        'step' => 0.1,
      ],
    ],
    'selectors' => [
      '{{WRAPPER}} {{CURRENT_ITEM}}' => 'letter-spacing: {{SIZE}}{{UNIT}}',
    ],
  ]
);


$pgggo_repeatergrid->end_controls_tab();

$pgggo_repeatergrid->start_controls_tab(
  'core_anim',
  [
    'label' => esc_html__( 'Class/ID', 'pgggo' ),
  ]
);




$pgggo_repeatergrid->add_control(
  'more_options_anim',
  [
    'label' => esc_html__( 'Set unique class and id for each repeater', 'pgggo' ),
    'type' => \Elementor\Controls_Manager::HEADING,
    'separator' => 'before',
  ]
);

$pgggo_repeatergrid->add_control(
  'field_class',
  [
    'label'   => esc_html__('Class', 'pgggo'),
    'type'    => \Elementor\Controls_Manager::TEXT,
    'default' => esc_html__('', 'pgggo'),
  ]
);

$pgggo_repeatergrid->add_control(
  'field_id',
  [
    'label'   => esc_html__('ID', 'pgggo'),
    'type'    => \Elementor\Controls_Manager::TEXT,
    'default' => esc_html__('', 'pgggo'),
  ]
);
$pgggo_repeatergrid->end_controls_tab();
$pgggo_repeatergrid->end_controls_tabs();
