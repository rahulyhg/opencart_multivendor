<?php 
class ControllerExtensionAccountPurpletreeMultivendorSellerstore extends Controller{
	private $error = array();
	
	public function index(){
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$store_detail = $this->customer->isSeller();
		if(!isset($store_detail['store_status'])){
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		
		$this->load->language('purpletree_multivendor/sellerstore');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/purpletree_style.js');
		
		$this->load->model('extension/purpletree_multivendor/vendor');
		
		$store_detail = $this->customer->isSeller();
		
		$store_id = (isset($store_detail['id'])?$store_detail['id']:'');
		
		if (!isset($store_detail['store_status'])) {
			$this->response->redirect($this->url->link('account/account', '', true));
		} elseif(isset($store_detail['store_status'])){
			if(!$store_detail['store_status']){
				$this->response->redirect($this->url->link('account/account', '', true));
			}
		}
		  
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$path = 'admin/ptsseller/';
			$file = "";
					if (!is_dir($path)) {
						@mkdir($path, 0777);
					}
					if(is_dir($path)){
                        
                        $allowed_file=array('gif','png','jpg','pdf','doc','docx','zip');
                        $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($_FILES['upload_file']['name'], ENT_QUOTES, 'UTF-8')));
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    if($filename != '') {
                        if(in_array($extension,$allowed_file) ) {
                            $file = md5(mt_rand()).'-'.$filename;
                            $directory  = $path;
                        
                            move_uploaded_file($_FILES['upload_file']['tmp_name'], $directory.'/'.$file);
                        }     
                    }
                   
                                
                    }
			
