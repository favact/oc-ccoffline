<?php

class ModelExtensionPaymentCcoffline extends Model {
  public function install() {
    $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ccoffline_order` (
        `ccoffline_order_id` INT(11) NOT NULL AUTO_INCREMENT,
        `order_id` INT(11) NOT NULL,
        `ccoffline_cc_owner` VARCHAR(120) NOT NULL,
        `ccoffline_cc_number` VARCHAR(100) NOT NULL,
        `ccoffline_cc_expire_date_month` VARCHAR(100) NOT NULL,
        `ccoffline_cc_expire_date_year` VARCHAR(100) NOT NULL,
        `ccoffline_cc_cvv2` VARCHAR(100) NOT NULL,
        PRIMARY KEY (`ccoffline_order_id`)
      ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;
    ");

    $this->load->model('setting/setting');

    $defaults = array();

    $defaults['payment_ccoffline_order_status_id'] = 1;
    $defaults['payment_ccoffline_status'] = 1;

    $this->model_setting_setting->editSetting('payment_ccoffline', $defaults);

//    this->load->model('setting/setting');
//    $this->model_setting_setting->editSetting('payment_ccoffline', ['payment_ccoffline_status' => 1]);
  }

  public function uninstall() {

    $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ccoffline_order`;");

    $this->load->model('setting/setting');
    $this->model_setting_setting->deleteSetting('payment_ccoffline');
  }
}
