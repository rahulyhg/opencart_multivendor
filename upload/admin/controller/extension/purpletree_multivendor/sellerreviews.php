<?php
class ControllerExtensionPurpletreeMultivendorSellerreviews extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('purpletree_multivendor/sellerreview');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/purpletree_multivendor/sellerreview');
		
		$this->getList();
	}

	public function edit() {
		$this->load->language('purpletree_multivendor/sellerreview');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/purpletree_multivendor/sellerreview');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_purpletree_multivendor_sellerreview->editReview($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
			} else {
				$filter_customer_name = null;
			}

			if (isset($this->request->get['filter_title'])) {
				$filter_title = $this->request->get['filter_title'];
			} else {
				$filter_title = null;
			}

			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {
				$filter_status = null;
			}

			if (isset($this->request->get['filter_created_at'])) {
				$filter_created_at = $this->request->get['filter_created_at'];
			} else {
				$filter_created_at = null;
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

			$this->response->redirect($this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('purpletree_multivendor/sellerreview');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/purpletree_multivendor/sellerreview');

		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $review_id) {
				$this->model_extension_purpletree_multivendor_sellerreview->deleteReview($review_id);
			}

			$this->session->data['success'] = $this->language->get('text_delete_success');

			$url = '';

			if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
			} else {
				$filter_customer_name = null;
			}

			if (isset($this->request->get['filter_title'])) {
				$filter_title = $this->request->get['filter_title'];
			} else {
				$filter_title = null;
			}

			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {
				$filter_status = null;
			}

			if (isset($this->request->get['filter_created_at'])) {
				$filter_created_at = $this->request->get['filter_created_at'];
			} else {
				$filter_created_at = null;
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

			$this->response->redirect($this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}
	
	protected function getList() {
		if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$filter_customer_name = null;
		}

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
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

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
		
		if (isset($this->request->get['seller_id'])) {
			$url .= '&seller_id=' . $this->request->get['seller_id'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('extension/purpletree_multivendor/sellerreviews/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/purpletree_multivendor/sellerreviews/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['reviews'] = array();

		$filter_data = array(
			'filter_customer_name'    => $filter_customer_name,
			'filter_title'     => $filter_title,
			'filter_status'     => $filter_status,
			'filter_created_at' => $filter_created_at,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin'),
			'seller_id'			=> $seller_id
		);

		$review_total = $this->model_extension_purpletree_multivendor_sellerreview->getTotalSellerReview($filter_data);

		$results = $this->model_extension_purpletree_multivendor_sellerreview->getSellerReview($filter_data);
		
		foreach ($results as $result) {
			
			$edit = $this->url->link('extension/purpletree_multivendor/sellerreviews/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['id'].'&seller_id='.$result['seller_id'] . $url, true);
			
			
			$data['reviews'][] = array(
				'id'     => $result['id'],
				'store_name'     => $this->model_extension_purpletree_multivendor_sellerreview->getStoreName($result['seller_id']),
				'customer_name'     => $result['customer_name'],
				'review_title'     => $result['review_title'],
				'review_description'       => nl2br($result['review_description']),
				'rating'     => (int)$result['rating'],
				'status'     => (($result['status'])?$this->language->get('text_approved'):$this->language->get('text_notapproved')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
				'edit' => $edit
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all'] = $this->language->get('text_all');
		
		$data['text_storereview'] = $this->language->get('text_storereview');
		$data['text_title'] = $this->language->get('text_title');
		$data['text_customer_name'] = $this->language->get('text_customer_name');
		$data['text_store_name'] = $this->language->get('text_store_name');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_rating'] = $this->language->get('text_rating');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['column_action'] = $this->language->get('column_action');
		$data['text_empty_result'] = $this->language->get('text_empty_result');
		$data['text_heading'] = $this->language->get('text_heading');
		$data['text_note'] = $this->language->get('text_note');
		$data['text_approved'] = $this->language->get('text_approved');
		$data['text_notapproved'] = $this->language->get('text_notapproved');
		$data['button_edit'] = $this->language->get('button_edit');

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

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
		
		if (isset($this->request->get['seller_id'])) {
			$url .= '&seller_id=' . $this->request->get['seller_id'];
		}

		$data['sort_customer'] = $this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . '&sort=customer_name' . $url, true);
		$data['sort_title'] = $this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . '&sort=pvr.review_title' . $url, true);
		$data['sort_rating'] = $this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . '&sort=pvr.rating' . $url, true);
		$data['sort_status'] = $this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . '&sort=pvr.status' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . '&sort=pvr.created_at' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
		
		if (isset($this->request->get['seller_id'])) {
			$url .= '&seller_id=' . $this->request->get['seller_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($review_total - $this->config->get('config_limit_admin'))) ? $review_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $review_total, ceil($review_total / $this->config->get('config_limit_admin')));

		$data['filter_customer_name'] = $filter_customer_name;
		$data['filter_title'] = $filter_title;
		$data['filter_status'] = $filter_status;
		$data['filter_created_at'] = $filter_created_at;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/purpletree_multivendor/review_list', $data));
	}
	
	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_storereview'] = $this->language->get('text_storereview');
		$data['text_title'] = $this->language->get('text_title');
		$data['text_customer_name'] = $this->language->get('text_customer_name');
		$data['text_seller_name'] = $this->language->get('text_seller_name');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_rating'] = $this->language->get('text_rating');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['column_action'] = $this->language->get('column_action');
		$data['text_empty_result'] = $this->language->get('text_empty_result');
		$data['text_heading'] = $this->language->get('text_heading');
		$data['text_note'] = $this->language->get('text_note');
		$data['text_approved'] = $this->language->get('text_approved');
		$data['text_notapproved'] = $this->language->get('text_notapproved');
		$data['button_edit'] = $this->language->get('button_edit');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = '';
		}

		if (isset($this->error['rating'])) {
			$data['error_rating'] = $this->error['rating'];
		} else {
			$data['error_rating'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('extension/purpletree_multivendor/sellerreviews/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/purpletree_multivendor/sellerreviews/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $this->request->get['id'].'&seller_id='. $this->request->get['seller_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/purpletree_multivendor/sellerreviews', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$review_info = $this->model_extension_purpletree_multivendor_sellerreview->getReview($this->request->get['id']);
		}
		
		$data['user_token'] = $this->session->data['user_token'];
		
		if (!empty($review_info)) {
			$data['customer_name'] = $review_info['customer_name'];
		} else {
			$data['customer_name'] = '';
		}
		
		if (!empty($review_info['seller_id'])) {
			$data['seller_name'] = $this->model_extension_purpletree_multivendor_sellerreview->getSellerName($review_info['seller_id']);
		} else {
			$data['seller_name'] = '';
		}
		
		if (isset($this->request->post['review_title'])) {
			$data['review_title'] = $this->request->post['review_title'];
		} elseif (!empty($review_info)) {
			$data['review_title'] = $review_info['review_title'];
		} else {
			$data['review_title'] = '';
		}

		if (isset($this->request->post['review_description'])) {
			$data['review_description'] = $this->request->post['review_description'];
		} elseif (!empty($review_info)) {
			$data['review_description'] = $review_info['review_description'];
		} else {
			$data['review_description'] = '';
		}

		if (isset($this->request->post['rating'])) {
			$data['rating'] = $this->request->post['rating'];
		} elseif (!empty($review_info)) {
			$data['rating'] = $review_info['rating'];
		} else {
			$data['rating'] = '';
		}


		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($review_info)) {
			$data['status'] = $review_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/purpletree_multivendor/review_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/purpletree_multivendor/sellerreviews')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['review_title'])) {
			$this->error['error_title'] = $this->language->get('error_title');
		}

		if (utf8_strlen($this->request->post['review_description']) < 1) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (!isset($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
			$this->error['rating'] = $this->language->get('error_rating');
		}
		
		return !$this->error;
	}
}