			$this->model_extension_purpletree_multivendor_vendor->editStore($store_id, $this->request->post,$file);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/account/purpletree_multivendor/sellerstore','',true));
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home','',true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_store'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_edit'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_no_file'] = $this->language->get('text_no_file');

		$data['entry_storename'] = $this->language->get('entry_storename');
		$data['entry_storeemail'] = $this->language->get('entry_storeemail');
		$data['entry_storephone'] = $this->language->get('entry_storephone');
		$data['entry_storelogo'] = $this->language->get('entry_storelogo');
		$data['entry_storebanner'] = $this->language->get('entry_storebanner');
		$data['entry_storebanner_desc'] = $this->language->get('entry_storebanner_desc');
		$data['entry_storestatus'] = $this->language->get('entry_storestatus');
		$data['entry_storeaddress'] = $this->language->get('entry_storeaddress');
		$data['entry_storecity'] = $this->language->get('entry_storecity');
		$data['entry_storepostcode'] = $this->language->get('entry_storepostcode');
		$data['entry_storecountry'] = $this->language->get('entry_storecountry');
		$data['entry_storezone'] = $this->language->get('entry_storezone');
		$data['entry_storedescription'] = $this->language->get('entry_storedescription');
		$data['entry_storeshippingpolicy'] = $this->language->get('entry_storeshippingpolicy');
		$data['entry_storereturn'] = $this->language->get('entry_storereturn');
		$data['entry_storemetakeyword'] = $this->language->get('entry_storemetakeyword');
		$data['entry_storemetadescription'] = $this->language->get('entry_storemetadescription');
		$data['entry_storebankdetail'] = $this->language->get('entry_storebankdetail');
		$data['entry_storetin'] = $this->language->get('entry_storetin');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_storestatus'] = $this->language->get('entry_storestatus');
		
		$data['entry_storeseo'] = $this->language->get('entry_storeseo');
		
		$data['button_continue'] = $this->language->get('button_save');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($store_id)) {
			$data['store_id'] = $store_id;
		} else {
			$data['store_id'] = 0;
		}
		
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
		
		if (isset($this->error['store_name'])) {
			$data['error_storename'] = $this->error['store_name'];
		} else {
			$data['error_storename'] = '';
		}
		
		if (isset($this->error['store_seo'])) {
			$data['error_storeseo'] = $this->error['store_seo'];
		} else {
			$data['error_storeseo'] = '';
		}
		if (isset($this->error['error_file_upload'])) {
			$data['error_file_upload'] = $this->error['error_file_upload'];
		} else {
			$data['error_file_upload'] = '';
		}
		
		if (isset($this->error['store_email'])) {
			$data['error_storeemail'] = $this->error['store_email'];
		} else {
			$data['error_storeemail'] = '';
		}
		
		if (isset($this->error['store_phone'])) {
			$data['error_storephone'] = $this->error['store_phone'];
		} else {
			$data['error_storephone'] = '';
		}
				
		if (isset($this->error['store_address'])) {
			$data['error_storeaddress'] = $this->error['store_address'];
		} else {
			$data['error_storeaddress'] = '';
		}
		
		if (isset($this->error['store_city'])) {
			$data['error_storecity'] = $this->error['store_city'];
		} else {
			$data['error_storecity'] = '';
		}
		
		if (isset($this->error['store_country'])) {
			$data['error_storecountry'] = $this->error['store_country'];
		} else {
			$data['error_storecountry'] = '';
		}
		
		if (isset($this->error['error_storezone'])) {
			$data['error_storezone'] = $this->error['error_storezone'];
		} else {
			$data['error_storezone'] = '';
		}
		
		if (isset($this->error['store_zipcode'])) {
			$data['error_storezipcode'] = $this->error['store_zipcode'];
		} else {
			$data['error_storezipcode'] = '';
		}
		
		if (isset($this->error['store_shipping'])) {
			$data['error_storeshipping'] = $this->error['store_shipping'];
		} else {
			$data['error_storeshipping'] = '';
		}
		
		if (isset($this->error['store_return'])) {
			$data['error_storereturn'] = $this->error['store_return'];
		} else {
			$data['error_storereturn'] = '';
		}
		
		if (isset($this->error['store_meta_keywords'])) {
			$data['error_storemetakeyword'] = $this->error['store_meta_keywords'];
		} else {
			$data['error_storemetakeyword'] = '';
		}
		
		if (isset($this->error['store_meta_description'])) {
			$data['error_storemetadescription'] = $this->error['store_meta_description'];
		} else {
			$data['error_storemetadescription'] = '';
		}
		
		if (isset($this->error['store_bank_details'])) {
			$data['error_storebankdetail'] = $this->error['store_bank_details'];
		} else {
			$data['error_storebankdetail'] = '';
		}
		
		if (isset($this->error['store_tin'])) {
			$data['error_storetin'] = $this->error['store_tin'];
		} else {
			$data['error_storetin'] = '';
		}

		$data['action'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true);

		if (isset($store_id) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$seller_info = $this->model_extension_purpletree_multivendor_vendor->getStore($store_id);
		}
		
		if (!empty($seller_info)) {
			$data['seller_id'] = $seller_info['seller_id'];
		} else {
			$data['seller_id'] = $this->request->post['seller_id'];
		}
		
		if (isset($this->request->post['seller_name'])) { 
			$data['seller_name'] = $this->request->post['seller_name'];
		} elseif (!empty($seller_info)) { 
			$data['seller_name'] = $seller_info['seller_name'];
		} else { 
			$data['seller_name'] = '';
		}
	

		if (isset($this->request->post['store_seo'])) { 
			$data['store_seo'] = $this->request->post['store_seo'];
		} elseif (!empty($seller_info)) { 
			$data['store_seo'] = $seller_info['store_seo'];
		} else { 
			$data['store_seo'] = '';
		}
		
		if (isset($this->request->post['store_name'])) {
			$data['store_name'] = $this->request->post['store_name'];
		} elseif (!empty($seller_info)) {
			$data['store_name'] = $seller_info['store_name'];
		} else {
			$data['store_name'] = '';
		}
		

		if (isset($this->request->post['store_email'])) {
			$data['store_email'] = $this->request->post['store_email'];
		} elseif (!empty($seller_info)) {
			$data['store_email'] = $seller_info['store_email'];
		} else {
			$data['store_email'] = '';
		}
		
		if (isset($this->request->post['store_phone'])) {
			$data['store_phone'] = $this->request->post['store_phone'];
		} elseif (!empty($seller_info)) {
			$data['store_phone'] = $seller_info['store_phone'];
		} else {
			$data['store_phone'] = '';
		}
		
		if (isset($this->request->post['store_description'])) {
			$data['store_description'] = $this->request->post['store_description'];
		} elseif (!empty($seller_info)) {
			$data['store_description'] = $seller_info['store_description'];
		} else {
			$data['store_description'] = '';
		}
		
		if (isset($this->request->post['store_address'])) {
			$data['store_address'] = $this->request->post['store_address'];
		} elseif (!empty($seller_info)) {
			$data['store_address'] = $seller_info['store_address'];
		} else {
			$data['store_address'] = '';
		}
		
		if (isset($this->request->post['store_country'])) {
			$data['store_country'] = $this->request->post['store_country'];
		} elseif (!empty($seller_info)) {
			$data['store_country'] = $seller_info['store_country'];
		} else {
			$data['store_country'] = '';
		}
		
		if (isset($this->request->post['store_state'])) {
			$data['store_state'] = $this->request->post['store_state'];
		} elseif (!empty($seller_info)) {
			$data['store_state'] = $seller_info['store_state'];
		} else {
			$data['store_state'] = '';
		}
		
		if (isset($this->request->post['store_city'])) {
			$data['store_city'] = $this->request->post['store_city'];
		} elseif (!empty($seller_info)) {
			$data['store_city'] = $seller_info['store_city'];
		} else {
			$data['store_city'] = '';
		}
		
		if (isset($this->request->post['store_zipcode'])) {
			$data['store_zipcode'] = $this->request->post['store_zipcode'];
		} elseif (!empty($seller_info)) {
			$data['store_zipcode'] = $seller_info['store_zipcode'];
		} else {
			$data['store_zipcode'] = '';
		}
		
		if (isset($this->request->post['store_shipping_policy'])) {
			$data['store_shipping_policy'] = $this->request->post['store_shipping_policy'];
		} elseif (!empty($seller_info)) {
			$data['store_shipping_policy'] = $seller_info['store_shipping_policy'];
		} else {
			$data['store_shipping_policy'] = '';
		}
		
		if (isset($this->request->post['store_return_policy'])) {
			$data['store_return_policy'] = $this->request->post['store_return_policy'];
		} elseif (!empty($seller_info)) {
			$data['store_return_policy'] = $seller_info['store_return_policy'];
		} else {
			$data['store_return_policy'] = '';
		}
		
		if (isset($this->request->post['store_meta_keywords'])) {
			$data['store_meta_keywords'] = $this->request->post['store_meta_keywords'];
		} elseif (!empty($seller_info)) {
			$data['store_meta_keywords'] = $seller_info['store_meta_keywords'];
		} else {
			$data['store_meta_keywords'] = '';
		}
		
		if (isset($this->request->post['store_meta_description'])) {
			$data['store_meta_description'] = $this->request->post['store_meta_description'];
		} elseif (!empty($seller_info)) {
			$data['store_meta_description'] = $seller_info['store_meta_descriptions'];
		} else {
			$data['store_meta_description'] = '';
		}
		
		if (isset($this->request->post['store_bank_details'])) {
			$data['store_bank_details'] = $this->request->post['store_bank_details'];
		} elseif (!empty($seller_info)) {
			$data['store_bank_details'] = $seller_info['store_bank_details'];
		} else {
			$data['store_bank_details'] = '';
		}
		
		if (isset($this->request->post['store_tin'])) {
			$data['store_tin'] = $this->request->post['store_tin'];
		} elseif (!empty($seller_info)) {
			$data['store_tin'] = $seller_info['store_tin'];
		} else {
			$data['store_tin'] = '';
		}

		
		if (isset($this->request->post['store_status'])) {
			$data['store_status'] = $this->request->post['store_status'];
		} elseif (!empty($seller_info)) {
			$data['store_status'] = $seller_info['store_status'];
		} else {
			$data['store_status'] = '';
		}

		if (isset($this->request->post['store_logo'])) {
			$data['store_logo'] = $this->request->post['store_logo'];
		} elseif (!empty($seller_info)) {
			$data['store_logo'] = $seller_info['store_logo'];
		} else {
			$data['store_logo'] = '';
		}


		$this->load->model('tool/image');

		if (isset($this->request->post['store_logo']) && is_file(DIR_IMAGE . $this->request->post['store_logo'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['store_logo'], 100, 100);
		} elseif (!empty($seller_info) && is_file(DIR_IMAGE . $seller_info['store_logo'])) {
			$data['thumb'] = $this->model_tool_image->resize($seller_info['store_logo'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['store_banner'])) {
			$data['store_banner'] = $this->request->post['store_banner'];
		} elseif (!empty($seller_info)) {
			$data['store_banner'] = $seller_info['store_banner'];
		} else {
			$data['store_banner'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['store_banner']) && is_file(DIR_IMAGE . $this->request->post['store_banner'])) {
			$data['banner_thumb'] = $this->model_tool_image->resize($this->request->post['store_banner'], 100, 100);
		} elseif (!empty($seller_info) && is_file(DIR_IMAGE . $seller_info['store_banner'])) {
			$data['banner_thumb'] = $this->model_tool_image->resize($seller_info['store_banner'], 100, 100);
		} else {
			$data['banner_thumb'] = $this->model_tool_image->resize('catalog/purpletree_banner.jpg', 100, 100);
		}
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$data['back'] = $this->url->link('account/account', '', true);

		if(!empty($seller_info['document'])){
				$data['upload_file_existing'] = $seller_info['document'];
				$data['upload_file_existing_href'] = "admin/ptsseller/".$seller_info['document'];
			}      
                    
		
		// End download document file of store

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/purpletree_multivendor/seller_store', $data));
	}

	public function downloadAttachment()
	{
		
		$file="ptsseller/".$this->request->get["document"]; //file location 
		
        if(file_exists($file)) {

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
		
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit();
	}
	}	
	
	public function becomeseller(){
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/purpletree_multivendor/becomeseller', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$this->load->language('purpletree_multivendor/sellerstore');
		
		$this->document->setTitle($this->language->get('heading_become_title'));
		
		$this->load->model('extension/purpletree_multivendor/vendor');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSeller()) {

			$file ='';
			$store_id = $this->model_extension_purpletree_multivendor_vendor->becomeSeller($this->customer->getId(), $this->request->post,$file);
			////// Start register mail for seller////////////
		
			$this->load->language('mail/register');
		    $this->load->language('account/ptsregister');
			$data['text_welcome'] = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$data['text_login'] = $this->language->get('text_login');
			$data['text_approval'] = $this->language->get('text_approval');
			$data['text_service'] = $this->language->get('text_service');
			$data['text_thanks'] = $this->language->get('text_thanks');
			  $this->load->model('account/customer'); 
               $this->load->model('account/customer_group');
				$datacust = $this->model_account_customer->getCustomer($this->customer->getId());
			if (isset($datacust['customer_group_id'])) {
				$customer_group_id = $datacust['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			$data['text_admin'] ="";
			if($this->config->get('module_purpletree_multivendor_seller_approval') == 1){
				$data['text_admin'] = $this->language->get('text_admin');
			}
						
			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
			
			if ($customer_group_info) {
				$data['approval'] = $customer_group_info['approval'];
			} else {
				$data['approval'] = '';
			}
				
			$data['login'] = $this->url->link('account/login', '', true);		
			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($datacust['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(sprintf($this->language->get('text_subject_seller'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
			$mail->setText($this->load->view('account/purpletree_multivendor/register_mail', $data));
			$mail->send();
		
		//////End register mail for seller////////////
		// Send to main admin email if new account email is enabled
		if (in_array('account', (array)$this->config->get('config_mail_alert'))) {

			$this->load->language('mail/register');
		    /////// Start alert mail for admin///////////
			
			$this->load->language('account/ptsregister');
			
			$data['text_signup_seller'] = $this->language->get('text_signup_seller');
			$data['text_firstname'] = $this->language->get('text_firstname');
			$data['text_lastname'] = $this->language->get('text_lastname');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_telephone'] = $this->language->get('text_telephone');
			
			$data['firstname'] = $datacust['firstname'];
			$data['lastname'] = $datacust['lastname'];
			
			$this->load->model('account/customer_group');
			
			if (isset($datacust['customer_group_id'])) {
				$customer_group_id = $datacust['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
			
			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}
			
			$data['email'] = $datacust['email'];
			$data['telephone'] = $datacust['telephone'];

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_Seller'), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->load->view('account/purpletree_multivendor/register_alertmail', $data));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails1 = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails1 as $email1) {
				if (utf8_strlen($email1) > 0 && filter_var($email1, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email1);
					$mail->send();
				}
			}
			}
		  /////// End alert mail for admin///////////
			if($store_id){
				if($this->config->get('module_purpletree_multivendor_seller_approval')){
					$this->session->data['success'] = $this->language->get('text_approval');
					$this->response->redirect($this->url->link('account/account','',true));
				} else {
					$this->session->data['success'] = $this->language->get('text_seller_success');
					$this->response->redirect($this->url->link('account/account','',true));
				}
			} else {
				$this->response->redirect($this->url->link('account/account','',true));
			}
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home','',true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_store'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true)
		);
		$data['text_supported'] = $this->language->get('text_supported');
		$data['text_attachment'] = $this->language->get('text_attachment');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_seller'] = $this->language->get('text_seller');
		$data['text_seller_heading'] = $this->language->get('text_seller_heading');
		$data['text_store_name'] = $this->language->get('text_store_name');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_remove_msg'] = $this->language->get('text_remove_msg');
		
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		
		if (isset($this->request->post['become_seller'])) {
			$data['become_seller'] = $this->request->post['become_seller'];
		} 
		 else {
			$data['become_seller'] = '';
		}
		
		if (isset($this->request->post['seller_storename'])) {
			$data['seller_storename'] = $this->request->post['seller_storename'];
		} 
		 else {
			$data['seller_storename'] = '';
		}
		if (isset($this->error['seller_store'])) {
			$data['error_sellerstore'] = $this->error['seller_store'];
		} else {
			$data['error_sellerstore'] = '';
		}
		
		if (isset($this->error['error_warning'])) {
			$data['error_warning'] = $this->error['error_warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if(isset($this->error['warning1']))
		{
			
				$data['warning1'] = $this->error['warning1'];
		}
		else{
			
			$data['warning1']= '';
		}
		$isSeller = $this->customer->isSeller();
		if($isSeller){
			if($isSeller['is_removed']){
				$data['action'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/reseller', '', true);
				$data['is_removed'] = 1;
			}
		} else {
			$data['action'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/becomeseller', '', true);
			$data['is_removed'] = 0;
		}
		
		if (isset($this->request->post['become_seller'])) {
			$data['become_seller'] = $this->request->post['become_seller'];
		} else {
			$data['become_seller'] = '';
		}
		
		if (isset($this->request->post['store_name'])) {
			$data['store_name'] = $this->request->post['store_name'];
		} else {
			$data['store_name'] = '';
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');

		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/purpletree_multivendor/seller_form', $data));
	}
	
	public function reseller(){
		
		$this->load->language('purpletree_multivendor/sellerstore');
		
		$this->load->model('extension/purpletree_multivendor/vendor');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$store_id = $this->model_extension_purpletree_multivendor_vendor->reseller($this->customer->getId(), $this->request->post);
			if($store_id){
				if($this->config->get('module_purpletree_multivendor_seller_approval')){
					$this->session->data['success'] = $this->language->get('text_approval');
					$this->response->redirect($this->url->link('account/account','',true));		
				} else {
					$this->session->data['success'] = $this->language->get('text_seller_success');
					$this->response->redirect($this->url->link('extension/account/purpletree_multivendor/sellerstore','',true));
				}
			} else {
				$this->response->redirect($this->url->link('account/account','',true));
			}
		}
		
	}
	public function storeview(){
		$this->load->model('setting/extension');
    $installed_modules = $this->model_setting_extension->getExtensions('module');

			if(isset($this->session->data['seller_sto_page'])) {
				unset($this->session->data['seller_sto_page']);
			}
			$data['error_warning'] = '';

		$this->load->language('purpletree_multivendor/storeview');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/purpletree_multivendor/vendor');
		
		$this->load->model('extension/purpletree_multivendor/sellerproduct');
			if(array_search('journal2', array_column($installed_modules, 'code')) !== False) {
		
		 $this->load->model('journal2/product');
	}
		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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
		
		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}
		$category_id=0;
		if(!empty($this->request->get['category']))
		{
			$category_id=$this->request->get['category'];
		}
		
		$data['seller_products'] = array();
		
		$data['toatl_seller_products'] = array();
		
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_returnpolicy'] = $this->language->get('text_returnpolicy');
		$data['text_shippingpolicy'] = $this->language->get('text_shippingpolicy');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');
		$data['text_aboutstore'] = $this->language->get('text_aboutstore');
		$data['text_sellerreview'] = $this->language->get('text_sellerreview');
		$data['text_no_results'] = $this->language->get('text_empty');
		$data['text_sellercontact'] = $this->language->get('text_sellercontact');
		
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/purpletree/custom.css');
		
		if(isset($this->request->get['seller_store_id'])){
			$sellerstore = $this->request->get['seller_store_id'];
		} else {
			$sellerstore_d = $this->customer->isSeller();
			$sellerstore = $sellerstore_d['id'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home','',true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_dashboard'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_storeview'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview&seller_store_id='.$sellerstore, '', true)
		);

		$store_detail = $this->model_extension_purpletree_multivendor_vendor->getStore($sellerstore);

		if($store_detail  and ($store_detail['store_status']==1)){
			$seller_detailss = $this->model_extension_purpletree_multivendor_vendor->getStoreDetail($store_detail['seller_id']);

			$this->document->setTitle($store_detail['store_name']);
			$this->document->setDescription($store_detail['store_meta_descriptions']);
			$this->document->setKeywords($store_detail['store_meta_keywords']);
			
			$data['store_rating'] = $this->model_extension_purpletree_multivendor_vendor->getStoreRating($store_detail['seller_id']);
			
			$data['module_purpletree_multivendor_store_email'] = $this->config->get('module_purpletree_multivendor_store_email');
			$data['module_purpletree_multivendor_store_phone'] = $this->config->get('module_purpletree_multivendor_store_phone');
			$data['module_purpletree_multivendor_store_address'] = $this->config->get('module_purpletree_multivendor_store_address');
		
			$data['store_name'] = $store_detail['store_name'];
			$data['seller_name'] = $store_detail['seller_name'];
			$data['store_email'] = $store_detail['store_email'];
			$data['store_phone'] = $store_detail['store_phone'];
			$data['store_tin'] = $store_detail['store_tin'];
			$data['store_zipcode'] = $store_detail['store_zipcode'];
			$data['store_description'] = html_entity_decode($store_detail['store_description'], ENT_QUOTES, 'UTF-8');
			$data['store_address'] = html_entity_decode($store_detail['store_address'], ENT_QUOTES, 'UTF-8');
			
			$data['seller_review_status'] = $this->config->get('module_purpletree_multivendor_seller_review');
			$data['store_review'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/sellerreview','seller_id=' . $store_detail['seller_id'], true);
			
			$data['store_shipping_policy'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/storedesc','path=shippingpolicy'. '&seller_store_id=' . $store_detail['id'], true);
			
			$data['store_return_policy'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/storedesc','path=returnpolicy'. '&seller_store_id=' . $store_detail['id'], true);
			
			$data['store_about'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/storedesc','path=aboutstore'. '&seller_store_id=' . $store_detail['id'], true);
			
			$data['seller_contact'] = $this->url->link('extension/account/purpletree_multivendor/sellercontact/customerReply','seller_id=' . $store_detail['seller_id'], true);
			
			
			$this->load->model('tool/image');
			
			if (is_file(DIR_IMAGE . $store_detail['store_logo'])) {
				$data['store_logo'] = $this->model_tool_image->resize($store_detail['store_logo'], 150, 150);
			} else {
				$data['store_logo'] = $this->model_tool_image->resize('no_image.png', 150, 150);
			}
			
			if (is_file(DIR_IMAGE . $store_detail['store_banner'])) {
				$data['store_banner'] = $this->model_tool_image->resize($store_detail['store_banner'], 900, 300);
			} else {
				$data['store_banner'] = $this->model_tool_image->resize('catalog/purpletree_banner.jpg', 900, 300);
			}

		$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
		

		$store_detail = array(
			'seller_id' => $store_detail['seller_id'],
			'category_id' => $category_id,
			'filter_filter'      => $filter,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit,
			'status'              => 1,
			'is_approved'              => 1
		);
			
		$store_detail['status'] = 1;
		$store_detail['is_approved'] = 1;
		$seller_products = $this->model_extension_purpletree_multivendor_sellerproduct->getSellerProducts($store_detail);
		$toatl_seller_products = $this->model_extension_purpletree_multivendor_sellerproduct->getTotalSellerProducts($store_detail);
		if($seller_products){
			$countttt = 0;
			foreach($seller_products as $seller_product){

				if ($seller_product['image'] && is_file(DIR_IMAGE . $seller_product['image'])) {
					$image = $this->model_tool_image->resize($seller_product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}
				
				$price = $this->currency->format($this->tax->calculate($seller_product['price'], $seller_product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				
				$product_specials = $this->model_extension_purpletree_multivendor_sellerproduct->getProductSpecials($seller_product['product_id']);
				
				$special = false;
				
				foreach ($product_specials  as $product_special) {
					if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
						$special = $this->currency->format($this->tax->calculate($product_special['price'], $seller_product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						break;
					}
				}
                $image2 = false;
				 $date_end = false;	if(array_search('journal2', array_column($installed_modules, 'code')) !== False) {
		
	
				     if (strpos($this->config->get('config_template'), 'journal2') === 0 && $special && $this->journal2->settings->get('show_countdown', 'never') !== 'never') {
                    $this->load->model('journal2/product');
                    $date_end = $this->model_journal2_product->getSpecialCountdown($seller_product['product_id']);
                    if ($date_end === '0000-00-00') {
                        $date_end = false;
                    }
                }
				  
                    $this->load->model('catalog/product');
				
                $additional_images = $this->model_catalog_product->getProductImages($seller_product['product_id']);


                if (count($additional_images) > 0) {
                    $image2 = $this->model_tool_image->resize($additional_images[0]['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                }
				 }
				$data['seller_products'][] = array(
					'href'  => $this->url->link('product/product', 'product_id=' . $seller_product['product_id'],true),
						'thumb'       => $image,
					   'thumb2'       => $image2,
            

                'labels'        => 	(array_search('journal2', array_column($installed_modules, 'code')) !== False)?$this->model_journal2_product->getLabels($seller_product['product_id']):'',
					'product_id' => $seller_product['product_id'],
					'name' => $seller_product['name'],
					'price' => $price,
					'countttt' => $countttt,
					'image' => $image,
					 'date_end'       => $date_end,
					'special'    => $special,
					'rating'      => $seller_product['rating'],
					'minimum'     => $seller_product['minimum'] > 0 ? $seller_product['minimum'] : 1,
					'description' => utf8_substr(strip_tags(html_entity_decode($seller_product['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length'))  . '..'
				);
				$countttt++;
			}
		}
		
		$url = '';

		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
			
		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview','&sort=p.sort_order&order=ASC' . $url.'&seller_store_id='.$sellerstore,true)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', '&sort=pd.name&order=ASC' . $url.'&seller_store_id='.$sellerstore,true)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', '&sort=pd.name&order=DESC' . $url.'&seller_store_id='.$sellerstore,true)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview',  '&sort=p.price&order=ASC' . $url.'&seller_store_id='.$sellerstore,true)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', '&sort=p.price&order=DESC' . $url.'&seller_store_id='.$sellerstore,true)
		);
		
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', '&sort=p.model&order=ASC' . $url.'&seller_store_id='.$sellerstore,true)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', '&sort=p.model&order=DESC' . $url.'&seller_store_id='.$sellerstore,true)
			);
			
			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

		
			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', $url . '&limit=' . $value.'&seller_store_id='.$sellerstore,true)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}
			if(! empty($category_id))
			{
				$url.= '&category=' .$category_id;
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			$url .= '&seller_store_id='.$sellerstore;
			$pagination = new Pagination();
			$pagination->total = $toatl_seller_products;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', $url . '&page={page}',true);

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($toatl_seller_products) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($toatl_seller_products - $limit)) ? $toatl_seller_products : ((($page - 1) * $limit) + $limit), $toatl_seller_products, ceil($toatl_seller_products / $limit));

			if ($page == 1) {
			    $this->document->addLink($this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($toatl_seller_products / $limit) > $page) {
			    $this->document->addLink($this->url->link('extension/account/purpletree_multivendor/sellerstore/storeview', '&page='. ($page + 1), true), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;
			
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		
		
		$this->response->setOutput($this->load->view('account/purpletree_multivendor/storeview', $data));
		} else {

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore','',true)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home','',true);

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
	
	public function storedesc() { 
		$this->load->language('purpletree_multivendor/storeview');
		
		$this->load->model('extension/purpletree_multivendor/vendor');

		if (isset($this->request->get['seller_store_id'])) {
			$store_id = (int)$this->request->get['seller_store_id'];
		} else {
			$store_id = 0;
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home','',true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_dashboard'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true)
		);


		$store_info = $this->model_extension_purpletree_multivendor_vendor->getStore($store_id);

		if ($store_info) {
			if($this->request->get['path']=="shippingpolicy"){
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_shippingpolicy'),
					'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storedesc&path='.$this->request->get['path'].'&seller_store_id='.$store_id, '', true)
				);
		
				$this->document->setTitle($this->language->get('text_shippingpolicy'));
				$data['text_policy'] = $this->language->get('text_shippingpolicy');
				$data['store_policy'] = html_entity_decode($store_info['store_shipping_policy'], ENT_QUOTES, 'UTF-8') . "\n";
			} elseif($this->request->get['path']=="returnpolicy"){
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_returnpolicy'),
					'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storedesc&path='.$this->request->get['path'].'&seller_store_id='.$store_id, '', true)
				);
				$this->document->setTitle($this->language->get('text_returnpolicy'));
				$data['text_policy'] = $this->language->get('text_returnpolicy');
				$data['store_policy'] = html_entity_decode($store_info['store_return_policy'], ENT_QUOTES, 'UTF-8') . "\n";
			} elseif($this->request->get['path']=="aboutstore"){
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_aboutstore'),
					'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore/storedesc&path='.$this->request->get['path'].'&seller_store_id='.$store_id, '', true)
				);
				$this->document->setTitle($this->language->get('text_aboutstore'));
				$data['text_policy'] = $this->language->get('text_aboutstore');
				$data['store_policy'] = html_entity_decode($store_info['store_description'], ENT_QUOTES, 'UTF-8') . "\n";
			}
		}

		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/purpletree_multivendor/policy', $data));
	}
	
	private function validateSeller(){		
		$this->load->model('extension/purpletree_multivendor/vendor');
	
		if($this->request->post['become_seller']){ 		
		if ((utf8_strlen(trim($this->request->post['seller_storename'])) < 5) || (utf8_strlen(trim($this->request->post['seller_storename'])) > 50)) {			
			$this->error['seller_store'] = $this->language->get('error_storename');		
		}
		
		$store_info1 = $this->model_extension_purpletree_multivendor_vendor->getStoreNameByStoreName($this->request->post['seller_storename']);	
		
        if ($store_info1 && (strtoupper(trim($this->request->post['seller_storename']))==strtoupper($store_info1['store_name']))) {
			
				$this->error['seller_store'] = $this->language->get('error_exist_storename');
				$this->error['warning'] = $this->language->get('error_warning');
		}
		
			
			if(!empty($this->request->files['upload_file']['name']))
			{
				$allowed_file=array('gif','png','jpg','pdf','doc','docx','zip');
				$filename = $this->request->files['upload_file']['name'];
				
				$extension = pathinfo($filename,PATHINFO_EXTENSION);
				
				if(!in_array($extension ,$allowed_file)) {
					
					$this->error['warning1'] = $this->language->get('error_supported_file');
				}
			}
		
		}
		return !$this->error;
	}
	
	private function validateForm(){
		
		$seller_seo = $this->model_extension_purpletree_multivendor_vendor->getStoreSeo($this->request->post['store_seo']);
		
		$store_info = $this->model_extension_purpletree_multivendor_vendor->getStoreByEmail($this->request->post['store_email']);

		$pattern = '/[\'\/~`\!@#\$%\^&\*\(\)\+=\{\}\[\]\|;:"\<\>,\.\?\\\ ]/';
		if (preg_match($pattern, $this->request->post['store_seo'])==true) {
			$this->error['store_seo'] = $this->language->get('error_store_seo');
		} elseif ((utf8_strlen($this->request->post['store_seo']) < 3) || (utf8_strlen(trim($this->request->post['store_seo'])) > 150)) {
			$this->error['store_seo'] = $this->language->get('error_storeseoempty');
		} elseif(isset($store_info['id'])){
			$seller_seot = "seller_store_id=".$store_info['id'];
			if(isset($seller_seo['query'])){
				if($seller_seo['query']!=$seller_seot){
					$this->error['store_seo'] = $this->language->get('error_storeseo');
				}
			}
		}
		if(!empty($_FILES['upload_file']['name'])) {
		 $allowed_file=array('gif','png','jpg','pdf','doc','docx','zip');
                        $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($_FILES['upload_file']['name'], ENT_QUOTES, 'UTF-8')));
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
					 if(!in_array($extension,$allowed_file) ) {
						$this->error['error_file_upload'] = $this->language->get('error_supported_file');
					 }
		}
		if ((utf8_strlen(trim($this->request->post['store_name'])) < 5) || (utf8_strlen(trim($this->request->post['store_name'])) > 50)) {
			$this->error['store_name'] = $this->language->get('error_storename');
		}
		$store_info1 = $this->model_extension_purpletree_multivendor_vendor->getStoreNameByStoreName($this->request->post['store_name']);

		$store_detail = $this->customer->isSeller();
		
		if (isset($store_detail['id'])) {
			if ($store_info1 && ($store_detail['id'] != $store_info1['id'] && strtoupper(trim($this->request->post['store_name']))==strtoupper($store_info1['store_name']))) {
				$this->error['store_name'] = $this->language->get('error_exist_storename');
				$this->error['warning'] = $this->language->get('error_warning');
		}
		}
		$EMAIL_REGEX='/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
		
		if (preg_match($EMAIL_REGEX, $this->request->post['store_email'])==false)	
		{
			$this->error['store_email'] = $this->language->get('error_storeemail');
		}
		$store_detail = $this->customer->isSeller();
		
		if (!isset($store_info['id'])) {
			if ($store_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else { 
			if ($store_info && ($store_detail['id'] != $store_info['id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
		if(trim($this->request->post['store_phone']) < 1){
			if ((utf8_strlen(trim($this->request->post['store_phone'])) < 10) || (utf8_strlen(trim($this->request->post['store_phone'])) > 12)) {
					$this->error['store_phone'] = $this->language->get('error_storephone');
			}
		}
		
		if ((utf8_strlen(trim($this->request->post['store_address'])) < 5) || (utf8_strlen(trim($this->request->post['store_address'])) > 101)) {
			$this->error['store_address'] = $this->language->get('error_storeaddress');
		}
		
		if ((utf8_strlen(trim($this->request->post['store_city'])) < 3) || (utf8_strlen(trim($this->request->post['store_city'])) > 50)) {
			$this->error['store_city'] = $this->language->get('error_storecity');
		}
		
		if (empty($this->request->post['store_country'])) {
			$this->error['store_country'] = $this->language->get('error_storecountry');
		}
		
		if (empty($this->request->post['store_state'])) {
			$this->error['error_storezone'] = $this->language->get('error_storezone');
		}
		
		if(trim($this->request->post['store_zipcode']) >= 1){
			if ((utf8_strlen(trim($this->request->post['store_zipcode'])) < 3) || (utf8_strlen(trim($this->request->post['store_zipcode'])) > 12)) {
				$this->error['store_zipcode'] = $this->language->get('error_storepostcode');
			}
		}
		
		if ((utf8_strlen(trim($this->request->post['store_meta_keywords'])) =='') ) {
			$this->error['store_meta_keywords'] = $this->language->get('error_storemetakeywords');
		}
		
		if ((utf8_strlen(trim($this->request->post['store_meta_description']))=='') ) {
			$this->error['store_meta_description'] = $this->language->get('error_storemetadescription');
		}
		
		if ((utf8_strlen(trim($this->request->post['store_bank_details'])) =='') ) {
			$this->error['store_bank_details'] = $this->language->get('error_storebankdetail');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
	
	public function removeseller(){
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/purpletree_multivendor/sellerstore/removeseller', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
				if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('extension/account/purpletree_multivendor/removeseller', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$store_detail = $this->customer->isSeller();
		if(!isset($store_detail['store_status'])){
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$this->load->language('purpletree_multivendor/storeview');
		
		$seller_id = $this->customer->getId();
		
		$this->load->model('extension/purpletree_multivendor/vendor');
		
		$result = $this->model_extension_purpletree_multivendor_vendor->removeSeller($seller_id);
		
		$this->session->data['success'] = $this->language->get('text_remove_account_success');
		
		$this->response->redirect($this->url->link('account/account', '', true));
		
	}
	

	public function sellerreview() { 

		$data['customer_id'] = $this->customer->getId();
		
		$this->load->language('purpletree_multivendor/sellerreview');
		
		$this->load->model('extension/purpletree_multivendor/sellerreview');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateReview()) {
			
			$this->model_extension_purpletree_multivendor_sellerreview->addReview($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/account/purpletree_multivendor/sellerstore/sellerreview&seller_id='.$this->request->post['seller_id'],'',true));
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_limit_admin');
		}
		
		if (isset($this->request->get['seller_id'])) {
			$seller_id = (int)$this->request->get['seller_id'];
		} else {
			$seller_id = 0;
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home','',true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_dashboard'),
			'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore', '', true)
		);
		
		$this->document->setTitle($this->language->get('text_storereview'));
		
		$data['text_storereview'] = $this->language->get('text_storereview');
		$data['text_title'] = $this->language->get('text_title');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_rating'] = $this->language->get('text_rating');
		$data['text_empty_result'] = $this->language->get('text_empty_result');
		$data['text_heading'] = $this->language->get('text_heading');
		$data['text_note'] = $this->language->get('text_note');
		$data['entry_bad'] = $this->language->get('entry_bad');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['text_login'] = $this->language->get('text_login');
		$data['button_continue'] = $this->language->get('button_continue');
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->error['review_title'])) {
			$data['error_title'] = $this->error['review_title'];
		} else {
			$data['error_title'] = '';
		}
		
		if (isset($this->error['rating'])) {
			$data['error_rating'] = $this->error['rating'];
		} else {
			$data['error_rating'] = '';
		}
		
		if (isset($this->error['review_description'])) {
			$data['error_description'] = $this->error['review_description'];
		} else {
			$data['error_description'] = '';
		}
		if (isset($this->error['no_can_review'])) {
			$data['warning'] = $this->error['no_can_review'];
		} else {
			$data['warning'] = '';
		}
		
		if(isset($this->request->get['seller_id'])){
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_storereview'),
				'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore/sellerreview&seller_id='.$this->request->get['seller_id'], '', true)
			);
			$data['seller_id'] = $seller_id;
			$this->load->model('extension/purpletree_multivendor/sellerreview');
		if(!$this->model_extension_purpletree_multivendor_sellerreview->canReview($datasend = array('seller_id' =>$seller_id,'customer_id' =>$data['customer_id']))) {
				$data['warning'] = $this->language->get('no_can_review');
		}
			
			$data['action'] = $this->url->link('extension/account/purpletree_multivendor/sellerstore/sellerreview&seller_id='.$data['seller_id'],'',true);
			
			$filter_data = array(
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit,
				'seller_id' 		=> $seller_id,
				'customer_id'		=> $data['customer_id']
			);
				
			$review_total = $this->model_extension_purpletree_multivendor_sellerreview->getTotalSellerReview($filter_data);
			
			if (isset($this->request->post['review_title'])) { 
				$data['review_title'] = $this->request->post['review_title'];
			} else { 
				$data['review_title'] = '';
			}
			
			if (isset($this->request->post['review_description'])) { 
				$data['review_description'] = $this->request->post['review_description'];
			} else { 
				$data['review_description'] = '';
			}
			
			if (isset($this->request->post['seller_id'])) { 
				$data['seller_id'] = $this->request->post['seller_id'];
			} else { 
				$data['seller_id'] = (isset($this->request->get['seller_id'])?$this->request->get['seller_id']:'');
			}
			
			$results = $this->model_extension_purpletree_multivendor_sellerreview->getSellerReview($filter_data);
			
			$data['result_check'] = $this->model_extension_purpletree_multivendor_sellerreview->checkReview($filter_data);
			
			$data['reviews'] = array();
			if ($results) {
				foreach($results as $result){
					$data['reviews'][] = array(
						'customer_name'     => $result['customer_name'],
						'seller_id'     => $result['seller_id'],
						'review_title'     => $result['review_title'],
						'review_description'       => nl2br($result['review_description']),
						'rating'     => (int)$result['rating'],
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['created_at']))
					);
				}
			}
			
			$pagination = new Pagination();
			$pagination->total = $review_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/account/purpletree_multivendor/sellerstore/sellerreview', 'seller_id=' . $data['seller_id'] . '&page={page}',true);

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($review_total - $limit)) ? $review_total : ((($page - 1) * $limit) + $limit), $review_total, ceil($review_total / $limit));
				
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/purpletree_multivendor/review', $data));
		} else{
			if($this->customer->isSeller()){
				
			$seller_id = $this->customer->getId();
			
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_storereview'),
				'href' => $this->url->link('extension/account/purpletree_multivendor/sellerstore/sellerreview', '', true)
			);
			$filter_data = array(
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit,
				'seller_id' 		=> $seller_id,
				'shown'				=> '1'
			);
				
			$review_total = $this->model_extension_purpletree_multivendor_sellerreview->getTotalSellerReview($filter_data);

			$results = $this->model_extension_purpletree_multivendor_sellerreview->getSellerReview($filter_data);
			
			$data['reviews'] = array();
			
			if ($results) {
				foreach($results as $result){
					$data['reviews'][] = array(
						'customer_name'     => $result['customer_name'],
						'review_title'     => $result['review_title'],
						'review_description'       => nl2br($result['review_description']),
						'rating'     => (int)$result['rating'],
						'status'     => (($result['status'])?$this->language->get('text_approved'):$this->language->get('text_notapproved')),
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['created_at']))
					);
				}
			}
			
			$pagination = new Pagination();
			$pagination->total = $review_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/account/purpletree_multivendor/sellerstore/sellerreview', 'page={page}',true);

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($review_total - $limit)) ? $review_total : ((($page - 1) * $limit) + $limit), $review_total, ceil($review_total / $limit));
				
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/purpletree_multivendor/reviewlist', $data));
			}
		}
	}
	
	private function validateReview(){
		
		if ((utf8_strlen($this->request->post['review_title']) < 3) ) {
			$this->error['review_title'] = $this->language->get('error_title');
		}
		
		if ((empty($this->request->post['rating'])) ) {
			$this->error['rating'] = $this->language->get('error_rating');
		}
		
		if ((utf8_strlen($this->request->post['review_description']) < 5) ) {
			$this->error['review_description'] = $this->language->get('error_description_length');
		} elseif(empty($this->request->post['review_description'])){
			$this->error['review_description'] = $this->language->get('error_description');
		}
		
		$this->load->model('extension/purpletree_multivendor/sellerreview');
		
		if(!$this->model_extension_purpletree_multivendor_sellerreview->canReview($this->request->post)) {
				$this->error['no_can_review'] = $this->language->get('no_can_review');
		}
		
		return !$this->error;
	}

}
if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}
?>