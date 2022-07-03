<?php
class ControllerExtensionPaymentCcoffline extends Controller {
	public function index() {
		$this->load->language('extension/payment/ccoffline');

		$data['info'] = nl2br($this->config->get('payment_ccoffline_info' . $this->config->get('config_language_id')));

		$data['months'] = array();
		$data['months'][0]['text'] = "---Mes---";
		$data['months'][0]['value'] = "";

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_expire'] = array();

		$data['year_expire'][0]['text'] = "---Año---";
		$data['year_expire'][0]['value'] = "";

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		return $this->load->view('extension/payment/ccoffline', $data);
	}

	public function validate() {
		$json = array();
		$json['error'] = '';

		$error = array();
		//$error = '';

		if (!$this->request->post['cc_owner']) {
			//$this->error['cc_owner'] = $this->language->get('error_payable');
			$error['cc_owner'] = "Falta Nombre";
			//$error['cc_owner'] = "Falta Nombre";
		}
		if (!$this->request->post['cc_number']) {
			$error['cc_number'] = "Falta Numero";
		}
		if (!$this->request->post['cc_expire_date_month']) {
			$error['cc_expire_date_month'] = "Falta Numero";
		}
		if (!$this->request->post['cc_expire_date_year']) {
			$error['cc_expire_date_year'] = "Falta Numero";
		}
		if (!$this->request->post['cc_cvv2']) {
			$error['cc_cvv2'] = "Falta Numero";
		}
		//$json['error'] = $error;
		//$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput(json_encode($json));

		//PENDIENTE
		//if ($error) {
			return $error;
		//} else {
			//return $this->request->post;
		//}
		
	}

	public function confirm() {

		/***PENDIENTE***/
		$error = $this->validate();
		
			if (!$error) {
				
				$json = array();
				
				if (isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'ccoffline') {
					$this->load->language('extension/payment/ccoffline');

					$this->load->model('checkout/order');

					/***FALTA?***/
					$this->load->model('checkout/order');
			        $this->load->model('extension/payment/ccoffline');

			        //$order_id = $this->request->post['order_id'];
			        $order_id = $this->session->data['order_id'];
			        $order_info = $this->model_checkout_order->getOrder($order_id);
			        //$ext_order = $this->model_extension_payment_ccoffline->getOrder($order_id);
					/***/

					$comment  = $this->language->get('text_instruction') . "\n\n";
					$comment .= $this->config->get('payment_ccoffline_info' . $this->config->get('config_language_id')) . "\n\n";
					$comment .= $this->language->get('text_payment');
/***/
					//Agrego datos de tarjeta
					$ccoffline_order_data = array(
		                'order_id' => $order_info['order_id'],
		                'ccoffline_cc_owner' => $this->request->post['cc_owner'],
		                'ccoffline_cc_number' => $this->request->post['cc_number'],
		                'ccoffline_cc_expire_date_month' => $this->request->post['cc_expire_date_month'],
		                'ccoffline_cc_expire_date_year' => $this->request->post['cc_expire_date_year'],
		                'ccoffline_cc_cvv2' => $this->request->post['cc_cvv2']
		            );
					$this->model_extension_payment_ccoffline->addOrder($ccoffline_order_data);

					//Agrego historial
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_ccoffline_order_status_id'), $comment, true);
/***/				
					$json['redirect'] = $this->url->link('checkout/success');
				}
				
			} else {

				$json['error'] = $error;

			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
	}
}