<?php 
class ModelExtensionPurpletreeMultivendorSellercommission extends Model{
	
	public function getCommissions($data=array()){
		
		$sql = "SELECT pvc.*,pd.name,pvo.total_price,o.currency_code, o.currency_value FROM " . DB_PREFIX . "purpletree_vendor_commissions pvc JOIN " .DB_PREFIX. "product_description pd ON(pd.product_id=pvc.product_id) JOIN " .DB_PREFIX. "purpletree_vendor_orders pvo ON(pvo.product_id=pvc.product_id AND pvo.order_id=pvc.order_id) JOIN `" .DB_PREFIX. "order` o ON(o.order_id=pvo.order_id)";
		
		$sql .= " WHERE o.order_status_id ='".(int)$data['order_status']."'";
		$sql .= " AND pvo.order_status_id ='".(int)$data['order_status']."'";
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvc.seller_id ='".(int)$data['seller_id']."'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(pvc.created_at) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(pvc.created_at) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}
		if(!isset($data['filter_date_from']) && !isset($data['filter_date_to'])){
			$end_date = date('Y-m-d', strtotime("-30 days"));
			$sql .= " AND DATE(pvc.created_at) >= '".$end_date."'";
			$sql .= " AND DATE(pvc.created_at) <= '".date('Y-m-d')."'";
		}
		
		$sql .= " GROUP BY pvc.id ORDER BY id DESC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query  = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalCommissions($data=array()){
	
		$sql  = "SELECT count(*) AS total FROM " . DB_PREFIX . "purpletree_vendor_commissions pvc JOIN " .DB_PREFIX. "purpletree_vendor_orders pvo ON(pvo.product_id=pvc.product_id AND pvo.order_id=pvc.order_id) JOIN `" .DB_PREFIX. "order` o ON(o.order_id=pvo.order_id)";
		
		$sql .= " WHERE o.order_status_id ='".(int)$data['order_status']."'";
		$sql .= " AND pvo.order_status_id ='".(int)$data['order_status']."'";
		
		if(!empty($data['seller_id'])){
			$sql .= " AND pvc.seller_id ='".(int)$data['seller_id']."'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(pvc.created_at) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(pvc.created_at) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}
		if(empty($data['filter_date_from']) && empty($data['filter_date_to'])){
			$end_date = date('Y-m-d', strtotime("-30 days"));
			$sql .= " AND DATE(pvc.created_at) >= '".$end_date."'";
			$sql .= " AND DATE(pvc.created_at) <= '".date('Y-m-d')."'";
		}
		
		$query  = $this->db->query($sql);
		
		if($query->num_rows >0){
			return $query->row['total'];
		} else {
			return 0;
		}
	}
	
	public function getTotalsale($data=array()){
		$sql  = "SELECT SUM(pvo.total_price) as total,o.currency_code, o.currency_value FROM " . DB_PREFIX . "purpletree_vendor_orders pvo JOIN `" . DB_PREFIX . "order` o ON(pvo.order_id=o.order_id) ";
		
		if(!empty($data['seller_id'])){
			$sql .= " WHERE pvo.seller_id ='".(int)$data['seller_id']."'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(pvo.created_at) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(pvo.created_at) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}
		if(empty($data['filter_date_from']) && empty($data['filter_date_to'])){
			$end_date = date('Y-m-d', strtotime("-30 days"));
			$sql .= " AND DATE(pvo.created_at) >= '".$end_date."'";
			$sql .= " AND DATE(pvo.created_at) <= '".date('Y-m-d')."'";
		}
		$query  = $this->db->query($sql);
		
		return $query->row;
	}	
	
	public function getTotalcommission($data=array()){
		$sql  = "SELECT SUM(commission) as total FROM " . DB_PREFIX . "purpletree_vendor_commissions ";
		
		if(!empty($data['seller_id'])){
			$sql .= " WHERE seller_id ='".(int)$data['seller_id']."'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(created_at) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(created_at) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}
		if(empty($data['filter_date_from']) && empty($data['filter_date_to'])){
			$end_date = date('Y-m-d', strtotime("-30 days"));
			$sql .= " AND DATE(created_at) >= '".$end_date."'";
			$sql .= " AND DATE(created_at) <= '".date('Y-m-d')."'";
		}
		
		$query  = $this->db->query($sql);
		
		if($query->num_rows >0){
			return $query->row['total'];
		} else {
			return 0;
		}
	}
	
		public function getCurrencySymbol($currency_code){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX. "currency WHERE code='".$this->db->escape($currency_code)."'");
		return $query->row;
		}

}
?>