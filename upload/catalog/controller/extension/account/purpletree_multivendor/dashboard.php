<?php 
class ControllerExtensionAccountPurpletreeMultivendorDashboard extends Controller {
	private $error = array();
	
	public function index(){
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('extension/account/purpletree_multivendor/dashboard', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$store_detail = $this->customer->isSeller();
		if(!isset($store_detail['store_status'])){
			$this->response->redirect($this->url->link('account/account', '', true));
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/dashboard', $url, true)
		);
		$data['my_orders']=$this->url->link('extension/account/purpletree_multivendor/sellerorder', $url, true);
		
		$data['my_commission']=$this->url->link('extension/account/purpletree_multivendor/sellercommission', $url, true);

		$data['my_sales']=$this->url->link('extension/account/purpletree_multivendor/sellerorder', $url, true);

		$filter_data = array(
			'limit'                => $this->config->get('config_limit_admin'),
			'seller_id'            => $this->customer->getId()
		);
		$orderstatus = 0;
			if(null !== $this->config->get('module_purpletree_multivendor_commission_status')) {
				$orderstatus = $this->config->get('module_purpletree_multivendor_commission_status');
			} else {
				$data['error_warning'] = $this->language->get('module_purpletree_multivendor_commission_status_warning');
			}
		$filter_data1 = array(
			'seller_id'            => $this->customer->getId(),
			'order_status_id'            =>  $orderstatus
		);
		$seller_id = $this->customer->getId();
		
		$data['total_sale'] = 0;
		$data['total_pay'] = 0;
		$data['total_commission'] = 0;
		
		$total_commission = 0;
		
		$total_sale = 0;
		$orderstatus = 0;
			if(null !== $this->config->get('module_purpletree_multivendor_commission_status')) {
				$orderstatus = $this->config->get('module_purpletree_multivendor_commission_status');
			} else {
				$data['error_warning'] = $this->language->get('module_purpletree_multivendor_commission_status_warning');
			}
		$total_commission1 = $this->model_extension_purpletree_multivendor_dashboard->getTotalSellerOrderscommission($this->customer->getId(),$orderstatus);
		if(!empty($total_commission1)) {
			foreach($total_commission1 as $tot) {
					$total_commission+= $tot['commission'];
			}	
		}
		
		$sellerstore = $this->customer->isSeller();
		
		$data['order_total'] = $this->model_extension_purpletree_multivendor_dashboard->getTotalSellerOrders($filter_data);
		
		$results = $this->model_extension_purpletree_multivendor_dashboard->getSellerOrders($filter_data);
		$seller_commissions = $this->model_extension_purpletree_multivendor_dashboard->getCommissions($filter_data);
		$curency = $this->session->data['currency'];
		$currency_detail = $this->model_extension_purpletree_multivendor_dashboard->getCurrencySymbol($curency);
		$pending_payments = $this->model_extension_purpletree_multivendor_dashboard->pendingPayments($filter_data1);
		$totalpaymentss = 0;
		$orderstatus = 0;
			if(null !== $this->config->get('module_purpletree_multivendor_commission_status')) {
				$orderstatus = $this->config->get('module_purpletree_multivendor_commission_status');
			} else {
				$data['error_warning'] = $this->language->get('module_purpletree_multivendor_commission_status_warning');
			}
	 	if(!empty($pending_payments)) {
			foreach($pending_payments as $paymentsss) {
				//print_r($paymentsss); //die;
				if($paymentsss['seller_order_status'] == $paymentsss['admin_order_status'] && $paymentsss['seller_order_status'] == $orderstatus && $paymentsss['admin_order_status'] == $orderstatus) {
					$totalpaymentss += $paymentsss['total_price'];
				}
			}
		} 
		$data['total_sale'] =$this->currency->format($this->model_extension_purpletree_multivendor_dashboard->getTotalsale($filter_data), $currency_detail['code'], $currency_detail['value']);


		if($seller_commissions){
			foreach($seller_commissions as $seller_commission){
				$data['seller_commissions'][] = array(
					'order_id' => $seller_commission['order_id'],
					'product_name' => $seller_commission['name'],
					'price' => $this->currency->format($seller_commission['total_price'], $currency_detail['code'], $currency_detail['value']),
					'commission' => $this->currency->format($seller_commission['commission'], $currency_detail['code'], $currency_detail['value']),
					'created_at' => date($this->language->get('date_format_short'), strtotime($seller_commission['created_at']))
				);
			}
		}
	$total_commission = 0;
		foreach ($results as $result) {

			 $total ='';
				$product_totals  = $this->model_extension_purpletree_multivendor_dashboard->getSellerOrdersTotal($seller_id,$result['order_id']);

				if(isset($product_totals['total'])){
					$total = $product_totals['total'];
				} else {
					$total = 0;
				}
				
				$product_commission  = $this->model_extension_purpletree_multivendor_dashboard->getSellerOrdersCommissionTotal($result['order_id'],$seller_id);
			
			$total_sale+= $total;
			
			$total_commission+= $product_commission['total_commission'];
		
			$data['seller_orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'admin_order_status'      => $result['admin_order_status'],
				'order_status'  => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'total'         => $this->currency->format($total, $result['currency_code'], $result['currency_value']),
				'commission'         => $this->currency->format($product_commission['total_commission'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'view'          => $this->url->link('extension/account/purpletree_multivendor/sellerorder/seller_order_info', 'order_id=' . $result['order_id']. $url, true)
			);
		
		}


$data['total_order_commission'] = $this->currency->format($total_commission, $currency_detail['code'], $currency_detail['value']);
		
		$this->document->setTitle($this->language->get('heading_title'));
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_total'] =$this->language->get('text_total');
		$data['text_total_commission'] = $this->language->get('text_total_commission');
		$data['text_view_more'] = $this->language->get('text_view_more');
		$data['text_latest_order'] = $this->language->get('text_latest_order');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_admin_status'] = $this->language->get('text_admin_status');
		$data['text_commission'] = $this->language->get('text_commission');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_date_modified'] = $this->language->get('text_date_modified');
		$data['text_last_five_records'] = $this->language->get('text_last_five_records');
		$data['text_transaction_id'] = $this->language->get('text_transaction_id');
		$data['text_amount'] = $this->language->get('text_amount');
		$data['text_payment_mode'] = $this->language->get('text_payment_mode');
		$data['text_created_date'] = $this->language->get('text_created_date');
		$data['text_latest_commission'] = $this->language->get('text_latest_commission');
		$data['text_product_name'] = $this->language->get('text_product_name');
		$data['text_product_price'] = $this->language->get('text_product_price');
		$data['text_action'] = $this->language->get('text_action');
		$data['button_view'] = $this->language->get('button_view');
		
		$this->load->model('extension/localisation/order_status');
		$data['order_statuses'] = $this->model_extension_localisation_order_status->getOrderStatuses();
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/purpletree_multivendor/dashboard', $data));
	}	

}
