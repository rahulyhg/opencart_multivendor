<?php 
class ModelExtensionPurpletreeMultivendorVendor extends Model{
	
		public function getTotalVendors($data = array()) {		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id AND c.status=1) JOIN " . DB_PREFIX . "purpletree_vendor_stores pvs ON(pvs.seller_id=c.customer_id) ";
		$sql .= " WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getVendors($data = array()) {
		$sql = "SELECT *,pvs.seller_id, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd  ON (c.customer_group_id = cgd.customer_group_id  AND c.status=1) JOIN " . DB_PREFIX . "purpletree_vendor_stores pvs ON(pvs.seller_id=c.customer_id) ";
		$sql .= " WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		$implode = array();
		
		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		$sql .= "GROUP BY pvs.seller_id";
		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.approved',
			'c.ip',
			'c.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);
		} else {
			$sql .= " ORDER BY name";
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
	
	public function addSeller($customer_id,$store_name,$filename = ''){
	
		$this->db->query("INSERT into " . DB_PREFIX . "purpletree_vendor_stores SET seller_id ='".(int)$customer_id."', store_name='".$this->db->escape($store_name)."', store_status='1',store_created_at= NOW(), store_updated_at= NOW()");
	}
	
	public function editSeller($customer_id,$store_name,$become_seller,$filename = ''){
		
		$query = $this->db->query("SELECT id FROM " . DB_PREFIX . "purpletree_vendor_stores WHERE seller_id = '".(int)$customer_id."'");
		if($query->num_rows){
			$seller_status = (((int)$become_seller=="1")?'1':'0');
			$is_removed = (((int)$become_seller=="1"?'0':'1'));			
			if($is_removed){								 
				$this->db->query("DELETE FROM ". DB_PREFIX . "purpletree_vendor_stores WHERE seller_id ='".(int)$customer_id."'");
			}
			$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_stores SET store_name='".$this->db->escape($store_name)."', store_status='".(int)$seller_status."', is_removed='".(int)$is_removed."', store_updated_at= NOW() WHERE seller_id='".(int)$customer_id."'");
		} else {
			if($become_seller=="1"){
				$this->db->query("INSERT into " . DB_PREFIX . "purpletree_vendor_stores SET seller_id ='".(int)$customer_id."', store_name='".$this->db->escape($store_name)."', store_status='1',store_created_at= NOW(), store_updated_at= NOW()");
			}
		}
	}
	
	public function getSellerStorename($store_name,$seller_id=NULL){
		
		$sql = "SELECT id FROM " . DB_PREFIX . "purpletree_vendor_stores where store_name='".$this->db->escape($store_name)."'";
		if(!empty($seller_id)){
			$sql .= "AND seller_id!='".(int)$seller_id."'";
		}
		$query = $this->db->query($sql);

		return $query->num_rows;
	}

		public function disableSeller($seller_id) {

		$query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET status=0 WHERE customer_id='".(int)$seller_id."'");		
		
		$query2 = $this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_products SET is_approved=0 WHERE seller_id='".(int)$seller_id."'");	
		
		$query3 = $this->db->query("UPDATE " . DB_PREFIX . "product SET status=0 WHERE product_id IN (SELECT product_id FROM " . DB_PREFIX . "purpletree_vendor_products WHERE seller_id='".(int)$seller_id."')");

		
		$query5 = $this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_stores SET store_status=0 WHERE seller_id='".(int)$seller_id."'");
		
				
	}
	
	public function checkCategoryassign($cat_id) {
		$query = $this->db->query("SELECT pvp.product_id FROM " . DB_PREFIX . "purpletree_vendor_products pvp JOIN " . DB_PREFIX . "product_to_category pc ON(pc.product_id=pvp.product_id) WHERE pc.category_id='".(int)$cat_id."'");
		return $query->num_rows;
	}
	
	public function getProductSeller($product_id){
		$query = $this->db->query("SELECT seller_id FROM " . DB_PREFIX . "purpletree_vendor_products WHERE product_id='".(int)$product_id."'");
		
		return $query->row;
	}
		public function getSeller($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) RIGHT JOIN " . DB_PREFIX . "purpletree_vendor_stores pvs  ON (pvs.seller_id = c.customer_id) ";
		
		if (!empty($data['filter_affiliate'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "customer_affiliate ca ON (c.customer_id = ca.customer_id)";
		}		
		
		$sql .= " WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_affiliate'])) {
			$implode[] = "ca.status = '" . (int)$data['filter_affiliate'] . "'";
		}
		
		if (!empty($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.ip',
			'c.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
}
?>