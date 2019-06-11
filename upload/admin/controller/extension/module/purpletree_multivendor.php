<?php
class ControllerExtensionModulePurpletreeMultivendor extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/purpletree_multivendor');

		$this->document->setTitle($this->language->get('heading_title'));
		$data['version'] = "Version 3.13.5";
		$this->load->model('setting/setting');
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = array();
		$data['order_statuses1'] = $this->model_localisation_order_status->getOrderStatuses();
		foreach($data['order_statuses1'] as $ordersstatus) {
			if($ordersstatus['name'] != 'Canceled' && $ordersstatus['name'] != 'Canceled Reversal' &&  $ordersstatus['name'] != 'Chargeback' && $ordersstatus['name'] != 'Denied' && $ordersstatus['name'] != 'Expired' && $ordersstatus['name'] != 'Failed' && $ordersstatus['name'] != 'Refunded' && $ordersstatus['name'] != 'Reversed' && $ordersstatus['name'] != 'Voided' ) {
				 $data['order_statuses'][] = array(
					'order_status_id' => $ordersstatus['order_status_id'],
					'name' => $ordersstatus['name']
				); 
			}
		}
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if( !$this->config->get('module_purpletree_multivendor_status')){ 
				$this->model_setting_setting->editSetting('module_purpletree_multivendor', $this->request->post);
			} else {
				$this->model_setting_setting->editSetting('module_purpletree_multivendor', $this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');
			}
			$this->response->redirect($this->url->link('extension/module/purpletree_multivendor', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['vendor_heading'] = $this->language->get('vendor_heading');
		$data['order_heading'] = $this->language->get('order_heading');
		$data['seller_product_heading'] = $this->language->get('seller_product_heading');
		$data['seller_review_heading'] = $this->language->get('seller_review_heading');
		$data['seller_email_heading'] = $this->language->get('seller_email_heading');
		$data['seller_store_heading'] = $this->language->get('seller_store_heading');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_allowed_categories'] = $this->language->get('text_allowed_categories');
		$data['text_selected_categories'] = $this->language->get('text_selected_categories');
		$data['text_assign_categories'] = $this->language->get('text_assign_categories');
		$data['text_store_email'] = $this->language->get('text_store_email');
		$data['text_store_phone'] = $this->language->get('text_store_phone');
		$data['text_store_address'] = $this->language->get('text_store_address');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['please_select'] = $this->language->get('please_select');
		$data['entry_seller_manage_order'] = $this->language->get('entry_seller_manage_order');
		$data['entry_seller_approval'] = $this->language->get('entry_seller_approval');
		$data['entry_product_approval'] = $this->language->get('entry_product_approval');
		$data['entry_product_edit_approval'] = $this->language->get('entry_product_edit_approval');
		$data['entry_allow_category'] = $this->language->get('entry_allow_category');
		$data['entry_become_seller'] = $this->language->get('entry_become_seller');
		$data['entry_order_approval'] = $this->language->get('entry_order_approval');
		$data['entry_limit_purchase'] = $this->language->get('entry_limit_purchase');
		$data['entry_allow_metals'] = $this->language->get('entry_allow_metals');
		$data['entry_seller_review'] = $this->language->get('entry_seller_review');
		$data['entry_seller_store'] = $this->language->get('entry_seller_store');
		$data['entry_seller_invoice'] = $this->language->get('entry_seller_invoice');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_email_id'] = $this->language->get('entry_email_id');
		$data['allow_browse_sellers'] = $this->language->get('allow_browse_sellers');
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_commission_status'] = $this->language->get('entry_commission_status');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_submit'] = $this->language->get('button_submit');
		$data['error_order_id'] = $this->language->get('error_order_id');
		$data['error_email_id'] = $this->language->get('error_email_id');
		$data['please_wait'] = $this->language->get('please_wait');
		$data['text_seller_logedin'] = $this->language->get('text_seller_logedin');
		$data['text_seller_general'] = $this->language->get('text_seller_general');
		$data['entry_seller_contact'] = $this->language->get('entry_seller_contact');
		$data['seller_contact_heading'] = $this->language->get('seller_contact_heading');
		$data['button_ok'] = $this->language->get('button_ok');
		$data['dont_have_lisence_key'] = $this->language->get('dont_have_lisence_key');
		$data['paypal_hosted_button_id'] = $this->language->get('paypal_hosted_button_id');
        ///////category for single product///////
		$data['entry_seller_product_category'] = $this->language->get('entry_seller_product_category');
		$data['text_single'] = $this->language->get('text_single');
        $data['text_multiple'] = $this->language->get('text_multiple');
		/////////////////////////////

		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} elseif(isset($this->session->data['warning'])){ 
			$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}
		
		if(isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->error['commission_status'])) {
			$data['commission_status_error'] = $this->error['commission_status'];
		}
			if (isset($this->error['commission'])) {
			$data['commission_error'] = $this->error['commission'];
		} 
		
		
		if (isset($this->error['product_limit'])) {
			$data['product_limit_error'] = $this->error['product_limit'];
		} 
		
		if (isset($this->error['process_data'])) {
			$data['error_warning'] = $this->error['process_data'];
		} 

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/purpletree_multivendor', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/purpletree_multivendor', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		if (isset($this->request->post['module_purpletree_multivendor_commission_status'])) {
			$data['module_purpletree_multivendor_commission_status'] = $this->request->post['module_purpletree_multivendor_commission_status'];
		} else {
			$data['module_purpletree_multivendor_commission_status'] = $this->config->get('module_purpletree_multivendor_commission_status');
		}
		if (isset($this->request->post['module_purpletree_multivendor_commission'])) {
			$data['module_purpletree_multivendor_commission'] = $this->request->post['module_purpletree_multivendor_commission'];
		} else {
			$data['module_purpletree_multivendor_commission'] = $this->config->get('module_purpletree_multivendor_commission');
		}
		if (isset($this->request->post['module_purpletree_multivendor_status'])) {
			$data['module_purpletree_multivendor_status'] = $this->request->post['module_purpletree_multivendor_status'];
		} else {
			$data['module_purpletree_multivendor_status'] = $this->config->get('module_purpletree_multivendor_status');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_process_data'])) {
			$data['module_purpletree_multivendor_process_data'] = $this->request->post['module_purpletree_multivendor_process_data'];
		} else {
			$data['module_purpletree_multivendor_process_data'] = $this->config->get('module_purpletree_multivendor_process_data');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_validate_text'])) {
			$data['module_purpletree_multivendor_validate_text'] = 1;
		} else {
			$data['module_purpletree_multivendor_validate_text'] = $this->config->get('module_purpletree_multivendor_validate_text');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_live_validate_text'])) {
			$data['module_purpletree_multivendor_live_validate_text'] = 0;
		} else {
			$data['module_purpletree_multivendor_live_validate_text'] = $this->config->get('module_purpletree_multivendor_live_validate_text');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_encypt_text'])) {
			$str = 'qtriangle.in';
			$data['module_purpletree_multivendor_encypt_text'] = md5($str);
		} else {
			$data['module_purpletree_multivendor_encypt_text'] = $this->config->get('module_purpletree_multivendor_encypt_text');
		}

		if (isset($this->request->post['module_purpletree_multivendor_seller_manage_order'])) {
			$data['module_purpletree_multivendor_seller_manage_order'] = $this->request->post['module_purpletree_multivendor_seller_manage_order'];
		} else {
			$data['module_purpletree_multivendor_seller_manage_order'] = $this->config->get('module_purpletree_multivendor_seller_manage_order');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_seller_approval'])) {
			$data['module_purpletree_multivendor_seller_approval'] = $this->request->post['module_purpletree_multivendor_seller_approval'];
		} else {
			$data['module_purpletree_multivendor_seller_approval'] = $this->config->get('module_purpletree_multivendor_seller_approval');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_product_approval'])) {
			$data['module_purpletree_multivendor_product_approval'] = $this->request->post['module_purpletree_multivendor_product_approval'];
		} else {
			$data['module_purpletree_multivendor_product_approval'] = $this->config->get('module_purpletree_multivendor_product_approval');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_allow_categorytype'])) {
			$data['module_purpletree_multivendor_allow_categorytype'] = $this->request->post['module_purpletree_multivendor_allow_categorytype'];
		} else {
			$data['module_purpletree_multivendor_allow_categorytype'] = $this->config->get('module_purpletree_multivendor_allow_categorytype');
		}
		
		$data['module_purpletree_multivendor_allow_category1'] = array();
	
		if (isset($this->request->post['module_purpletree_multivendor_allow_category'])) {
			$data['module_purpletree_multivendor_allow_category'] = $this->request->post['module_purpletree_multivendor_allow_category'];
			$data['module_purpletree_multivendor_allow_category1'] = $this->request->post['module_purpletree_multivendor_allow_category'];
		} elseif($this->config->get('module_purpletree_multivendor_allow_category')) {
			$data['module_purpletree_multivendor_allow_category'] = $this->config->get('module_purpletree_multivendor_allow_category');
			$data['module_purpletree_multivendor_allow_category1'] = $this->config->get('module_purpletree_multivendor_allow_category');
		} else {
			$data['module_purpletree_multivendor_allow_category'] = array();
			$data['module_purpletree_multivendor_allow_category1'] = array();
		}

			$data['module_purpletree_multivendor_allow_category'] = array();
			$this->load->model('catalog/category');
			$results = $this->model_catalog_category->getCategories();
			foreach ($results as $result) {
				if($data['module_purpletree_multivendor_allow_categorytype']) {
				
					$data['module_purpletree_multivendor_allow_category'][strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))] = $result['category_id'];
				} else {
					if(in_array($result['category_id'],$data['module_purpletree_multivendor_allow_category1'])) {
					$data['module_purpletree_multivendor_allow_category'][strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))] = $result['category_id'];
					}
				}
			}
		
		
		//////category for single product///////
		if (isset($this->request->post['module_purpletree_multivendor_seller_product_category'])) {
			$data['module_purpletree_multivendor_seller_product_category'] = $this->request->post['module_purpletree_multivendor_seller_product_category'];
		} else {
			$data['module_purpletree_multivendor_seller_product_category'] = $this->config->get('module_purpletree_multivendor_seller_product_category');
		}
		////////////////////////////////////////
		
		if (isset($this->request->post['module_purpletree_multivendor_become_seller'])) {
			$data['module_purpletree_multivendor_become_seller'] = $this->request->post['module_purpletree_multivendor_become_seller'];
		} else {
			$data['module_purpletree_multivendor_become_seller'] = $this->config->get('module_purpletree_multivendor_become_seller');
		}
		

		
		if (isset($this->request->post['module_purpletree_multivendor_product_limit'])) {
			$data['module_purpletree_multivendor_product_limit'] = $this->request->post['module_purpletree_multivendor_product_limit'];
		} else {
			$data['module_purpletree_multivendor_product_limit'] = $this->config->get('module_purpletree_multivendor_product_limit');
		}
			if (isset($this->request->post['module_purpletree_multivendor_allow_metals_product'])) {
			$data['module_purpletree_multivendor_allow_metals_product'] = $this->request->post['module_purpletree_multivendor_allow_metals_product'];
		} else {
			$data['module_purpletree_multivendor_allow_metals_product'] = $this->config->get('module_purpletree_multivendor_allow_metals_product');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_seller_review'])) {
			$data['module_purpletree_multivendor_seller_review'] = $this->request->post['module_purpletree_multivendor_seller_review'];
		} else {
			$data['module_purpletree_multivendor_seller_review'] = $this->config->get('module_purpletree_multivendor_seller_review');
		}
		
			if (isset($this->request->post['module_purpletree_multivendor_seller_contact'])) {
			$data['module_purpletree_multivendor_seller_contact'] = $this->request->post['module_purpletree_multivendor_seller_contact'];
		} else {
			$data['module_purpletree_multivendor_seller_contact'] = $this->config->get('module_purpletree_multivendor_seller_contact');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_store_email'])) {
			$data['module_purpletree_multivendor_store_email'] = $this->request->post['module_purpletree_multivendor_store_email'];
		} else {
			$data['module_purpletree_multivendor_store_email'] = $this->config->get('module_purpletree_multivendor_store_email');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_store_phone'])) {
			$data['module_purpletree_multivendor_store_phone'] = $this->request->post['module_purpletree_multivendor_store_phone'];
		} else {
			$data['module_purpletree_multivendor_store_phone'] = $this->config->get('module_purpletree_multivendor_store_phone');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_store_address'])) {
			$data['module_purpletree_multivendor_store_address'] = $this->request->post['module_purpletree_multivendor_store_address'];
		} else {
			$data['module_purpletree_multivendor_store_address'] = $this->config->get('module_purpletree_multivendor_store_address');
		}
		
		if (isset($this->request->post['module_purpletree_multivendor_seller_invoice'])) {
			$data['module_purpletree_multivendor_seller_invoice'] = $this->request->post['module_purpletree_multivendor_seller_invoice'];
		} else {
			$data['module_purpletree_multivendor_seller_invoice'] = $this->config->get('module_purpletree_multivendor_seller_invoice');
		}

		
		if (isset($this->request->post['module_purpletree_multivendor_browse_sellers'])) {
				$data['module_purpletree_multivendor_browse_sellers'] = $this->request->post['module_purpletree_multivendor_browse_sellers'];
		} else {
		$data['module_purpletree_multivendor_browse_sellers'] = $this->config->get('module_purpletree_multivendor_browse_sellers');
		}
				if (isset($this->request->post['module_purpletree_multivendor_seller_contact'])) {
			$data['module_purpletree_multivendor_seller_contact'] = $this->request->post['module_purpletree_multivendor_seller_contact'];
		} else {
			$data['module_purpletree_multivendor_seller_contact'] = $this->config->get('module_purpletree_multivendor_seller_contact');
		}


		
		$data['user_token'] = $this->session->data['user_token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/purpletree_multivendor', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/purpletree_multivendor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if(!isset($this->request->post['module_purpletree_multivendor_commission_status']) || $this->request->post['module_purpletree_multivendor_commission_status'] == '') {
			
			$this->error['commission_status'] = $this->language->get('error_commission_status');
		}

		if($this->request->post['module_purpletree_multivendor_commission'] > 100){
		
		$this->error['commission'] = $this->language->get('error_commission');			
			
		}elseif( ! filter_var($this->request->post['module_purpletree_multivendor_commission'], FILTER_VALIDATE_FLOAT) && $this->request->post['module_purpletree_multivendor_commission'] != '0' ){
			$this->error['commission'] = $this->language->get('error_commission');

		} elseif($this->request->post['module_purpletree_multivendor_commission'] < 0){
			$this->error['commission'] = $this->language->get('error_commission');
		}
		if($this->request->post['module_purpletree_multivendor_product_limit'] < 1 ){
			$this->error['product_limit'] = $this->language->get('error_product_limit');
		}

		return !$this->error;
	}
		public function getSelectedCategory()
	{
		$json = array();
			$this->load->model('catalog/category');
			$results = $this->model_catalog_category->getCategories();
			foreach ($results as $result) {
				
		$categories = $this->config->get('module_purpletree_multivendor_allow_category');
					if(in_array($result['category_id'],$categories)) {
					$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
					}
			}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}
?>