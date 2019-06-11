<?php 
class ModelExtensionPurpletreeMultivendorSellercontact extends Model{
	
	public function getSellerContactCustomerschat1($custid,$seller_id){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE seller_id = '".(int)$seller_id."' and customer_id='". (int)$custid ."'";
		
		$sql .=" ORDER BY created_at DESC";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getSellerContactCustomerschat($custid,$seller_id){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE seller_id = '".(int)$seller_id."' and customer_id='". (int)$custid ."'";
		
		$sql .=" ORDER BY created_at DESC LIMIT 0,1";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getSellerContactCustomerschat12255($id){
		$sql1 = "SELECT seller_id FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE id = '".(int)$id."'";
		
		$query1 = $this->db->query($sql1);

		if($query1->num_rows){
				return $query1->row['seller_id'];
		}
	}
	public function getSellerContactCustomerschat1225566($seller_id,$custid){

			$sql = "SELECT * FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE seller_id = '".(int)$seller_id."' and customer_id='". (int)$custid ."'";
			
			$sql .=" ORDER BY created_at ASC";
			$query = $this->db->query($sql);
			return $query->rows;
	}
	public function getSellerContactCustomerschat122($seller_id,$custid){
		
			$sql = "SELECT * FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE seller_id = '".(int)$seller_id."' and customer_id='". (int)$custid ."'";
			
			$sql .=" ORDER BY created_at ASC";
			$query = $this->db->query($sql);
			return $query->rows;
	}	
public function getInvoiceStatus($seller_id){
			$query=$this->db->query("SELECT pvpi.status_id AS invoice_status FROM " . DB_PREFIX . "purpletree_vendor_plan_invoice pvpi LEFT JOIN " . DB_PREFIX . "purpletree_vendor_seller_plan pvsp ON (pvpi.invoice_id = pvsp.invoice_id) WHERE pvsp.seller_id='".(int) $seller_id."' AND pvsp.status=1");
				if($query->num_rows){	
					return $query->row['invoice_status'];
				} else {
					return NULL;	
				}
		}

	public function getSellerContactCustomerschat11($seller_id,$custid){
		
			$sql = "SELECT * FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE seller_id = '".(int)$seller_id."' and customer_id='". (int)$custid ."'";
			
			$sql .=" ORDER BY created_at ASC";
			$query = $this->db->query($sql);
			return $query->rows;
	}
	public function getSellerContactCustomers122($data=array()){

		if ($data['start'] < 0) {
			$data['start'] = 0;
		}

		if ($data['limit'] < 1) {
			$data['limit'] = 1;
		}
		
		$sql = "SELECT COUNT(distinct seller_id) as total FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE customer_id = '".(int)$data['customer_id']."'";
		
		$sql .=" ORDER BY id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		
		$query = $this->db->query($sql);
		if($query->num_rows) {
			return $query->row['total'];
		} else {
			return "0";
		}
	}
	public function getSellerContactCustomers1($data=array()){

		if ($data['start'] < 0) {
			$data['start'] = 0;
		}

		if ($data['limit'] < 1) {
			$data['limit'] = 1;
		}
		
		$sql = "SELECT distinct seller_id FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE customer_id = '".(int)$data['customer_id']."'";
		
		$sql .=" ORDER BY id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getSellerContactCustomers($data=array()){
		
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}

		if ($data['limit'] < 1) {
			$data['limit'] = 1;
		}
		
		$sql = "SELECT distinct customer_id FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE seller_id = '".(int)$data['seller_id']."'";
		
		$sql .=" ORDER BY id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
		public function getSellerContactSeller($data=array()){
		
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}

		if ($data['limit'] < 1) {
			$data['limit'] = 1;
		}
		
		$sql = "SELECT distinct seller_id FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE customer_id = '".(int)$data['customer_id']."'";
		
		$sql .=" ORDER BY id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getSellerContactCustomers1111($data=array()){
		
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}

		//if ($data['limit'] < 1) {
			$data['limit'] = 1;
		//}
		
		$sql = "SELECT COUNT(distinct customer_id ) as total FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE seller_id = '".(int)$data['seller_id']."'";
		
		$sql .=" ORDER BY id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		
		$query = $this->db->query($sql);
		if($query->num_rows){		
			return  $query->row['total']; 
		} else {
			return '0';
		}
	}
	public function getSellerContact($data=array()){
		
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}

		if ($data['limit'] < 1) {
			$data['limit'] = 1;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE seller_id = '".(int)$data['seller_id']."'";
		
		$sql .=" ORDER BY id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalSellerContact($data=array()){
	
		$sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE seller_id = '".(int)$data['seller_id']."'";
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function addContact($data){
		$this->db->query("INSERT into " . DB_PREFIX . "purpletree_vendor_contact SET seller_id= '".(int)$data['seller_id']."',customer_id= '".(int)$data['customer_id']."',contact_from= '".(int)$data['contact_from']."', customer_name ='".$this->db->escape($data['customer_name'])."', customer_email='".$this->db->escape($data['customer_email'])."', customer_message='".$this->db->escape($data['customer_message'])."', created_at=NOW(), updated_at=NOW()");
	}
	
	public function getCusatemail($id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$id . "'");
		return $query->row['email'];
			
	}
	public function getStoreEmail($id){
		$seller_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purpletree_vendor_stores WHERE seller_id = '" . (int)$id . "'");
$email ='';
		if ($seller_query->num_rows) {
				$email = $seller_query->row['store_email'];
		}
		if($email == '') {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$id . "'");
			if ($query->num_rows) {
				$email = $query->row['email'];
			}			
		}
		return $email;		
	}
		public function getCustomerid($id) {
			$sql1 = "SELECT customer_id FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE id = '".(int)$id."'";
		
		$query1 = $this->db->query($sql1);

			if($query1->num_rows){
				return $query1->row['customer_id'];
			}
		}
		
				public function getSellerId($id) {
			$sql1 = "SELECT seller_id FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE id = '".(int)$id."'";
		
		$query1 = $this->db->query($sql1);

			if($query1->num_rows){
				return $query1->row['seller_id'];
			}
		}
		
		public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		if ($query->num_rows) {
			return $query->row;
			} else {
			return NULL;
			}			
		
	}
	public function getSellerContactCustomers1total($data=array()){

		if ($data['start'] < 0) {
			$data['start'] = 0;
		}

		if ($data['limit'] < 1) {
			$data['limit'] = 1;
		}
			
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE customer_id = '".(int)$data['customer_id']."'";
		
		$sql .=" ORDER BY id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];

		$query = $this->db->query($sql);
		if ($query->num_rows) {
			return $query->row['total'];
			} else {
			return NULL;
			}			
		
		
		
	}
	
}
?>