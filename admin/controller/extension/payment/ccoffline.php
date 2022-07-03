<?php
	class ControllerExtensionPaymentCcoffline extends Controller
	{

		private $error = array();


		public function index() 
		{
			$this->load->language('extension/payment/ccoffline');
			$this->document->setTitle($this->language->get('heading_title'));
			$this->load->model('setting/setting');
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				
				$this->model_setting_setting->editSetting('payment_ccoffline', $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');

				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
			}

			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->error['info'])) {
				$data['error_info'] = $this->error['info'];
			} else {
				$data['error_info'] = '';
			}

			$data['breadcrumbs'] = array();
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_extension'),
				'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
			
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/payment/ccoffline', 'user_token=' . $this->session->data['user_token'], true)
			);

			$data['action'] = $this->url->link('extension/payment/ccoffline', 'user_token=' . $this->session->data['user_token'], true);
			

			$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
			
			$this->load->model('localisation/language');

			$data['payment_ccoffline_info'] = array();

			$languages = $this->model_localisation_language->getLanguages();
			
			foreach ($languages as $language) {
				if (isset($this->request->post['payment_ccoffline_info' . $language['language_id']])) {
					$data['payment_ccoffline_info'][$language['language_id']] = $this->request->post['payment_ccoffline_info' . $language['language_id']];
				} else {
					$data['payment_ccoffline_info'][$language['language_id']] = $this->config->get('payment_ccoffline_info' . $language['language_id']);
				}
			}

			$data['languages'] = $languages;

			if (isset($this->request->post['payment_ccoffline_total'])) {
				$data['payment_ccoffline_total'] = $this->request->post['payment_ccoffline_total'];
			} else {
				$data['payment_ccoffline_total'] = $this->config->get('payment_ccoffline_total');
			}

			if (isset($this->request->post['payment_ccoffline_order_status_id'])) {
				$data['payment_ccoffline_order_status_id'] = $this->request->post['payment_ccoffline_order_status_id'];
			} else {
				$data['payment_ccoffline_order_status_id'] = $this->config->get('payment_ccoffline_order_status_id');
			}

			$this->load->model('localisation/order_status');

			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			if (isset($this->request->post['payment_ccoffline_geo_zone_id'])) {
				$data['payment_ccoffline_geo_zone_id'] = $this->request->post['payment_ccoffline_geo_zone_id'];
			} else {
				$data['payment_ccoffline_geo_zone_id'] = $this->config->get('payment_ccoffline_geo_zone_id');
			}

			$this->load->model('localisation/geo_zone');

			$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

			if (isset($this->request->post['payment_ccoffline_status'])) {
				$data['payment_ccoffline_status'] = $this->request->post['payment_ccoffline_status'];
			} else {
				$data['payment_ccoffline_status'] = $this->config->get('payment_ccoffline_status');
			}

			if (isset($this->request->post['payment_ccoffline_sort_order'])) {
				$data['payment_ccoffline_sort_order'] = $this->request->post['payment_ccoffline_sort_order'];
			} else {
				$data['payment_ccoffline_sort_order'] = $this->config->get('payment_ccoffline_sort_order');
			}

			$this->load->model('localisation/language');
			$data['languages'] = $this->model_localisation_language->getLanguages();

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('extension/payment/ccoffline', $data));

		}

		protected function validate()
		{

			if (!$this->user->hasPermission('modify', 'extension/payment/ccoffline')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}

			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				if (empty($this->request->post['payment_ccoffline_info' . $language['language_id']])) {
					$this->error['bank'][$language['language_id']] = $this->language->get('error_bank');
				}
			}

			return !$this->error;

		}

		public function install()
		{
			$this->load->model('extension/payment/ccoffline');
			$this->model_extension_payment_ccoffline->install();

		}

		public function uninstall()
		{
			$this->load->model('extension/payment/ccoffline');
			$this->model_extension_payment_ccoffline->uninstall();

		}
		
	}