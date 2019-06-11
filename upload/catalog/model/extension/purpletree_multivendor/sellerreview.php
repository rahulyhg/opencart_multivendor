<?php 
class ModelExtensionPurpletreeMultivendorSellerreview extends Model{
	
	public function getSellerReview($data=array()){
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}

		if ($data['limit'] < 1) {
			$data['limit'] = 1;
		}
		
		$sql = "SELECT pvr.*,CONCAT(c.firstname, ' ',c.lastname) AS customer_name FROM " . DB_PREFIX . "purpletree_vendor_reviews pvr JOIN " . DB_PREFIX . "customer c ON(c.customer_id=pvr.customer_id) WHERE pvr.seller_id = '".(int)$data['seller_id']."'";
		
		if(!isset($data['shown'])){
			$sql .= " AND pvr.status =1";
		}
		
		$sql .=" ORDER BY pvr.id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalSellerReview($data=array()){
	
		$sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "purpletree_vendor_reviews WHERE seller_id = '".(int)$data['seller_id']."'";
		if(!isset($data['shown'])){
			$sql .= " AND status =1";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function checkReview($data=array()){
		$query = $this->db->query("SELECT seller_id FROM " . DB_PREFIX . "purpletree_vendor_reviews WHERE seller_id='".(int)$data['seller_id']."' AND customer_id='".(int)$data['customer_id']."'");
		return $query->num_rows;
	}
		public function canReview($data=array()){
		$query = $this->db->query("SELECT `order_id` FROM " . DB_PREFIX . "purpletree_vendor_orders WHERE seller_id='".(int)$data['seller_id']."' ");
		if(!empty($query->rows)) {
			foreach($query->rows as $orderes) {
				$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE customer_id='".$data['customer_id']."' AND order_id='".(int)$orderes['order_id']."' ");
				if(!empty($query1->rows)) {
					return true;
				}
			}
		}
		return false;
		
	}
	public function addReview($data){
		$this->db->query("INSERT into " . DB_PREFIX . "purpletree_vendor_reviews SET seller_id= '".(int)$data['seller_id']."', customer_id ='".(int)$data['customer_id']."', review_title='".$this->db->escape($data['review_title'])."', review_description='".$this->db->escape($data['review_description'])."', status=0, rating ='".(int)$data['rating']."', created_at=NOW(), updated_at=NOW()");
	}

}
?>