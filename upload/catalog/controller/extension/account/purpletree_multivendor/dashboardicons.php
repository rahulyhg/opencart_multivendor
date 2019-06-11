<?php 
class ControllerExtensionAccountPurpletreeMultivendorDashboardicons extends Controller {
	private $error = array();
	
	public function index(){
			
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('extension/account/purpletree_multivendor/dashboard', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

	
		$this->load->language('purpletree_multivendor/dashboard');
		$this->load->model('extension/purpletree_multivendor/dashboard');
		$data['seller_orders'] = array();
		

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		$url ='';
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home','',true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/dashboardicons', $url, true)
		);
		$data['isSeller'] = $this->customer->isSeller();
		$store_id = (isset($data['isSeller']['id'])?$data['isSeller']['id']:'');
		$this->load->model('localisation/order_status');
		$this->document->setTitle($this->language->get('heading_title1'));
		$data['heading_title']=$this->language->get('heading_title1');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer'); 
		$data['header'] = $this->load->controller('common/header');

		
				$data['sellerprofile'] = $this->url->link('extension/account/edit', '', true);
				$data['downloadsitems'] = $this->url->link('extension/account/purpletree_multivendor/downloads', '', true);
				$data['sellerstore'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true);
				$data['sellerproduct'] = $this->url->link('extension/account/purpletree_multivendor/sellerproduct', '', true);
				$orderstatus = 0;
				if(null !== $this->config->get('module_purpletree_multivendor_commission_status')) {
					$orderstatus = $this->config->get('module_purpletree_multivendor_commission_status');
				}
				$data['sellerorder'] = $this->url->link('extension/account/purpletree_multivendor/sellerorder', 'filter_order_status='.$orderstatus.'&filter_admin_order_status='.$orderstatus.'', true);
				$data['sellercommission'] = $this->url->link('extension/account/purpletree_multivendor/sellercommission', '', true);
				$data['removeseller'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/removeseller', '', true);
				$data['becomeseller'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/becomeseller', '', true);
				$data['sellerview'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview&seller_store_id='.$store_id, '', true);
				$data['sellerreview'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/sellerreview', '', true);
				$data['sellerenquiry'] = $this->url->link('extension/account/purpletree_multivendor/sellercontact/sellercontactlist', '', true);
				$data['dashboardicons'] = $this->url->link('extension/account/purpletree_multivendor/dashboardicons', '', true);
				$data['dashboard'] = $this->url->link('extension/account/purpletree_multivendor/dashboard', '', true);
											
	
		$this->response->setOutput($this->load->view('account/purpletree_multivendor/dashboardicons', $data));
	}	

}
