<?php
if (!defined('ABSPATH')) {
    exit;
}

  $pgggo_initial = array();
  $pgggo_config = new PGGGO_CONFIG\pgggoConfig($pgggo_initial);

  //CONFIGURE BELOW
  $pgggo_config->pgggo_generate_plugin_name('Gridify for Elementor');
  $pgggo_config->pgggo_generate_plugin_metalinks("Follow", 'https://www.instagram.com/latheeshvmv/');

  $pgggo_metalinks = [
    [
      'title'=>'Donate',
      'type'=>'button', //or button
      'link'=>'https://paypal.me/latheeshvmv',
      'class'=>'pgggo-pro-note',
      'id'=>'pgggo-pro-note',
    ],
  ];

  // SEND
  $pgggo_config->pgggo_generate_plugin_actionlinks($pgggo_metalinks);
  $pgggo_data = $pgggo_config->pgggo_get_all_info();
