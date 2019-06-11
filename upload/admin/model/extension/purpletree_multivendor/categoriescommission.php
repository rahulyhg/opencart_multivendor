<?php 
class ModelExtensionPurpletreeMultivendorCategoriescommission extends Model{
	public function getTotalCategoriesCommission($data = array()) {
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purpletree_vendor_categories_commission";
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	
	public function addCategoriesCommission($data=array()){
			$qryadd = "";
		if(isset($data['filter_seller_group'])) {
			$qryadd = ", seller_group='".(int)$data['filter_seller_group']."'";
		}
	$this->db->query("INSERT INTO " . DB_PREFIX . "purpletree_vendor_categories_commission SET category_id ='".(int)$data['filter_id']."', commission='".(float)$data['filter_commission']."', commison_fixed='".(float)$data['filter_commission_fixed']."'".$qryadd ."");
	}
	
	
	
	
	public function editCategoriesCommission($data=array()){
		
			$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_categories_commission SET commission ='".(float)$data['filter_commission']."', commison_fixed='".(float)$data['filter_commission_fixed']."', seller_group='".(int)$data['filter_seller_group']."' WHERE id='".(int)$data['filter_id']."'");

      
	}
	public function deleteCategoriesCommission($id){ 
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "purpletree_vendor_categories_commission WHERE id='".(int)$id."'");
	}
	
	public function getCommission($commission_id){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "purpletree_vendor_categories_commission where id = '".(int)$commission_id."'";
		
		$query = $this->db->query($sql);
        
		return $query->row;
	}
	
	public function getCategoryName($category_id){
		
		$sql = "SELECT name FROM " . DB_PREFIX . "category_description where category_id = '".(int)$category_id."'";
		
		$query = $this->db->query($sql);
        
		return $query->row;
	}
	
	
	
	
	public function getCategoriesCommission($data = array(),$show_seller_group){
		if($show_seller_group == 1) {
		$sql = "SELECT pvcc.id,cd.name,pvcc.commission, pvcc.commison_fixed as commission_fixed,cgd.name as seller_group FROM " . DB_PREFIX . "purpletree_vendor_categories_commission pvcc INNER JOIN ". DB_PREFIX ."category_description cd ON(pvcc.category_id=cd.category_id) JOIN ". DB_PREFIX ."customer_group_description cgd ON (cgd. 	customer_group_id = pvcc.seller_group) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY pvcc.id";
		}
		else {
			$sql = "SELECT pvcc.id,cd.name,pvcc.commission, pvcc.commison_fixed as commission_fixed FROM " . DB_PREFIX . "purpletree_vendor_categories_commission pvcc INNER JOIN ". DB_PREFIX ."category_description cd ON(pvcc.category_id=cd.category_id) GROUP BY pvcc.id";
		}

		$sort_data = array(
			'commission'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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
	 

	public function getAllCategories() {
		$sql = "SELECT pvcc.category_id FROM " . DB_PREFIX . "purpletree_vendor_categories_commission pvcc";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getCategories($data = array()) {
			$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}



		$query = $this->db->query($sql);

		return $query->rows;
	}
		public function getCurrencySymbol($currency_code){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX. "currency WHERE code='".$this->db->escape($currency_code)."'");
		return $query->row;
	}
}
?>