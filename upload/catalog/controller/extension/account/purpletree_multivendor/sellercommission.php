<?php 
class ControllerExtensionAccountPurpletreeMultivendorSellercommission extends Controller{
	public function index(){
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$store_detail = $this->customer->isSeller();
		if(!isset($store_detail['store_status'])){
			$this->response->redirect($this->url->link('account/account', '', true));
		}
		

		$this->load->language('purpletree_multivendor/sellercommission');
			
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/purpletree_multivendor/sellercommission');
		
		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
		} else {
			$end_date = date('Y-m-d', strtotime("-30 days"));
			$filter_date_from = $end_date;
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$end_date = date('Y-m-d');
			$filter_date_to = $end_date;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_total_sale'] = $this->language->get('text_total_sale');
		$data['text_total_commission'] = $this->language->get('text_total_commission');
		$data['text_recvd_amt'] = $this->language->get('text_recvd_amt');
		$data['text_pending_amt'] = $this->language->get('text_pending_amt');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_product_id'] = $this->language->get('text_product_id');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_created_at'] = $this->language->get('text_created_at');
		$data['text_commission'] = $this->language->get('text_commission');
		$data['text_product_price'] = $this->language->get('text_product_price');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['entry_date_from'] = $this->language->get('entry_date_from');
		$data['entry_date_to'] = $this->language->get('entry_date_to');
		
		$data['button_filter'] = $this->language->get('button_filter');
		
		$url = '';
		
		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}
	
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home','',true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_commission'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellercommission', '', true)
		);
		
		$seller_id = $this->customer->getId();
		$filter_data = array(
			'filter_date_from'    => $filter_date_from,
			'filter_date_to' => $filter_date_to,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin'),
			'order_status' => $this->config->get('module_purpletree_multivendor_commission_status'),
			'seller_id'				=> $seller_id
		);
		$data['seller_commissions'] = array();
		
		$total_sale = $this->model_extension_purpletree_multivendor_sellercommission->getTotalsale($filter_data);
		
		$seller_commissions = $this->model_extension_purpletree_multivendor_sellercommission->getCommissions($filter_data);
		
		$total_commissions = $this->model_extension_purpletree_multivendor_sellercommission->getTotalCommissions($filter_data);

		$curency = $this->session->data['currency'];
		
		$currency_detail = $this->model_extension_purpletree_multivendor_sellercommission->getCurrencySymbol($curency);
		
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
		
		$pagination = new Pagination();
		$pagination->total = $total_commissions;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/account/purpletree_multivendor/sellercommission', $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_commissions) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_commissions - $this->config->get('config_limit_admin'))) ? $total_commissions : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_commissions, ceil($total_commissions / $this->config->get('config_limit_admin')));

		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/purpletree_multivendor/commission_list', $data));
	}
}
?>