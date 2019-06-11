<?php 
class ModelExtensionPurpletreeMultivendorSellerproduct extends Model{
	public function getSellerProducts($data=array()){
		
		$sql = "SELECT pd.*, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating,p.*,pvp.* ,CONCAT(c.firstname, ' ', c.lastname) AS seller_name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) JOIN " . DB_PREFIX . "purpletree_vendor_products pvp ON(pvp.product_id=p.product_id) JOIN " .DB_PREFIX. "customer c ON(c.customer_id=pvp.seller_id) LEFT JOIN ".DB_PREFIX. "product_to_category ptc ON(ptc.product_id=p.product_id) ";
	
		if(!empty($data['seller_id'])){
			
			$sql .= "WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pvp.seller_id ='".(int)$data['seller_id']."'";
		} else {
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		}
		if(!empty($data['category_id']))
		{
			$sql .= " AND ptc.category_id = '" . (int)$data['category_id'] . "'";
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
		
		if (isset($data['status']) && !is_null($data['status'])) {
			$sql .= " AND p.status = '" . (int)$data['status'] . "'";
		}
		
		if (isset($data['is_approved']) && !is_null($data['is_approved'])) {
			$sql .= " AND pvp.is_approved = '" . (int)$data['is_approved'] . "'";
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
				$data['limit'] = 5;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);

		return $query->rows;
	}

	
 	public function sellerTotalProduct($seller_id){
		$query=$this->db->query("SELECT COUNT(*) AS total_product FROM " . DB_PREFIX . "purpletree_vendor_products WHERE seller_id='".(int) $seller_id."'");
	if($query->num_rows){	
		return $query->row;
	} else {
		return NULL;	
	}
	} 
	
		public function getInvoiceStatus($seller_id){
		$query=$this->db->query("SELECT pvpi.status_id AS invoice_status FROM " . DB_PREFIX . "purpletree_vendor_plan_invoice pvpi LEFT JOIN " . DB_PREFIX . "purpletree_vendor_seller_plan pvsp ON (pvpi.invoice_id = pvsp.invoice_id) WHERE pvsp.seller_id='".(int) $seller_id."' AND pvsp.status=1");
	if($query->num_rows){	
		return $query->row['invoice_status'];
	} else {
		return NULL;	
	}
	}
	
	

	public function getTotalSellerProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) JOIN " . DB_PREFIX . "purpletree_vendor_products pvp ON(pvp.product_id=p.product_id) JOIN " .DB_PREFIX. "customer c ON(c.customer_id=pvp.seller_id) LEFT JOIN ".DB_PREFIX. "product_to_category ptc ON(ptc.product_id=p.product_id)";
		
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvp.seller_id ='".(int)$data['seller_id']."'";
		}
		
		if(!empty($data['category_id']))
		{
			$sql .= " AND ptc.category_id = '" . (int)$data['category_id'] . "'";
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
		
		if (isset($data['status']) && !is_null($data['status'])) {
			$sql .= " AND p.status = '" . (int)$data['status'] . "'";
		}
		
		if (isset($data['is_approved']) && !is_null($data['is_approved'])) {
			$sql .= " AND pvp.is_approved = '" . (int)$data['is_approved'] . "'";
		}
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	
	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}
	
	public function getProduct($product_id, $seller_id=NULL) {
		
		$sql = "SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) JOIN " . DB_PREFIX . "purpletree_vendor_products pvp ON(pvp.product_id=p.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		if($seller_id){
			$sql .= " AND pvp.seller_id='".(int)$seller_id."'";
		}
		
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function getProductOptions($product_id) {
		$product_option_data = array();

		$seller_id = $this->customer->getId();
		
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po JOIN ".DB_PREFIX."purpletree_vendor_products pvp ON po.product_id = pvp.product_id LEFT JOIN " . DB_PREFIX . "option o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND pvp.seller_id = '".(int)$seller_id."' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON(pov.option_value_id = ov.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY ov.sort_order ASC");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}
	
	public function getOptionValue($option_value_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_value_id = '" . (int)$option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getOptionValues($option_id) {
		$option_value_data = array();

		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '" . (int)$option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order, ovd.name");

		foreach ($option_value_query->rows as $option_value) {
			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				'image'           => $option_value['image'],
				'sort_order'      => $option_value['sort_order']
			);
		}

		return $option_value_data;
	}
	
	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) JOIN  " . DB_PREFIX . "purpletree_vendor_products pvp ON(pvp.product_id=pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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

		if (isset($data['seller_id']) && !is_null($data['seller_id'])) {
			$sql .= " AND pvp.seller_id = '" . (int)$data['seller_id'] . "'";	
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
	
	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$seller_id = $this->customer->getId();
		
		$query = $this->db->query("SELECT pd.* FROM " . DB_PREFIX . "product_description pd JOIN ".DB_PREFIX."purpletree_vendor_products pvp ON pd.product_id = pvp.product_id WHERE pvp.product_id = '" . (int)$product_id . "' AND pvp.seller_id = '".(int)$seller_id."'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}
	
	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}
	
	public function getProductCategories($product_id) {
		$product_category_data = array();

		$seller_id = $this->customer->getId();

		$query = $this->db->query("SELECT ptc.* FROM " . DB_PREFIX . "product_to_category ptc JOIN ".DB_PREFIX."purpletree_vendor_products pvp ON ptc.product_id = pvp.product_id WHERE pvp.product_id = '" . (int)$product_id . "' AND pvp.seller_id = '".(int)$seller_id."'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}
	public function getSubCategories($category_id)
	{
	
		$query = $this->db->query("SELECT cd.category_id,cd.name FROM " . DB_PREFIX . "category c JOIN " .DB_PREFIX . "category_description cd ON(c.parent_id=cd.category_id) WHERE c.category_id =". (int)$category_id);
		return $query->rows;
		
	}
	
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'category_id=" . (int)$category_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}
	
	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$seller_id = $this->customer->getId();

		$product_attribute_query = $this->db->query("SELECT pa.attribute_id FROM " . DB_PREFIX . "product_attribute pa JOIN ".DB_PREFIX."purpletree_vendor_products pvp ON pa.product_id = pvp.product_id WHERE pvp.product_id = '" . (int)$product_id . "' AND pvp.seller_id = '".(int)$seller_id."' GROUP BY pa.attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}
	
	public function getProductDiscounts($product_id) {
		
		$seller_id = $this->customer->getId();
		
		$query = $this->db->query("SELECT pd.* FROM " . DB_PREFIX . "product_discount pd JOIN ".DB_PREFIX."purpletree_vendor_products pvp ON pd.product_id = pvp.product_id WHERE pvp.product_id = '" . (int)$product_id . "' AND pvp.seller_id = '".(int)$seller_id."' ORDER BY pd.quantity, pd.priority, pd.price");

		return $query->rows;
	}
	
	public function getProductImages($product_id) {
		
		$seller_id = $this->customer->getId();
		
		$query = $this->db->query("SELECT pi.* FROM " . DB_PREFIX . "product_image pi JOIN ".DB_PREFIX."purpletree_vendor_products pvp  ON pi.product_id = pvp.product_id WHERE pvp.product_id = '" . (int)$product_id . "' AND pvp.seller_id = '".(int)$seller_id."' ORDER BY sort_order ASC");

		return $query->rows;
	}
	
	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$seller_id = $this->customer->getId();
		
		$query = $this->db->query("SELECT ptd.* FROM " . DB_PREFIX . "product_to_download ptd JOIN ".DB_PREFIX."purpletree_vendor_products pvp ON ptd.product_id = pvp.product_id WHERE pvp.product_id = '" . (int)$product_id . "' AND pvp.seller_id = '".(int)$seller_id."'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}
	
	public function getProductRelated($product_id) {
		$product_related_data = array();
		
		$seller_id = $this->customer->getId();
		
		$query = $this->db->query("SELECT pr.* FROM " . DB_PREFIX . "product_related pr JOIN ".DB_PREFIX."purpletree_vendor_products pvp ON pr.product_id = pvp.product_id WHERE pvp.product_id = '" . (int)$product_id . "' AND pvp.seller_id = '".(int)$seller_id."'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}
	
	public function getProductRewards($product_id) {
		$product_reward_data = array();
		
		$seller_id = $this->customer->getId();
		
		$query = $this->db->query("SELECT pr.* FROM " . DB_PREFIX . "product_reward pr JOIN ".DB_PREFIX."purpletree_vendor_products pvp ON pr.product_id = pvp.product_id WHERE pvp.product_id = '" . (int)$product_id . "' AND pvp.seller_id = '".(int)$seller_id."'");
		
		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}
	
	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
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
	
	public function getCategories($data = array()) {
		
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if(empty($data['category_type'])){
			$sql .= " AND c1.category_id IN (" . $this->db->escape($data['category_allow']).")";
		}
		$sql .= " GROUP BY cp.category_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);
		} else {
			$sql .= " ORDER BY sort_order";
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
	
	public function getFilters($data) {
		$sql = "SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND fd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " ORDER BY f.sort_order ASC";

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
	
	public function getDownloads($data = array()) {
			$sql = "SELECT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND dd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
			if (isset($data['seller_id']) && !is_null($data['seller_id'])) {
			$sql .= " AND pvd.seller_id = '" . (int)$data['seller_id'] . "'";	
		}
		$sort_data = array(
			'dd.name',
			'd.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY dd.name";
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
	
	public function getAttributes($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}

		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY attribute_group, ad.name";
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
	
	public function getOptions($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND od.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'od.name',
			'o.type',
			'o.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY od.name";
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
	
	public function getSellername($product_id){

		$query  = $this->db->query("SELECT CONCAT(c.firstname, ' ', c.lastname) AS seller_name,pvp.seller_id,pvs.id, pvs.store_name FROM " . DB_PREFIX . "purpletree_vendor_products pvp JOIN " . DB_PREFIX . "customer c ON(c.customer_id=pvp.seller_id) JOIN " . DB_PREFIX . "purpletree_vendor_stores pvs ON(pvs.seller_id= pvp.seller_id) WHERE pvp.product_id ='".(int)$product_id."' AND is_approved=1");
		return $query->row;
	}
	
	public function getProductSeoUrls($product_id) {
		$product_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $product_seo_url_data;
	}

	public function getvendorcatagory($vendor_id)
	{
		$query = $this->db->query("SELECT pvc.vendor_category_id,cd.name FROM " . DB_PREFIX ."purpletree_vendor_categories pvc JOIN " . DB_PREFIX . "category_description cd on(cd.category_id=pvc.vendor_category_id) WHERE pvc.vendor_id=".(int)$vendor_id);
		return $query->rows;
	}
	
	
	public function getSellerProductsCategories($data=array()){
		
		$query = $this->db->query("SELECT cd.name,c.status,ptc.category_id,c.parent_id,cp.path_id FROM " . DB_PREFIX . "purpletree_vendor_products pvp JOIN ". DB_PREFIX ."product_to_category ptc on(pvp.product_id=ptc.product_id) JOIN " . DB_PREFIX. "category c on(c.category_id=ptc.category_id) JOIN " . DB_PREFIX . "category_description cd on(cd.category_id=c.category_id) JOIN " . DB_PREFIX. "category_path cp on(cp.category_id=cd.category_id) WHERE c.status=1 AND cd.language_id='".(int)$data['language_id']."' AND pvp.seller_id='".(int)$data['seller_id']."' AND c.parent_id='".(int)$data['category_id']."' GROUP BY ptc.category_id");
		
		return $query->rows;
	}
	
	public function checkParentCategory($category_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id =". (int)$category_id);
		return $query->rows;
	}

	/////// End category featured and featured product /////////
}
?>