<?php
class ControllerExtensionAccountPurpletreeMultivendorSellercontact extends Controller {
	private $error = array();

	protected function validatemessage() {
		
		if ((utf8_strlen($this->request->post['customer_message']) < 10) || (utf8_strlen($this->request->post['customer_message']) > 3000)) {
			$this->error['customer_message'] = $this->language->get('error_enquiry');
		}

		// Captcha
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
		}
			return !$this->error;
	}
	protected function validate() {
					
		if ((utf8_strlen($this->request->post['customer_name']) < 3) || (utf8_strlen($this->request->post['customer_name']) > 32)) {
			$this->error['customer_name'] = $this->language->get('error_name');
		}
		$EMAIL_REGEX='/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
		if (preg_match($EMAIL_REGEX, $this->request->post['customer_email'])==false) {
			$this->error['customer_email'] = $this->language->get('error_email');
		}
		
		if ((utf8_strlen($this->request->post['customer_message']) < 10) || (utf8_strlen($this->request->post['customer_message']) > 3000)) {
			$this->error['customer_message'] = $this->language->get('error_enquiry');
		}

		// Captcha
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
		}

		return !$this->error;
	}
		public function customerContactlist(){
		
		$this->load->model('extension/purpletree_multivendor/sellercontact');
		$this->load->language('purpletree_multivendor/sellercontact');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_empty_result'] = $this->language->get('text_empty_result');
	
		if($this->customer->isLogged()){
			
			if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
			} else {
				$page = 1;
			}	
			
			if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
			} else {
				$limit = 10;
			}
			 $customer_id = $this->customer->getId();
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home','',true)
			);
			
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_heading'),
				'href' => $this->url->link('extension/account/purpletree_multivendor/sellercontact/customerContactlist', '', true)
			);
			$data['text_heading'] = $this->language->get('text_heading');
			$filter_data = array(
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit,
				'customer_id' 		=> $customer_id
			);
			$contact_total = $this->model_extension_purpletree_multivendor_sellercontact->getSellerContactCustomers122($filter_data);

			$results1 = $this->model_extension_purpletree_multivendor_sellercontact->getSellerContactCustomers1($filter_data); 
		
			$data['sellercontacts'] = array();
			if(!empty($results1)) {
				foreach($results1 as $re) {
					$seller_id 	= $re['seller_id'];
					$customernnaaa = $this->model_extension_purpletree_multivendor_sellercontact->getCustomer($seller_id);
					$results2 	= $this->model_extension_purpletree_multivendor_sellercontact->getSellerContactCustomerschat($customer_id,$seller_id);
						//$message = array();
						//$contact_from = array();
					if(!empty($results2)) {
						foreach($results2 as $result){
							$data['sellercontacts'][$seller_id] = array(
							'id' 			 =>  $result['id'],
							'message' 		 =>  nl2br($result['customer_message']),
							'seller_id' 	 =>  $result['seller_id'],
							'customer_id' 	 =>  $customer_id,
							'contact_from' 	 =>  $result['contact_from'],
							'customer_name'  => $customernnaaa['firstname'].' '. $customernnaaa['lastname'],
							'customer_email'  => $customernnaaa['email'],
							'date_added' 	 => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
							'reply'			 => $this->url->link('extension/account/purpletree_multivendor/sellercontact/customerreply','id='. $result['id'], true)
					);
						}
					}
				}
			}
		
			$data['config_contactseller']=$this->config->get('module_purpletree_multivendor_seller_contact');
			
		} else {
				$this->session->data['redirect'] = $this->url->link('extension/account/purpletree_multivendor/sellercontact/customerContactlist','', true);
				$this->response->redirect($this->url->link('account/login','', true));				
		}
			
			$pagination = new Pagination();
			$pagination->total = $contact_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/account/purpletree_multivendor/sellercontact/customercontactlist', '&page={page}',true);

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($contact_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($contact_total - $limit)) ? $contact_total : ((($page - 1) * $limit) + $limit), $contact_total, ceil($contact_total / $limit));
				
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
	if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

			$this->response->setOutput($this->load->view('account/purpletree_multivendor/customercontactlist', $data));
		}
	
	
	public function sellercontactlist(){
		
			$data['contact_mode'] = $this->config->get('module_purpletree_multivendor_seller_contact');
		$this->load->model('extension/purpletree_multivendor/sellercontact');
		$this->load->language('purpletree_multivendor/sellercontact');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_empty_result'] = $this->language->get('text_empty_result');
		
		if($this->customer->isSeller()){
			
			if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
			} else {
				$page = 1;
			}	
			
			if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
			} else {
				$limit = 10;
			}
			 $seller_id = $this->customer->getId();
			//die;
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home','',true)
			);
			
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_heading'),
				'href' => $this->url->link('extension/account/purpletree_multivendor/sellercontact/sellercontactlist', '', true)
			);
			$data['text_heading'] = $this->language->get('text_heading');
			$filter_data = array(
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit,
				'seller_id' 		=> $seller_id
			);
				
			$contact_total = $this->model_extension_purpletree_multivendor_sellercontact->getSellerContactCustomers1111($filter_data);
			$results1 = $this->model_extension_purpletree_multivendor_sellercontact->getSellerContactCustomers($filter_data);
			$data['sellercontacts'] = array();
			if(!empty($results1)) {
				foreach($results1 as $re) {
					$custid 	= $re['customer_id'];
					$customernnaaa = $this->model_extension_purpletree_multivendor_sellercontact->getCustomer($custid);
					$results2 	= $this->model_extension_purpletree_multivendor_sellercontact->getSellerContactCustomerschat($custid,$seller_id);
						$message = array();
						$contact_from = array();
					if(!empty($results2)) {
						foreach($results2 as $result){
							if($result['customer_id'] == '0') {
								$nameeee = "Guest";
								$emailll = "Guest";
							} else {
								$nameeee = $customernnaaa['firstname'].' '. $customernnaaa['lastname'];
								$emailll = $customernnaaa['email'];
							}
							$data['sellercontacts'][] = array(
							'id' 			 =>  $result['id'],
							'message' 		 =>  nl2br($result['customer_message']),
							'customer_id' 	 =>  $result['customer_id'],
							'contact_from' 	 =>  $result['contact_from'],
							'customer_name'  =>  $nameeee,
							'customer_email'  => $emailll,
							'date_added' 	 => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
							'reply'			 => $this->url->link('extension/account/purpletree_multivendor/sellercontact/reply','id='. $result['id'], true)
					);
							
						}
					}
				}
			}
			$data['config_contactseller']	=	$this->config->get('module_purpletree_multivendor_seller_contact');
			
			 $pagination = new Pagination();
			$pagination->total = $contact_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/account/purpletree_multivendor/sellercontact/sellercontactlist', '&page={page}',true);

			$data['pagination'] = $pagination->render(); 

			 $data['results'] = sprintf($this->language->get('text_pagination'), ($contact_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($contact_total - $limit)) ? $contact_total : ((($page - 1) * $limit) + $limit), $contact_total, ceil($contact_total / $limit)); 
				
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
				if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
	
			$this->response->setOutput($this->load->view('account/purpletree_multivendor/contactlist', $data));
		} else {
			$this->response->redirect($this->url->link('account/account','',true));	
			}
	}
	
	
		public function reply() {
				if ($this->customer->isLogged()) {
			$data['loggedin'] = '1';
		} else {
			$data['loggedin'] = '0';
		}
		
				$data['contact_mode'] = $this->config->get('module_purpletree_multivendor_seller_contact');
		
		if($this->config->get('module_purpletree_multivendor_seller_contact')==1){
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('extension/account/purpletree_multivendor/sellercontact/reply', 'id='.$this->request->get['customer_id'], true);
				$this->response->redirect($this->url->link('account/login', '', true));
			}
		}
		
		$this->load->language('purpletree_multivendor/sellercontact');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/purpletree_multivendor/sellercontact');

		if(!$this->customer->isSeller()){
			$this->response->redirect($this->url->link('account/account','',true));	
		}
		if(!isset($this->request->get['id'])){
			$this->response->redirect($this->url->link('extension/account/purpletree_multivendor/sellercontact/sellercontactlist','',true));	
		}
		$customerid = $this->model_extension_purpletree_multivendor_sellercontact->getCustomerid($this->request->get['id']);
		$data['customer_id'] = $customerid;
		$seller_id = $this->customer->getId();
		if($customerid == $seller_id) {
						$this->response->redirect($this->url->link('extension/account/purpletree_multivendor/sellercontact/sellercontactlist','',true));
		}
		$data['customer'] = $this->customer->getId();	

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatemessage()) {
				 $customerid = $this->request->post['customer_id'];
				$customer = $this->model_extension_purpletree_multivendor_sellercontact->getCustomer($customerid);
				$selllleeerr = $this->model_extension_purpletree_multivendor_sellercontact->getCustomer($seller_id);
			$dataa = array(
					'customer_id' 	 => $customerid,
					'seller_id'		 => $seller_id,
					'customer_name'  => $selllleeerr['firstname'].' '. $selllleeerr['lastname'],
					'customer_email'  => $selllleeerr['email'],
					'customer_message'  => $this->request->post['customer_message'],
					'contact_from'   => 1
					);
			$this->model_extension_purpletree_multivendor_sellercontact->addContact($dataa);

			$ptsmv_current_page='';
			$message = $this->request->post['customer_message'];
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($customer['firstname'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $customer['firstname']), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($message);
			$mail->send();

			$this->session->data['success'] = $this->language->get('text_success');
			
			//unset($this->session->data['ptsmv_current_page']);
			
			//$this->response->redirect($this->url->link('extension/account/purpletree_multivendor/sellercontact/sellercontactlist','',true));
		} else {
					if (isset($this->request->post['customer_message'])) {
			$data['customer_message'] = $this->request->post['customer_message'];
		} else {
			$data['customer_message'] = '';
		}
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home','',true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellercontactlist','',true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_enquiry'] = $this->language->get('entry_enquiry');

		if (isset($this->error['customer_message'])) {
			$data['error_enquiry'] = $this->error['customer_message'];
		} else {
			$data['error_enquiry'] = '';
		}
		
		if (isset($this->error['error_warning'])) {
			$data['error_warning'] = $this->error['error_warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['button_submit'] = $this->language->get('button_submit');
;

			$customer_detal=array();
			$data['customer_id']=$customerid;
			$data['sellercontacts'] = array();
						$results2 	= $this->model_extension_purpletree_multivendor_sellercontact->getSellerContactCustomerschat122($seller_id,$customerid);
						$message = array();
						$contact_from = array();
						$date_added = array();
					if(!empty($results2)) {
						foreach($results2 as $result){
						$data['sellercontacts'][] = array(
						'contact_from'     => $result['contact_from'],
						'customer_id'     => $result['customer_id'],
						'customer_name'     => $result['customer_name'],
						'customer_email'     => $result['customer_email'],
						'customer_messages'       => nl2br($result['customer_message']),
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
					);
						}
					}

		// Captcha
		$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'), $this->error);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/purpletree_multivendor/customer_reply', $data));
	}
	
		public function customerReply() {

		if ($this->customer->isLogged()) {
			$data['loggedin'] = '1';
		} else {
			$data['loggedin'] = '0';
		}
			$data['contact_mode'] = $this->config->get('module_purpletree_multivendor_seller_contact');
		//}
		if($this->config->get('module_purpletree_multivendor_seller_contact')==1){
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('extension/account/purpletree_multivendor/sellercontact/customerReply', 'id='.$this->request->get['id'], true);
				$this->response->redirect($this->url->link('account/login', '', true));
			}
		}
		$this->load->model('extension/purpletree_multivendor/sellercontact');
		if(isset($this->request->get['seller_id'])) {
			$seller_id = $this->request->get['seller_id'];
		} elseif(isset($this->request->get['id'])) {
			$seller_id = $this->model_extension_purpletree_multivendor_sellercontact->getSellerId($this->request->get['id']);
		} else {
			$this->response->redirect($this->url->link('account/account','',true));	
		}
		$customerid = $this->customer->getId();
			if($customerid == $seller_id) {
				$this->response->redirect($this->url->link('account/account', '', true));
			}
		
		$this->load->language('purpletree_multivendor/sellercontact');

		$this->document->setTitle($this->language->get('heading_title'));

	if (!$this->customer->isLogged()) {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$sellerr = $this->model_extension_purpletree_multivendor_sellercontact->getCustomer($seller_id);
			$dataa = array(
					'customer_id' 	 => $customerid,
					'seller_id'		 => $seller_id,
					'customer_name'  => $this->request->post['customer_name'],
					'customer_email'  => $this->request->post['customer_email'],
					'customer_message'  => $this->request->post['customer_message'],
					'contact_from'   => 0
					);
			$this->model_extension_purpletree_multivendor_sellercontact->addContact($dataa);
			$ptsmv_current_page='';
			$message = $this->request->post['customer_message'];
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($sellerr['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($sellerr['firstname'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $sellerr['firstname']), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($message);
			$mail->send();

			$this->session->data['success'] = $this->language->get('text_success');
			
		} else {
					if (isset($this->request->post['customer_message'])) {
			$data['customer_message'] = $this->request->post['customer_message'];
		} else {
			$data['customer_message'] = '';
		}
				if (isset($this->request->post['customer_name'])) {
			$data['customer_name'] = $this->request->post['customer_name'];
		} else {
			$data['customer_name'] = '';
		}

		if (isset($this->request->post['customer_email'])) {
			$data['customer_email'] = $this->request->post['customer_email'];
		} else {
			$data['customer_email'] = '';
		}
		}

	} else {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatemessage()) {
			//$seller_id = $this->request->post['seller_id'];
			$sellerr = $this->model_extension_purpletree_multivendor_sellercontact->getCustomer($seller_id);
			$customerrr = $this->model_extension_purpletree_multivendor_sellercontact->getCustomer($customerid);
			$dataa = array(
					'customer_id' 	 => $customerid,
					'seller_id'		 => $seller_id,
					'customer_name'  => $customerrr['firstname'].' '. $customerrr['lastname'],
					'customer_email'  => $customerrr['email'],
					'customer_message'  => $this->request->post['customer_message'],
					'contact_from'   => 0
					);
			$this->model_extension_purpletree_multivendor_sellercontact->addContact($dataa);
			$ptsmv_current_page='';
			$message = $this->request->post['customer_message'];
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($sellerr['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($sellerr['firstname'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $sellerr['firstname']), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($message);
			$mail->send();

			$this->session->data['success'] = $this->language->get('text_success');
			
		} else {
					if (isset($this->request->post['customer_message'])) {
			$data['customer_message'] = $this->request->post['customer_message'];
		} else {
			$data['customer_message'] = '';
		}
		}
	}
				
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home','',true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellercontact/customercontactlist','',true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_enquiry'] = $this->language->get('entry_enquiry');

		if (isset($this->error['error_warning'])) {
			$data['error_warning'] = $this->error['error_warning'];
		} else {
			$data['error_warning'] = '';
		}
	if (isset($this->error['customer_message'])) {
			$data['error_enquiry'] = $this->error['customer_message'];
		} else {
			$data['error_enquiry'] = '';
		}
				if (isset($this->error['customer_name'])) {
			$data['error_name'] = $this->error['customer_name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['customer_email'])) {
			$data['error_email'] = $this->error['customer_email'];
		} else {
			$data['error_email'] = '';
		}
		$data['button_submit'] = $this->language->get('button_submit');
			$data['seller_id']=$seller_id;
			$data['sellercontacts'] = array();
	if ($this->customer->isLogged()) {
			$customer_id = $customerid;
			$results2 	= $this->model_extension_purpletree_multivendor_sellercontact->getSellerContactCustomerschat11($seller_id,$customer_id);
			$message = array();
			$contact_from = array();
			$date_added = array();
			if(!empty($results2)) {
				foreach($results2 as $result){
				$data['sellercontacts'][] = array(
				'contact_from'     => $result['contact_from'],
				'customer_id'     => $result['customer_id'],
				'customer_name'     => $result['customer_name'],
				'customer_email'     => $result['customer_email'],
				'customer_messages'       =>  nl2br($result['customer_message']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['created_at']))
			);
				}
			}
	}

		// Captcha
		$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'), $this->error);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/purpletree_multivendor/seller_reply', $data));
	}
	
}
