<?php
class ModelExtensionPaymentCcoffline extends Model {

	public function addOrder($data) {
    	$this->db->query("INSERT INTO `" . DB_PREFIX . "ccoffline_order` SET `order_id` = '" . (int)$data['order_id'] . 
    		 "', `ccoffline_cc_owner` = '" . $this->db->escape($data['ccoffline_cc_owner']) . 
    		 "', `ccoffline_cc_number` = '" . $this->db->escape($data['ccoffline_cc_number']) . 
    		 "', `ccoffline_cc_expire_date_month` = '" . $this->db->escape($data['ccoffline_cc_expire_date_month']) . 
    		 "', `ccoffline_cc_expire_date_year` = '" . $this->db->escape($data['ccoffline_cc_expire_date_year']) . 
    		 "', `ccoffline_cc_cvv2` = '" . $this->db->escape($data['ccoffline_cc_cvv2']) . 
    		 "'");
  	}


	public function getMethod($address, $total) {
		$this->load->language('extension/payment/ccoffline');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_ccoffline_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_ccoffline_total') > 0 && $this->config->get('payment_ccoffline_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_ccoffline_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'ccoffline',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_ccoffline_sort_order')
			);
		}

		return $method_data;
	}
}
