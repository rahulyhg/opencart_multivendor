<?php 
class ModelExtensionPurpletreeMultivendorSellerreview extends Model{
	
	public function getSellerReview($data = array()){
		
		$sql = "SELECT pvr.*,CONCAT(c.firstname,' ',c.lastname) AS customer_name FROM " . DB_PREFIX . "purpletree_vendor_reviews pvr JOIN " . DB_PREFIX . "customer c ON(c.customer_id=pvr.customer_id)";
		
		if(!empty($data['seller_id'])){
			$sql .= " WHERE pvr.seller_id = '".(int)$data['seller_id']."'";
		}
		
		if (!empty($data['filter_customer_name'])) {
			$sql .= " AND CONCAT(c.firstname,' ',c.lastname) LIKE '" . $this->db->escape($data['filter_customer_name']) . "%'";
		}
		
		if (!empty($data['filter_title'])) {
			$sql .= " AND pvr.review_title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND pvr.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_created_at'])) {
			$sql .= " AND DATE(pvr.created_at) = DATE('" . $this->db->escape($data['filter_created_at']) . "')";
		}

		$sort_data = array(
			'customer_name',
			'pvr.rating',
			'pvr.status',
			'pvr.created_at'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);
		} else {
			$sql .= " ORDER BY pvr.created_at";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalSellerReview($data = array()){
	
		$sql = "SELECT count(*) AS total ,CONCAT(c.firstname,c.lastname) AS customer_name FROM " . DB_PREFIX . "purpletree_vendor_reviews pvr JOIN " . DB_PREFIX . "customer c ON(c.customer_id=pvr.customer_id)";
		
		if(!empty($data['seller_id'])){
			$sql .= " WHERE pvr.seller_id = '".(int)$data['seller_id']."'";
		}
		
		if (!empty($data['filter_customer_name'])) {
			$sql .= " AND CONCAT(c.firstname,' ',c.lastname) LIKE '" . $this->db->escape($data['filter_customer_name']) . "%'";
		}
		
		if (!empty($data['filter_title'])) {
			$sql .= " AND pvr.review_title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND pvr.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_created_at'])) {
			$sql .= " AND DATE(pvr.created_at) = DATE('" . $this->db->escape($data['filter_created_at']) . "')";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getSellerName($seller_id){
		$query = $this->db->query("SELECT CONCAT(firstname,' ',lastname) AS seller_name FROM " . DB_PREFIX . "customer WHERE customer_id= '".(int)$seller_id."'");
		return $query->row['seller_name'];
	}
	
	public function getStoreName($seller_id){
		$query = $this->db->query("SELECT store_name FROM " . DB_PREFIX . "purpletree_vendor_stores WHERE seller_id= '".(int)$seller_id."'");
		return $query->row['store_name'];
	}
	
	public function addReview($data){
		$this->db->query("INSERT into " . DB_PREFIX . "purpletree_vendor_reviews SET seller_id= '".(int)$data['seller_id']."', customer_id ='".(int)$data['customer_id']."', review_title='".$this->db->escape($data['review_title'])."', review_description='".$this->db->escape($data['review_description'])."', status=0, rating ='".(int)$data['rating']."', created_at=NOW(), updated_at=NOW()");
	}
	
	public function getReview($id){
		$sql = "SELECT pvr.*,CONCAT(c.firstname,' ',c.lastname) AS customer_name FROM " . DB_PREFIX . "purpletree_vendor_reviews pvr JOIN " . DB_PREFIX . "customer c ON(c.customer_id=pvr.customer_id) WHERE pvr.id='".(int)$id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function editReview($id,$data){
		
		$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_reviews SET review_title = '".$this->db->escape($data['review_title'])."', review_description ='".$this->db->escape($data['review_description'])."', rating ='".(int)$data['rating']."', status='".(int)$data['status']."', updated_at=NOW() WHERE id='".(int)$id."'");
	}
	
	public function deleteReview($review_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "purpletree_vendor_reviews WHERE id = '" . (int)$review_id . "'");

	}
}
?>