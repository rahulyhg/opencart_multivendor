<?php
class ControllerExtensionPurpletreeMultivendorSellercontacts extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('purpletree_multivendor/sellercontacts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/purpletree_multivendor/sellercontacts');
		
		$this->getList();
	}
	
	public function delete() {
		$this->load->language('purpletree_multivendor/sellercontacts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/purpletree_multivendor/sellercontacts');

		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $message_id) {
				$this->model_extension_purpletree_multivendor_sellercontacts->deleteMessage($message_id);
			}

			$this->session->data['success'] = $this->language->get('text_delete_success');

			$url = '';

			if (isset($this->request->get['filter_seller_name'])) {
				$filter_seller_name = $this->request->get['filter_seller_name'];
			} else {
				$filter_seller_name = null;
			}

			if (isset($this->request->get['filter_customer_name'])) {
				$filter_customer_name = $this->request->get['filter_customer_name'];
			} else {
				$filter_customer_name = null;
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = null;
			}

			if (isset($this->request->get['filter_created_at'])) {
				$filter_created_at = $this->request->get['filter_created_at'];
			} else {
				$filter_created_at = null;
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/purpletree_multivendor/sellercontacts', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}
	
	protected function getList() {
		
		if (isset($this->request->get['filter_seller_name'])) {
			$filter_seller_name = $this->request->get['filter_seller_name'];
		} else {
			$filter_seller_name = null;
		}

		if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$filter_customer_name = null;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}

		if (isset($this->request->get['filter_created_at'])) {
			$filter_created_at = $this->request->get['filter_created_at'];
		} else {
			$filter_created_at = null;
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['seller_id'])) {
			$seller_id = $this->request->get['seller_id'];
		} else {
			$seller_id = 0;
		}
		
		$data['seller_id'] = (isset($this->request->get['seller_id'])?$this->request->get['seller_id']:'');
		$url = '';

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_seller_name'])) {
			$url .= '&filter_seller_name=' . urlencode(html_entity_decode($this->request->get['filter_seller_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_created_at'])) {
			$url .= '&filter_created_at=' . $this->request->get['filter_created_at'];
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
			'href' => $this->url->link('extension/purpletree_multivendor/sellercontacts', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['view'] = $this->url->link('extension/purpletree_multivendor/sellercontacts/view', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/purpletree_multivendor/sellercontacts/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['sellercontacts'] = array();

		$filter_data = array(
			'filter_customer_name'    => $filter_customer_name,
			'filter_seller_name'     => $filter_seller_name,
			'filter_email'     => $filter_email,
			'filter_created_at' => $filter_created_at,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin'),
			'seller_id'			=> $seller_id
		);

		$contact_total = $this->model_extension_purpletree_multivendor_sellercontacts->getTotalsellercontacts($filter_data);

		$results = $this->model_extension_purpletree_multivendor_sellercontacts->getsellercontacts($filter_data);
		
		foreach ($results as $result) {
			
		
				$view = $this->url->link('extension/purpletree_multivendor/sellercontacts/view', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['id'].'&seller_id='.$result['seller_id'] . $url, true);
				
			$data['sellercontacts'][] = array(
				'id'     => $result['id'],
				'seller_name'     => $result['seller_name'],
				'contact_from'     => $result['contact_from'],
				'customer_name'     => $result['customer_name'],
				'customer_email'     => $result['customer_email'],
				'customer_message'       => utf8_substr(nl2br($result['customer_message']),0,40),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
				'view' => $view
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['text_storereview'] = $this->language->get('text_storereview');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_customer_name'] = $this->language->get('text_customer_name');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_seller_name'] = $this->language->get('text_seller_name');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['column_action'] = $this->language->get('column_action');
		$data['text_empty_result'] = $this->language->get('text_empty_result');
		$data['text_heading'] = $this->language->get('text_heading');
		$data['button_view'] = $this->language->get('button_view');
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

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_seller_name'])) {
			$url .= '&filter_seller_name=' . urlencode(html_entity_decode($this->request->get['filter_seller_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_created_at'])) {
			$url .= '&filter_created_at=' . $this->request->get['filter_created_at'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$url = '';

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_seller_name'])) {
			$url .= '&filter_seller_name=' . urlencode(html_entity_decode($this->request->get['filter_seller_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_created_at'])) {
			$url .= '&filter_created_at=' . $this->request->get['filter_created_at'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $contact_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/purpletree_multivendor/sellercontacts', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($contact_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($contact_total - $this->config->get('config_limit_admin'))) ? $contact_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $contact_total, ceil($contact_total / $this->config->get('config_limit_admin')));

		$data['filter_customer_name'] = $filter_customer_name;
		$data['filter_seller_name'] = $filter_seller_name;
		$data['filter_email'] = $filter_email;
		$data['filter_created_at'] = $filter_created_at;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/purpletree_multivendor/sellercontact_list', $data));
	}
	
	public function view() {
		
		$this->load->language('purpletree_multivendor/sellercontacts');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['text_email'] = $this->language->get('text_email');
		$data['text_customer_name'] = $this->language->get('text_customer_name');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_seller_name'] = $this->language->get('text_seller_name');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['column_action'] = $this->language->get('column_action');
		$data['text_empty_result'] = $this->language->get('text_empty_result');
		$data['text_heading'] = $this->language->get('text_heading');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/purpletree_multivendor/sellercontacts', 'user_token=' . $this->session->data['user_token'] , true)
		);

		$data['action'] = $this->url->link('extension/purpletree_multivendor/sellercontacts/view', 'user_token=' . $this->session->data['user_token'] . '&id=' . $this->request->get['id'].'&seller_id='. $this->request->get['seller_id'], true);

		$data['cancel'] = $this->url->link('extension/purpletree_multivendor/sellercontacts', 'user_token=' . $this->session->data['user_token'], true);
		
		$this->load->model('extension/purpletree_multivendor/sellercontacts');
		
		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$message_info = $this->model_extension_purpletree_multivendor_sellercontacts->getMessage($this->request->get['id']);
		}
		
		$data['user_token'] = $this->session->data['user_token'];
		
		if (!empty($message_info)) {
			$data['customer_name'] = $message_info['customer_name'];
		} else {
			$data['customer_name'] = '';
		}
		
		if (!empty($message_info['seller_id'])) {
			$data['seller_name'] = $message_info['seller_name'];
		} else {
			$data['seller_name'] = '';
		}
		
		if (isset($this->request->post['customer_email'])) {
			$data['customer_email'] = $this->request->post['customer_email'];
		} elseif (!empty($message_info)) {
			$data['customer_email'] = $message_info['customer_email'];
		} else {
			$data['customer_email'] = '';
		}

		if (isset($this->request->post['customer_message'])) {
			$data['customer_message'] = $this->request->post['customer_message'];
		} elseif (!empty($message_info)) {
			$data['customer_message'] = $message_info['customer_message'];
		} else {
			$data['customer_message'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/purpletree_multivendor/sellercontact_view', $data));
	}
}
