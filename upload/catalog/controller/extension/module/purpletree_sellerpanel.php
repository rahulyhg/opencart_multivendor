<?php
class ControllerExtensionModulePurpletreeSellerpanel extends Controller {
	public function index() {
		$this->load->language('extension/module/purpletree_sellerpanel');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['logged'] = $this->customer->isLogged();
		$data['isSeller'] = $this->customer->isSeller();
			$data['module_purpletree_multivendor_status'] = $this->config->get('module_purpletree_multivendor_status');
			$data['module_purpletree_multivendor_become_seller'] = $this->config->get('module_purpletree_multivendor_become_seller');
		if ($this->config->get('module_purpletree_multivendor_status')) {
			$store_id = (isset($data['isSeller']['id'])?$data['isSeller']['id']:'');
				$data['text_sellerstore'] = $this->language->get('text_sellerstore');
				$data['text_dashboard_icon'] = $this->language->get('text_dashboard_icon');
				$data['text_sellerproduct'] = $this->language->get('text_sellerproduct');
				$data['text_sellerprofile'] = $this->language->get('text_sellerprofile');
				$data['text_sellerorder'] = $this->language->get('text_sellerorder');
				$data['text_removeseller'] = $this->language->get('text_removeseller');
				$data['text_becomeseller'] = $this->language->get('text_becomeseller');
				$data['text_sellerview'] = $this->language->get('text_sellerview');
				$data['text_approval'] = $this->language->get('text_approval');
				$data['text_sellerreview'] = $this->language->get('text_sellerreview');
				$data['text_sellerenquiry'] = $this->language->get('text_sellerenquiry');
				$data['text_dashboard'] = $this->language->get('text_dashboard');
				$data['text_selleroption'] = $this->language->get('text_selleroption');

				$data['sellerprofile'] = $this->url->link('account/edit', '', true);
				$data['sellerstore'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true);
				$data['dashboardicons'] = $this->url->link('extension/account/purpletree_multivendor/dashboardicons', '', true);
				$data['sellerproduct'] = $this->url->link('extension/account/purpletree_multivendor/sellerproduct', '', true);
				$orderstatus = 0;
				if(null !== $this->config->get('module_purpletree_multivendor_commission_status')) {
					$orderstatus = $this->config->get('module_purpletree_multivendor_commission_status');
				}
				$data['sellerorder'] = $this->url->link('extension/account/purpletree_multivendor/sellerorder', 'filter_order_status='.$orderstatus.'&filter_admin_order_status='.$orderstatus.'', true);
				$data['removeseller'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/removeseller', '', true);
				$data['becomeseller'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/becomeseller', '', true);
				$data['sellerview'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview&seller_store_id='.$store_id, '', true);
				$data['sellerreview'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/sellerreview', '', true);
				$data['sellerenquiry'] = $this->url->link('extension/account/purpletree_multivendor/sellercontact/sellercontactlist', '', true);
				$data['dashboard'] = $this->url->link('extension/account/purpletree_multivendor/dashboard', '', true);


		}

		return $this->load->view('extension/module/purpletree_sellerpanel', $data);
	}
}