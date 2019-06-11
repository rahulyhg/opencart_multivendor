<?php
class ModelExtensionPurpletreeMultivendorSellers extends Model{
	
	public function getSellers($data= array()){
		
		$sort_data = array(
			'seller'
		); 
		
		$sql = "SELECT pvs.*,(SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = pvs.seller_id) AS seller,(SELECT co.name FROM " . DB_PREFIX . "country co WHERE co.country_id = pvs.store_country) AS seller_country FROM " . DB_PREFIX . "purpletree_vendor_stores pvs WHERE pvs.store_status='1'";
		
		if(!empty($data['filter'])){
			$sql .=" HAVING pvs.store_name LIKE '%" . $this->db->escape($data['filter']) . "%'";
		}
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= "ORDER BY LCASE(pvs.store_name)";
		} else {
			$sql .= "ORDER BY pvs.store_created_at";
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

		
	public function getTotalSellers($data= array()){
		
		$sql = "SELECT pvs.store_name, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = pvs.seller_id) AS seller FROM " . DB_PREFIX . "purpletree_vendor_stores pvs WHERE pvs.store_status='1'";
		if(!empty($data['filter'])){
			$sql .=" HAVING pvs.store_name LIKE '%" . $this->db->escape($data['filter']) . "%'";
		}
		$query = $this->db->query($sql);
		
		$query->row['total'] = $query->num_rows;
		
		return $query->row['total'];
	}
	
	public function getTotalProducts($data= array()){
		
		$sql = "SELECT COUNT(pvp.id) AS total FROM " . DB_PREFIX . "purpletree_vendor_products pvp JOIN " . DB_PREFIX . "product p ON(p.product_id=pvp.product_id) WHERE pvp.is_approved='1' AND p.status ='1'";
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvp.seller_id ='".(int)$data['seller_id']."'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getProducts($data= array()){
		
		$sql = "SELECT p.image, p.product_id FROM " . DB_PREFIX . "purpletree_vendor_products pvp JOIN " . DB_PREFIX . "product p ON(p.product_id=pvp.product_id) WHERE pvp.is_approved='1' AND p.status ='1'";
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvp.seller_id ='".(int)$data['seller_id']."'";
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
}