<?php 
class ModelExtensionPurpletreeMultivendorSellercontacts extends Model{
	
	public function getsellercontacts($data = array()){
		$sql = "SELECT pvc.*,CONCAT(c.firstname,' ',c.lastname) AS seller_name FROM " . DB_PREFIX . "purpletree_vendor_contact pvc JOIN " . DB_PREFIX . "customer c ON(c.customer_id=pvc.seller_id)";
		
		if(!empty($data['seller_id'])){
			$sql .= " WHERE pvc.seller_id = '".(int)$data['seller_id']."'";
		}
		
		if (!empty($data['filter_seller_name'])) {
			$sql .= " AND CONCAT(c.firstname,' ',c.lastname) LIKE '" . $this->db->escape($data['filter_seller_name']) . "%'";
		}
		
		if (!empty($data['filter_customer_name'])) {
			$sql .= " AND pvc.customer_name LIKE '" . $this->db->escape($data['filter_customer_name']) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$sql .= " AND pvc.customer_email = '" . $this->db->escape($data['filter_email']) . "'";
		}

		if (!empty($data['filter_created_at'])) {
			$sql .= " AND DATE(pvc.created_at) = DATE('" . $this->db->escape($data['filter_created_at']) . "')";
		}

		$sql .= " ORDER BY pvc.created_at";

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
	
	public function getTotalsellercontacts($data = array()){
	
		$sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "purpletree_vendor_contact pvc JOIN " . DB_PREFIX . "customer c ON(c.customer_id=pvc.seller_id)";
		
		if(!empty($data['seller_id'])){
			$sql .= " WHERE pvc.seller_id = '".(int)$data['seller_id']."'";
		}
		
		if (!empty($data['filter_seller_name'])) {
			$sql .= " AND CONCAT(c.firstname,' ',c.lastname) LIKE '" . $this->db->escape($data['filter_seller_name']) . "%'";
		}
		
		if (!empty($data['filter_customer_name'])) {
			$sql .= " AND pvc.customer_name LIKE '" . $this->db->escape($data['filter_customer_name']) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$sql .= " AND pvc.customer_email = '" . $this->db->escape($data['filter_email']) . "'";
		}

		if (!empty($data['filter_created_at'])) {
			$sql .= " AND DATE(pvc.created_at) = DATE('" . $this->db->escape($data['filter_created_at']) . "')";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getMessage($id){
		$sql = "SELECT pvc.*,CONCAT(c.firstname,' ',c.lastname) AS seller_name FROM " . DB_PREFIX . "purpletree_vendor_contact pvc JOIN " . DB_PREFIX . "customer c ON(c.customer_id=pvc.seller_id) WHERE pvc.id='".(int)$id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function deleteMessage($message_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "purpletree_vendor_contact WHERE id = '" . (int)$message_id . "'");

	}
}
?>