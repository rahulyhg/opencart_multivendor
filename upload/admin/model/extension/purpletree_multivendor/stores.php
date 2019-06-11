<?php 
class ModelExtensionPurpletreeMultivendorStores extends Model{
	public function getTotalStores($data = array()) { 
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) JOIN " . DB_PREFIX . "purpletree_vendor_stores pvs ON(pvs.seller_id=c.customer_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status=1";
		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "pvs.store_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "pvs.store_email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "pvs.store_status = '" . (int)$data['filter_status'] . "'";
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
	
	public function getStoreByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "purpletree_vendor_stores WHERE LCASE(store_email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
		
	}
	
	public function getStoreSeo($seo_url) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE LCASE(keyword) = '".$this->db->escape(utf8_strtolower($seo_url)) . "'");
		return $query->row;
	}
	
	public function editStore($store_id,$data, $file = ''){
		if($data['store_commission'] == '') {
			$datastore_commission = 'NULL';
		} else {
			$datastore_commission = $this->db->escape($data['store_commission']);
		}
		$dcument = "";

		if($file != '') {
			$dcument = ",document='".$this->db->escape($file)."'"; 
		}

		$this->db->query("UPDATE " . DB_PREFIX. "purpletree_vendor_stores SET store_name='".$this->db->escape(trim($data['store_name']))."', store_logo='".$this->db->escape($data['store_logo'])."', store_email='".$this->db->escape($data['store_email'])."', store_phone='".$this->db->escape($data['store_phone'])."', store_banner='".$this->db->escape($data['store_banner'])."', store_description='".$this->db->escape($data['store_description'])."' , store_address='".$this->db->escape($data['store_address'])."', store_city='".$this->db->escape($data['store_city'])."',store_country='".(int)$data['store_country']."', store_state='".(int)$data['store_state']."', store_zipcode='".$this->db->escape($data['store_zipcode'])."', store_shipping_policy='".$this->db->escape($data['store_shipping_policy'])."', store_return_policy='".$this->db->escape($data['store_return_policy'])."', store_meta_keywords='".$this->db->escape($data['store_meta_keywords'])."', store_meta_descriptions='".$this->db->escape($data['store_meta_description'])."', store_bank_details='".$this->db->escape($data['store_bank_details'])."', store_tin='".$this->db->escape($data['store_tin'])."', store_status= '".(int)$data['store_status']."', store_commission=".$datastore_commission.",store_updated_at=NOW() where id='".(int)$store_id."'");
		
		if($this->config->get('module_purpletree_multivendor_product_approval')){
			$is_approved = 0;
		} else{
			$is_approved = 1;
		}
		
		if(!empty($data['product_ids'])){
			foreach($data['product_ids'] as $product_id){
				$this->db->query("INSERT INTO " . DB_PREFIX . "purpletree_vendor_products SET seller_id='".(int)$data['seller_id']."',product_id='".(int)$product_id."', is_approved='".(int)$is_approved."', created_at =NOW(), updated_at =NOW()");
			}
		}
		
		if(!empty($data['vendor_category_ids']))
		{
			foreach($data['vendor_category_ids'] as $vendor_category_id)
			{
				$sql=$this->db->query("SELECT * FROM " . DB_PREFIX . "purpletree_vendor_categories WHERE vendor_id='".(int)$store_id."' AND vendor_category_id='".(int)$vendor_category_id."'");
				
				if($sql->num_rows==0)
				{
					$this->db->query("INSERT INTO " . DB_PREFIX . "purpletree_vendor_categories SET vendor_id='".(int)$store_id."', vendor_category_id='".(int)$vendor_category_id."'");
				}
			}
		}
		
		if ($data['store_seo']) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'seller_store_id=" . (int)$store_id . "'");
            if($query->num_rows > 0){
                $row = $query->row;
                $this->db->query("UPDATE " . DB_PREFIX . "seo_url SET query = 'seller_store_id=" . (int)$store_id . "', language_id = '0', keyword = '" . $this->db->escape($data['store_seo']) . "' WHERE seo_url_id=".$this->db->escape($row['seo_url_id']));
               
            } else{
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'seller_store_id=" . (int)$store_id . "', language_id = '0', keyword = '" . $this->db->escape($data['store_seo']) . "'");
            }
        }
	}
	
	public function getStores($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) JOIN " . DB_PREFIX . "purpletree_vendor_stores pvs ON(pvs.seller_id=c.customer_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND c.status=1";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "pvs.store_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "pvs.store_email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "pvs.store_status = '" . (int)$data['filter_status'] . "'";
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
	
	public function getStore($store_id){
		$query = $this->db->query("SELECT pvs.*,CONCAT(c.firstname, ' ', c.lastname) AS seller_name, (SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'seller_store_id=" . (int)$store_id . "') AS store_seo FROM " . DB_PREFIX . "purpletree_vendor_stores pvs JOIN " . DB_PREFIX . "customer c ON(c.customer_id = pvs.seller_id) where pvs.id='".(int)$store_id."'");
		return $query->row;
	}
	
	public function getStoreDetail($customer_id){
		$query = $this->db->query("SELECT pvs.* FROM " . DB_PREFIX . "purpletree_vendor_stores pvs where pvs.seller_id='".(int)$customer_id."'");
		return $query->row;
	}
	
	public function addSeller($customer_id,$store_name){
		$this->db->query("INSERT into " . DB_PREFIX . "purpletree_vendor_stores SET seller_id ='".(int)$customer_id."', store_name='".$this->db->escape($store_name)."', store_status='0'");
	}
	
	public function editSeller($customer_id,$store_name,$become_seller){
		
		$query = $this->db->query("SELECT id FROM " . DB_PREFIX . "purpletree_vendor_stores WHERE seller_id = '".(int)$customer_id."'");
		if($query->num_rows){
			$seller_status = (($become_seller=="1")?'1':'0');
			$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_stores SET seller_id ='".(int)$customer_id."', store_name='".$this->db->escape($store_name)."', store_status='".(int)$seller_status."'");
		} else {
			if($become_seller=="1"){
				$this->db->query("INSERT into " . DB_PREFIX . "purpletree_vendor_stores SET seller_id ='".(int)$customer_id."', store_name='".$this->db->escape($store_name)."', store_status='1'");
			}
		}
	}
	
	public function getSellerStorename($store_name){
		$query = $this->db->query("SELECT id FROM " . DB_PREFIX . "purpletree_vendor_stores");
		return $query->num_rows;
	}
	
	public function getProductList($data = array()){
		
		$sql = "SELECT p.product_id,p.model,p.price,p.quantity,pd.name,p.status FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		
		if(empty($data['category_type'])){
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category pc ON(pc.product_id=p.product_id)";
		} 
		$sql.="	LEFT JOIN " . DB_PREFIX . "purpletree_vendor_products pvp ON(pvp.product_id=p.product_id)";
		
		$sql .= " where pvp.id IS NULL AND pd.name IS NOT NULL";
		
		if(empty($data['category_type'])){
			if($this->db->escape($data['category_allow'])){
				$sql .=" AND pc.category_id IN (" . $this->db->escape($data['category_allow']).")";
			}
		}
		$sql .=" group by p.product_id";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getSellerProducts($data=array()){
		
		$sql = "SELECT * ,CONCAT(c.firstname, ' ', c.lastname) AS seller_name,p.status as product_status FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) JOIN " .DB_PREFIX. "purpletree_vendor_products pvp ON(pvp.product_id=p.product_id) JOIN " .DB_PREFIX. "customer c ON(c.customer_id=pvp.seller_id)";
	
		if(!empty($data['seller_id'])){
			
			$sql .= "WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pvp.seller_id ='".(int)$data['seller_id']."'";
		} else {
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		}
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_approval']) && !is_null($data['filter_approval'])) {
			$sql .= " AND pvp.is_approved = '" . (int)$data['filter_approval'] . "'";
		}

		if (isset($data['filter_image']) && !is_null($data['filter_image'])) {
			if ($data['filter_image'] == 1) {
				$sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
			} else {
				$sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
			}
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);
		} else {
			$sql .= " ORDER BY pd.name";
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
	
	
	

	public function getTotalSellerProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) JOIN " . DB_PREFIX . "purpletree_vendor_products pvp ON(pvp.product_id=p.product_id)";
		
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvp.seller_id ='".(int)$data['seller_id']."'";
		}
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		if (isset($data['filter_approval']) && !is_null($data['filter_approval'])) {
			$sql .= " AND pvp.is_approved = '" . (int)$data['filter_approval'] . "'";
		}
		if (isset($data['filter_image']) && !is_null($data['filter_image'])) {
			if ($data['filter_image'] == 1) {
				$sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
			} else {
				$sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
			}
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function delete_vendor_categories($id){ 
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "purpletree_vendor_categories WHERE id='".(int)$id."'");
	}
	
	public function getTotalSellerOrders($data= array()){
		
$sql = "SELECT COUNT(DISTINCT(pvo.order_id)) AS total FROM `" . DB_PREFIX . "order` o JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=o.order_id)";
		
		if (isset($data['filter_order_status']) && $data['filter_order_status']!='') {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "pvo.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE pvo.order_status_id > 0";
		}
		if (isset($data['filter_admin_order_status']) && $data['filter_admin_order_status']!='') {
			$implode1 = array();

			$order_statuses1 = explode(',', $data['filter_admin_order_status']);

			foreach ($order_statuses1 as $order_status_id1) {
				$implode1[] = "o.order_status_id = '" . (int)$order_status_id1 . "'";
			}

			if ($implode1) {
				$sql .= " AND (" . implode(" OR ", $implode1) . ")";
			}
		} else {
			$sql .= " AND o.order_status_id > 0";
		}
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvo.seller_id ='".(int)$data['seller_id']."'";
		}
		
		if(!empty($data['seller_id_filter'])){
			$sql .= " AND pvo.seller_id ='".(int)$data['seller_id_filter']."'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}
		if(!isset($data['filter_date_from']) && !isset($data['filter_date_to'])){
			$end_date = date('Y-m-d', strtotime("-30 days"));
			$sql .= " AND DATE(o.date_added) >= '".$end_date."'";
			$sql .= " AND DATE(o.date_added) <= '".date('Y-m-d')."'";
		}
		$query = $this->db->query($sql);
		if($query->num_rows > 0 ){
			return $query->row['total'];
		} else{
			return 0;
		}
	}
	
	public function getSellerOrders($data = array()) {
		$sql = "SELECT pvo.*, o.order_status_id AS admin_order_status_idd,o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = pvo.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status,(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS admin_order_status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=o.order_id)";

		if (isset($data['filter_order_status']) && $data['filter_order_status']!='') {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "pvo.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE pvo.order_status_id > 0";
		}
		if (isset($data['filter_admin_order_status']) && $data['filter_admin_order_status']!='') {
			$implode1 = array();

			$order_statuses1 = explode(',', $data['filter_admin_order_status']);

			foreach ($order_statuses1 as $order_status_id1) {
				$implode1[] = "o.order_status_id = '" . (int)$order_status_id1 . "'";
			}

			if ($implode1) {
				$sql .= " AND (" . implode(" OR ", $implode1) . ")";
			}
		} else {
			$sql .= " AND o.order_status_id > 0";
		}
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvo.seller_id ='".(int)$data['seller_id']."'";
		}
		
		if(!empty($data['seller_id_filter'])){
			$sql .= " AND pvo.seller_id ='".(int)$data['seller_id_filter']."'";
		}
		

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}
		if(!isset($data['filter_date_from']) && !isset($data['filter_date_to'])){
			$end_date = date('Y-m-d', strtotime("-30 days"));
			$sql .= " AND DATE(o.date_added) >= '".$end_date."'";
			$sql .= " AND DATE(o.date_added) <= '".date('Y-m-d')."'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'order_status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);
		
		$sql .= " group by o.order_id";
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		};
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getSellerOrdersProductTotal($seller_id,$order_id){
		$query = $this->db->query("SELECT SUM(total_price) AS total, SUM(shipping) AS total_shipping  FROM " . DB_PREFIX . "purpletree_vendor_orders WHERE seller_id = '".(int)$seller_id."' AND order_id = '".(int)$order_id."'");

		return $query->rows;
	}
	
	public function getSellerOrdersTotal($seller_id,$order_id){
		$query = $this->db->query("SELECT value AS total  FROM " . DB_PREFIX . "purpletree_order_total WHERE seller_id = '".(int)$seller_id."' AND order_id = '".(int)$order_id."' AND code='sub_total'");
		return $query->row;
	}
	
	public function getSellerOrdersCommission($order_id,$seller_id=NULL){
		
		$sql = "SELECT SUM(commission) AS total_commission  FROM " . DB_PREFIX . "purpletree_vendor_commissions WHERE order_id = '".(int)$order_id."'";		
		if(isset($seller_id)){
			$sql .= " AND seller_id = '".(int)$seller_id."'";
		}
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function getSellerOrdersCommissionTotal($seller_id=NULL){
		
		$sql = "SELECT SUM(pvc.commission) AS total_commission  FROM " . DB_PREFIX . "purpletree_vendor_commissions pvc JOIN `" . DB_PREFIX . "order` o ON(o.order_id=pvc.order_id) WHERE o.order_status_id = '5'";		
		if(!empty($seller_id)){
			$sql .= " AND pvc.seller_id = '".(int)$seller_id."'";
		}
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getSellerCompleteAmount($seller_id=NULL){
		
		$sql = "SELECT SUM(pvo.total_price) AS total  FROM " . DB_PREFIX . "purpletree_vendor_orders pvo JOIN `" . DB_PREFIX . "order` o ON(o.order_id=pvo.order_id) WHERE o.order_status_id = '5'";		
		if(!empty($seller_id)){
			$sql .= " AND pvo.seller_id = '".(int)$seller_id."'";
		}
		$query = $this->db->query($sql);
		return $query->row;
	}

	
	public function getOrder($order_id,$seller_id){
		$sql = "SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status FROM `" . DB_PREFIX . "order` o JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=o.order_id) WHERE o.order_id = '" . (int)$order_id . "'";
		
		if(!empty($seller_id)){
			$sql .=" AND pvo.seller_id = '".(int)$seller_id."'";
		}
		$order_query = $this->db->query($sql);
		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}
			
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			$this->load->model('customer/customer');

			$affiliate_info = $this->model_customer_customer->getCustomer($order_query->row['affiliate_id']);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => json_decode($order_query->row['payment_custom_field'], true),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return;
		}
	}
	
	public function getOrderProducts($order_id,$seller_id) {
		$query = $this->db->query("SELECT op.* ,(SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = pvo.seller_id) as seller_name,pvo.seller_id FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=op.order_id AND pvo.product_id=op.product_id) WHERE op.order_id = '" . (int)$order_id . "'");
		
		return $query->rows;
	}
	
	public function getSellerOrderProducts($order_id,$seller_id){
		$query = $this->db->query("SELECT op.* ,(SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = pvo.seller_id) as seller_name, pvo.seller_id, pvo.shipping FROM " . DB_PREFIX . "order_product op JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=op.order_id AND pvo.product_id=op.product_id) WHERE op.order_id = '" . (int)$order_id . "' AND pvo.seller_id = '".(int)$seller_id."'");
		return $query->rows;
	}
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}
	
	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}
	
	public function getOrderTotals($order_id,$seller_id) { 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purpletree_order_total WHERE order_id = '" . (int)$order_id . "' AND seller_id = '".(int)$seller_id."' ORDER BY sort_order");

		return $query->rows;
	}
	public function getOrderTotalsfromorder($order_id) { 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purpletree_order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}
	public function getsellerInfofororder($sellerid) { 
		$query = $this->db->query("SELECT CONCAT(c.firstname, ' ', c.lastname) AS seller_name, pvs.id AS store_id FROM " . DB_PREFIX . "purpletree_vendor_stores pvs JOIN " . DB_PREFIX . "customer c ON(pvs.seller_id=c.customer_id) WHERE pvs.seller_id = '" . (int)$sellerid . "'");

		return $query->row;
	}
	
	public function getOrderHistories($order_id, $seller_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.created_at, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "purpletree_vendor_orders_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND oh.seller_id= '".(int)$seller_id."' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.created_at ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getTotalOrderHistories($order_id,$seller_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purpletree_vendor_orders_history WHERE order_id = '" . (int)$order_id . "' AND seller_id='".(int)$seller_id."'");

		return $query->row['total'];
	}
	
	public function approveProduct($product_id){
		$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_products SET is_approved=1 WHERE product_id='".(int)$product_id."'");
	}
	
	public function approveSeller($store_id){
		$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_stores SET store_status=1, is_removed=0, store_updated_at=NOW() WHERE id='".(int)$store_id."'");
	}
	
	public function disapproveSeller($store_id){ 
		$this->db->query("UPDATE " . DB_PREFIX . "purpletree_vendor_stores SET store_status=0, is_removed=0, store_updated_at=NOW() WHERE id='".(int)$store_id."'");
		
		$query = $this->db->query("select product_id FROM " . DB_PREFIX . "purpletree_vendor_products pvp JOIN " . DB_PREFIX . "purpletree_vendor_stores pvs ON pvp.seller_id=pvs.seller_id WHERE pvs.id='".(int)$store_id."'");
		
		if($query->num_rows > 0){
			foreach($query->rows as $product){
				$this->db->query("UPDATE " . DB_PREFIX . "product SET status=0,date_modified=NOW() WHERE product_id='".(int)$product['product_id']."'");
			}
		}
	}
	
	public function unAssign($product_id){ 
		$this->db->query("DELETE FROM " . DB_PREFIX . "purpletree_vendor_products WHERE product_id='".(int)$product_id."'");
	}
	
	public function getLatestsellerstatus($order_id, $seller_id){
		$query = $this->db->query("SELECT oh.created_at, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "purpletree_vendor_orders_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND oh.seller_id= '".(int)$seller_id."' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.id DESC LIMIT 1");
		return $query->row;
	}
	
	public function getUniqueSeller($order_id){
		$query = $this->db->query("SELECT DISTINCT(seller_id) FROM " . DB_PREFIX . "purpletree_vendor_orders_history WHERE order_id='".(int)$order_id."' order by id desc");
		$result = $query->rows;
		return $this->getSellerOrderStatus($result, $order_id);
	}
	
	public function getSellerOrderStatus($result, $order_id){
		$order_status = array();
		foreach($result as $result){
			$query = $this->db->query("SELECT pvs.id AS store_id ,pvs.store_name, pvh.order_id, pvh.seller_id, pvh.created_at,pvh.order_status_id, os.name AS status FROM " . DB_PREFIX . "purpletree_vendor_orders_history pvh JOIN " . DB_PREFIX . "purpletree_vendor_stores pvs ON pvh.seller_id=pvs.seller_id LEFT JOIN " . DB_PREFIX . "order_status os ON pvh.order_status_id = os.order_status_id WHERE pvh.seller_id='".(int)$result['seller_id']."' AND pvh.order_id='".(int)$order_id."' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pvh.id DESC limit 1");
			$result1 = $query->row;
			$shipping = 0;
			$curency = $this->config->get('config_currency');
			$currency_detail = $this->getCurrencySymbol($curency);
			$shipping = $this->currency->format($shipping, $currency_detail['code'], $currency_detail['value']);
			$storeurll = "#";
			$result1status = "";
			$result1store_name  = "";
			$result1order_status_id = "";
			if($query->num_rows == 1) {
				$storeurll = $this->url->link('extension/purpletree_multivendor/stores/edit&store_id='.$result1['store_id'], 'user_token=' . $this->session->data['user_token'], true);
				$result1status = $result1['status'];
				$result1order_status_id = $result1['order_status_id'];
				$result1store_name = $result1['store_name'];
			$sql11 = "SELECT o.currency_value,o.currency_code,pot.value as shipping_value FROM " . DB_PREFIX . "purpletree_vendor_stores pvs JOIN " . DB_PREFIX . "purpletree_order_total pot ON pvs.seller_id = pot.seller_id JOIN " . DB_PREFIX . "order o ON o.order_id = pot.order_id WHERE id= '". (int)$result1['store_id'] ."' AND pot.order_id = '". (int)$order_id ."' AND pot.code ='seller_shipping'";
			$quer11 = $this->db->query($sql11);
			//$result12 = $query->rows;
			if($quer11->num_rows == 1) {
				$shipping = $quer11->row['shipping_value'];
				$shipping = $this->currency->format($shipping, $quer11->row['currency_code'], $quer11->row['currency_value']);
			}
			}
			$order_status[] = array(
				'shipping' => $shipping,
				'store_name' => $result1store_name ,
				'store_url' => $storeurll,
				'order_status' => $result1status,
				'order_status_id' => $result1order_status_id ,
				'product' => $this->getSelleradminOrderProducts($order_id, $result['seller_id'])
			);
		}
	
		return $order_status;
	}
	
	public function getSelleradminOrderProducts($order_id, $seller_id){
		$query = $this->db->query("SELECT (SELECT name FROM " . DB_PREFIX . "product_description where product_id = pvo.product_id AND language_id = '" . (int)$this->config->get('config_language_id') . "') as product_name, total_price FROM " . DB_PREFIX . "purpletree_vendor_orders pvo WHERE pvo.seller_id='".(int)$seller_id."' AND pvo.order_id = '".(int)$order_id."'");
		return $query->rows;
	}
	
	public function getVendors($data = array()) {
		$sql = "SELECT id,store_name FROM " . DB_PREFIX . "purpletree_vendor_stores";
		
		if (!empty($data['filter_name'])) {
			$implode[] = " LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		$sort_data = array(
			'store_name',
			
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);
		} else {
			$sql .= " ORDER BY store_name";
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
			public function getSellerId($store_id) {
		
		$query = $this->db->query("SELECT seller_id,store_status FROM " . DB_PREFIX . "purpletree_vendor_stores WHERE id = '" . (int)$store_id . "'");

		return $query->row;
	}
	public function getCustomerId($seller_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$seller_id . "'");

		return $query->row;
	}	
	public function getSellerstore($store_name1) {
		$sql = "SELECT pvs.seller_id,pvs.store_name FROM " . DB_PREFIX . "purpletree_vendor_stores pvs LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = pvs.seller_id) WHERE pvs.store_name  LIKE '%" . $this->db->escape($store_name1) . "%' AND c.status=1 AND pvs.store_status=1";

		$query = $this->db->query($sql);

		return $query->rows;
	
	}
	public function getStoreNameByStoreName($store_name2){
		
		$sql = "SELECT pvs.id ,pvs.seller_id ,pvs.store_name,c.status FROM " . DB_PREFIX . "purpletree_vendor_stores pvs LEFT JOIN ". DB_PREFIX ."customer c ON(pvs.seller_id = c.customer_id) WHERE pvs.store_name = '" . $this->db->escape(trim($store_name2)) . "' AND c.status=1";
		$query = $this->db->query($sql);       
		return $query->row;	
    }
		public function getCurrencySymbol($currency_code){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX. "currency WHERE code='".$this->db->escape($currency_code)."'");
		return $query->row;
	}
			public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) RIGHT JOIN  " . DB_PREFIX . "purpletree_vendor_products pvp ON (p.product_id = pvp.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_approval']) && $data['filter_approval'] !== '') {
			$sql .= " AND pvp.is_approved = '" . (int)$data['filter_approval'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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