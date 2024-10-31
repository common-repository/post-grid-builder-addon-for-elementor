<?php
namespace PGGGO_SPECS {

  if (!defined('ABSPATH')) {
      exit;
  }

  class pgggoSpecs
  {
      public $data;

      public function __construct($data)
      {
          $this->data = $data;
          add_filter('plugin_action_links_pgggo/elementor-post-grid-by-geeky-green-owl.php', array($this,'pgggo_action_link'));
          add_filter('plugin_row_meta', array($this,'pgggo_plugin_meta_links'), 10, 3);
      }


      public function pgggo_action_link($links)
      {
          $data = $this->data;
          if (!empty($data)) {
              foreach ($data['actionlinks'] as $key => $value) {
                  $links[] = $value;
              }
              return $links;
          } else {
              return $links;
          }
      }

      public function pgggo_plugin_meta_links($plugin_meta, $plugin_file, $plugin_data)
      {
          $data = $this->data;
          if ($plugin_data['Name'] === $data['plugin_name'] && !empty($data)) {
              foreach ($data['metalinks'] as $key => $value) {
                  $plugin_meta[] = $value;
              }
              return $plugin_meta;
          } else {
              return $plugin_meta;
          }
      }
  }

  }
