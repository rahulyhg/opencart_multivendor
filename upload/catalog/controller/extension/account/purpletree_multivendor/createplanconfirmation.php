<?php
class ControllerExtensionAccountPurpletreeMultivendorCreateplanconfirmation extends Controller {
	private $error = array();
	
	public function index() {
		
		
	$data['heading_title'] = $this->language->get('heading_title');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
	

		$this->response->setOutput($this->load->view('account/purpletree_multivendor/create_plan_confirmation', $data));
	}
}