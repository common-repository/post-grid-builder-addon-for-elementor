<?php
namespace PGGGO_CONFIG {

  if (!defined('ABSPATH')) {
      exit;
  }

  class pgggoConfig
  {
      public $pgggo_data_conf;

      public function __construct($pgggo_data_conf)
      {
          $this->plugin_config = $pgggo_data_conf;
      }

      public function __debugInfo()
      {
          return [
            'data' => $this->plugin_config,
        ];
      }

      private function pgggo_data_metalinks($title, $link)
      {
          return '<a target="_blank" href="'.$link.'" title="' . esc_html__("$title", "pgggo") . '">' . esc_html__("$title", "pgggo") . '</a>';
      }

      public function pgggo_generate_plugin_metalinks($title, $link)
      {
          $this->plugin_config['metalinks'][]= $this->pgggo_data_metalinks($title, $link);
      }

      public function pgggo_generate_plugin_name($plugin_name)
      {
          $this->plugin_config['plugin_name']= $plugin_name;
      }

      public function pgggo_data_action_link($datas)
      {
          $info = array();
          foreach ($datas as $key => $data) {
              if ($data['type'] == 'a') {
                  $info[] = '<a class="elementor-plugins-gopro '.$data['class'].'" id="'.$data['id'].'" target="_blank" href="'.$data['link'].'">'.$data['title'].'</a>';
              } else {
                  $info[] = '<button class="elementor-plugins-gopro '.$data['class'].'" id="'.$data['id'].'" >' . $data['title'] . '</button>';
              }
          }
          return $info;
      }

      public function pgggo_generate_plugin_actionlinks($datas)
      {
          $this->plugin_config['actionlinks']= $this->pgggo_data_action_link($datas);
      }

      public function pgggo_get_all_info(){
        return $this->plugin_config;
      }
  }

}
