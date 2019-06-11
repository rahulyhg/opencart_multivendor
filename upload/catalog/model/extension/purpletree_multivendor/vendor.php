<?php 
class ModelExtensionPurpletreeMultivendorVendor extends Model{
	
	public function addSeller($customer_id,$store_name,$filename = ''){
		$this->db->query("INSERT into " . DB_PREFIX . "purpletree_vendor_stores SET seller_id ='".(int)$customer_id."', store_name='".$this->db->escape(trim($store_name))."', store_status='".(int)(!$this->config->get('module_purpletree_multivendor_seller_approval'))."', store_created_at= NOW(), store_updated_at= NOW()");
		$store_id = $this->db->getLastId();
	}
	
	public function becomeSeller($customer_id,$store_name,$filename = ''){
		if($store_name['become_seller']){
			$this->db->query("INSERT into " . DB_PREFIX . "purpletree_vendor_stores SET seller_id ='".(int)$customer_id."', store_name='".$this->db->escape(trim($store_name['seller_storename']))."', store_status='".(int)(!$this->config->get('module_purpletree_multivendor_seller_approval'))."', store_created_at= NOW(), store_updated_at= NOW()");
			$store_id = $this->db->getLastId();
		}
		else {
			$store_id = 0;
		}
		return $store_id;
		
	}
	
	public function reseller($customer_id,$store_name){
		if($store_name['become_seller']){	
			$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_stores SET store_status='".(int)(!$this->config->get('module_purpletree_multivendor_seller_approval'))."', is_removed=0 WHERE seller_id='".(int)$customer_id."'");
			$store_id = 1;
		}
		else {
			$store_id = 0;
		}
		return $store_id;
		
	}
	
	public function getSellerStorename($store_name){
		$query = $this->db->query("SELECT id FROM " . DB_PREFIX . "purpletree_vendor_stores where store_name='".$this->db->escape($store_name)."'");
		return $query->num_rows;
	}
	
	public function getStoreRating($seller_id){
		$query = $this->db->query("SELECT AVG(rating) as rating,count(*) as count FROM " . DB_PREFIX . "purpletree_vendor_reviews where seller_id='".(int)$seller_id."' AND status=1");
		return $query->row;
	}
	
	public function getStore($store_id){
		$query = $this->db->query("SELECT pvs.*,CONCAT(c.firstname, ' ', c.lastname) AS seller_name, (SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'seller_store_id=" . (int)$store_id . "') AS store_seo FROM " . DB_PREFIX . "purpletree_vendor_stores pvs JOIN " . DB_PREFIX . "customer c ON(c.customer_id = pvs.seller_id) where pvs.id='".(int)$store_id."'");
		return $query->row;
	}
	
	public function getStoreDetail($customer_id){
		$query = $this->db->query("SELECT pvs.* FROM " . DB_PREFIX . "purpletree_vendor_stores pvs where pvs.seller_id='".(int)$customer_id."'");
		return $query->row;
	}
	
	public function editStore($store_id,$data,$file = ''){
		$dcument = "";
		if($file != '') {
			$dcument = ",document='".$file."'";
		}

		$this->db->query("UPDATE " . DB_PREFIX. "purpletree_vendor_stores SET store_name='".$this->db->escape(trim($data['store_name']))."', store_logo='".$this->db->escape($data['store_logo'])."', store_email='".$this->db->escape($data['store_email'])."', store_phone='".$this->db->escape($data['store_phone'])."', store_banner='".$this->db->escape($data['store_banner'])."', store_description='".$this->db->escape($data['store_description'])."', store_address='".$this->db->escape($data['store_address'])."', store_city='".$this->db->escape($data['store_city'])."',store_country='".(int)$data['store_country']."', store_state='".(int)$data['store_state']."', store_zipcode='".$this->db->escape($data['store_zipcode'])."', store_shipping_policy='".$this->db->escape($data['store_shipping_policy'])."', store_return_policy='".$this->db->escape($data['store_return_policy'])."', store_meta_keywords='".$this->db->escape($data['store_meta_keywords'])."', store_meta_descriptions='".$this->db->escape($data['store_meta_description'])."', store_bank_details='".$this->db->escape($data['store_bank_details'])."', store_tin='".$this->db->escape($data['store_tin'])."', store_updated_at=NOW() where id='".(int)$store_id."'");
		
		if ($data['store_seo']) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'seller_store_id=" . (int)$store_id . "'");
			if($query->num_rows > 0){
				$row = $query->row;
				$this->db->query("UPDATE " . DB_PREFIX . "seo_url SET query = 'seller_store_id=" . (int)$store_id . "', language_id = '0', keyword = '" . $this->db->escape($data['store_seo']) . "' WHERE seo_url_id=".$row['seo_url_id']);
			} else{
				$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'seller_store_id=" . (int)$store_id . "', language_id = '0', keyword = '" . $this->db->escape($data['store_seo']) . "'");
			}
		}
	}
	public function getStoreByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "purpletree_vendor_stores WHERE LCASE(store_email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		return $query->row;
	}
	
	public function getStoreSeo($seo_url) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE keyword = '".$this->db->escape($seo_url) . "'");
		return $query->row;
	}
	
	public function removeSeller($seller_id){
		$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_products pvp JOIN " . DB_PREFIX . "product p ON(p.product_id=pvp.product_id) SET p.status=0 WHERE pvp.seller_id='".(int)$seller_id."'");
		
		$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_stores SET store_status=0, is_removed=1 WHERE seller_id='".(int)$seller_id."'");
	}
	public function getStoreNameByStoreName($store_name2){
		$sql = "SELECT pvs.id ,pvs.seller_id ,pvs.store_name,c.status FROM " . DB_PREFIX . "purpletree_vendor_stores pvs LEFT JOIN ". DB_PREFIX ."customer c ON(pvs.seller_id = c.customer_id) WHERE pvs.store_name = '" . $this->db->escape(trim($store_name2)) . "' AND c.status=1";
		$query = $this->db->query($sql);    
   		return $query->row;	
    }
}
?>