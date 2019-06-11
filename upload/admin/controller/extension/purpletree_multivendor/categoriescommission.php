<?php 
class ControllerExtensionPurpletreeMultivendorCategoriescommission extends Controller{
	
	public function index(){
		
						
		$this->load->language('purpletree_multivendor/categoriescommission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/purpletree_multivendor/categoriescommission');
		$this->load->model('customer/customer_group');
		
		$this->getList();
	}
	
	
	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}	
       if (isset($this->request->get['filter_commission'])) {
			$filter_commission = $this->request->get['filter_commission'];
		} else {
			$filter_commission = '';
		}	
		
		
		if (isset($this->request->get['filter_commission_fixed'])) {
			$filter_commission_fixed = $this->request->get['filter_commission_fixed'];
		} else {
			$filter_commission_fixed = '';
		}
		if (isset($this->request->get['filter_seller_group'])) {
			$filter_seller_group = $this->request->get['filter_seller_group'];
		} else {
			$filter_seller_group = '';
		}
		

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . urlencode(html_entity_decode($this->request->get['filter_commission'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/purpletree_multivendor/categoriescommission', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		
		if (!isset($this->request->get['category_id'])) {
			$data['action'] = $this->url->link('extension/purpletree_multivendor/categoriescommission/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} 
		$filter_data = array(
			'filter_name'              => $filter_name,
			'filter_email'             => $filter_commission,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);

		$commission_total = $this->model_extension_purpletree_multivendor_categoriescommission->getTotalCategoriesCommission($filter_data);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_approved'] = $this->language->get('text_approved');
		$data['text_notapproved'] = $this->language->get('text_notapproved');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_categories'] = $this->language->get('column_categories');
		$data['column_commission'] = $this->language->get('column_commission');
		$data['column_commission_fixed'] = $this->language->get('column_commission_fixed');
		$data['column_seller_group'] = $this->language->get('column_seller_group');
		

		$data['entry_Categories'] = $this->language->get('entry_Categories');
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_approved'] = $this->language->get('entry_approved');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_commission_fixed'] = $this->language->get('entry_commission_fixed');

		$data['button_approve'] = $this->language->get('button_approve');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
	
		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' .$this->request->get['filter_commission'];
		}	

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		
		$data['sort_commission'] = $this->url->link('extension/purpletree_multivendor/categoriescommission', 'user_token=' . $this->session->data['user_token'] . '&sort=commission' . $url, true);

		

		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}
		
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
        
		$pagination = new Pagination();
		$pagination->total = $commission_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/purpletree_multivendor/categoriescommission', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();
		
		 $data['results'] = sprintf($this->language->get('text_pagination'), ($commission_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($commission_total - $this->config->get('config_limit_admin'))) ? $commission_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $commission_total, ceil($commission_total / $this->config->get('config_limit_admin'))); 

		         
		$data['filter_name'] = $filter_name;
		$data['filter_commission'] = $filter_commission;
		$data['sort'] = $sort;
		$data['order'] = $order;
				
		$data['commissions']=array();
		$data['show_seller_group'] = $this->config->get('module_purpletree_multivendor_seller_group');
		
		$categoriescommissions = $this->model_extension_purpletree_multivendor_categoriescommission->getCategoriesCommission($filter_data,$data['show_seller_group']);
		$curency = $this->config->get('config_currency');		
		$currency_detail = $this->model_extension_purpletree_multivendor_categoriescommission->getCurrencySymbol($curency);
		foreach($categoriescommissions as $categoriescommission)
		{
			$data['commissions'][] = array(
				'commission_id' =>	$categoriescommission['id'],
				'name' => $categoriescommission['name'],
					'commission' => $categoriescommission['commission']."%",
				'commission_fixed' => $this->currency->format($categoriescommission['commission_fixed'], $currency_detail['code'], $currency_detail['value']),
				'seller_group' => isset($categoriescommission['seller_group'])?$categoriescommission['seller_group']:'',
				'editaction' => $this->url->link('extension/purpletree_multivendor/categoriescommission/edit', 'user_token=' . $this->session->data['user_token'] . '&commission_id=' . $categoriescommission['id'] . $url, true),
				'deleteaction' => $this->url->link('extension/purpletree_multivendor/categoriescommission/delete', 'user_token=' . $this->session->data['user_token'] . '&commission_id=' . $categoriescommission['id'] . $url, true)
				
				
			);
		}
		$data['seller_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/purpletree_multivendor/categories_commission', $data));
		
       
	}
	public function add(){
		
		$this->load->language('purpletree_multivendor/categoriescommission');		

		$this->load->model('extension/purpletree_multivendor/categoriescommission');
		
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->request->post['filter_id'] && $this->request->post['filter_commission'] && is_numeric($this->request->post['filter_commission']) && is_numeric($this->request->post['filter_commission_fixed'])) {
			
			$this->model_extension_purpletree_multivendor_categoriescommission->addCategoriesCommission($this->request->post);

			$this->session->data['success'] = $this->language->get('text_editsuccess');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->session->data['success'] = $this->language->get('text_success');
		   } else {
			$this->session->data['error_warning'] = $this->language->get('error_warning');
		    }
			
			$this->response->redirect($this->url->link('extension/purpletree_multivendor/categoriescommission', 'user_token=' . $this->session->data['user_token'] . $url, true));
			

			

	}
	
	public function edit(){
		
		
     
		$this->load->language('purpletree_multivendor/categoriescommission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/purpletree_multivendor/categoriescommission');
		$this->load->model('customer/customer_group');
		 
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				if(is_numeric($this->request->post['filter_commission'] )) {
			
			
			$this->model_extension_purpletree_multivendor_categoriescommission->editCategoriesCommission($this->request->post);
			
			
			
			$this->session->data['success'] = $this->language->get('text_editsuccess');

			$url = ''; 
			
			if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
			}

			if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_commission'])) {
				$url .= '&filter_commission=' .$this->request->get['filter_commission'];
			}
			
			$this->response->redirect($this->url->link('extension/purpletree_multivendor/categoriescommission', 'user_token=' . $this->session->data['user_token'] . $url, true));
		    }  else {
			$this->session->data['error_warning'] = $this->language->get('error_warning');
		    }
	}			
		 $data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit_commission'] = $this->language->get('text_edit_commission');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_categories'] = $this->language->get('column_categories');
		$data['column_commission'] = $this->language->get('column_commission');		
		$data['entry_Categories'] = $this->language->get('entry_Categories');
		$data['entry_commission'] = $this->language->get('entry_commission');	
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_approved'] = $this->language->get('entry_approved');
		$data['button_approve'] = $this->language->get('button_approve');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['breadcrumbs'] = array();
        $url = '';
		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/purpletree_multivendor/categoriescommission', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		

		 $data['user_token'] = $this->session->data['user_token'];
            $data['commission_id'] = $this->request->get['commission_id'];
			
            $commission = $this->model_extension_purpletree_multivendor_categoriescommission->getCommission($this->request->get['commission_id']);
			$data['category_id'] = $commission['category_id'];
			
			$categoryname = $this->model_extension_purpletree_multivendor_categoriescommission->getCategoryName($data['category_id']);
			$data['seller_group'] = $commission['seller_group'];	
			$data['filter_commission_fixed'] = $commission['commison_fixed'];	
			$data['show_seller_group'] = $this->config->get('module_purpletree_multivendor_seller_group');
				$data['seller_groups'] = $this->model_customer_customer_group->getCustomerGroups();
            $data['category_name'] = $categoryname['name'];
			
			$data['category_commission'] = $commission['commission'];
		    $data['header'] = $this->load->controller('common/header');
		    $data['column_left'] = $this->load->controller('common/column_left');
		    $data['footer'] = $this->load->controller('common/footer');
		

			$this->response->setOutput($this->load->view('extension/purpletree_multivendor/categories_commissionedit',$data));
	}
	public function delete() {
		$this->load->language('purpletree_multivendor/categoriescommission');

		$this->load->model('extension/purpletree_multivendor/categoriescommission');
		
		if($this->request->get['commission_id']){
			 $this->model_extension_purpletree_multivendor_categoriescommission->deleteCategoriesCommission($this->request->get['commission_id']);
		$this->session->data['success'] = $this->language->get('text_delete_success');
		}
		
		$url = '';
		$this->response->redirect($this->url->link('extension/purpletree_multivendor/categoriescommission', 'user_token=' . $this->session->data['user_token'] . $url, true));

			$this->session->data['success'] = $this->language->get('text_delete_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['filter_commission'])) {
				$filter_commission = $this->request->get['filter_commission'];
			} else {
				$filter_commission = null;
			}
            
			if (isset($this->request->get['filter_commission_fixed'])) {
				$filter_commission_fixed = $this->request->get['filter_commission_fixed'];
			} else {
				$filter_commission_fixed = null;
			}
			if (isset($this->request->get['filter_seller_group'])) {
				$filter_seller_group = $this->request->get['filter_seller_group'];
			} else {
				$filter_seller_group = null;
			}

			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			} 

			$this->response->redirect($this->url->link('extension/purpletree_multivendor/categoriescommission', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		
			
	public function autocomplete() {
$json = array();

		if (isset($this->request->get['filter_name'])) {
		if (isset($this->request->get['filter_name'])) {
		$filter_name = $this->request->get['filter_name'];
		} else {
		$filter_name = '';
		}
		$this->load->model('extension/purpletree_multivendor/categoriescommission');

		$filter_data = array(
		'filter_name'      => $filter_name,
		'start'            => 0,
		'limit'            => 5
		);

		//$getAllCategories = $this->model_extension_purpletree_multivendor_categoriescommission->getAllCategories();
		$results = $this->model_extension_purpletree_multivendor_categoriescommission->getCategories($filter_data);
	$allowedCategories = array();
		if($this->config->get('module_purpletree_multivendor_allow_category')){
			$allowedCategories = $this->config->get('module_purpletree_multivendor_allow_category');
		}	
		foreach ($results as $result) {
			if($this->config->get('module_purpletree_multivendor_allow_categorytype')==0){
				if(in_array($result['category_id'],$allowedCategories)) {
		$json[] = array(
		'vendor_id'       => $result['category_id'],
		'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))

		);
			}
			} else if($this->config->get('module_purpletree_multivendor_allow_categorytype')==1) {
			$json[] = array(
			'vendor_id'       => $result['category_id'],
			'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
		$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
?>