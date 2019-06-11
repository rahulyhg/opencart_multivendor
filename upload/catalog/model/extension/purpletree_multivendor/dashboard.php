<?php 
class ModelExtensionPurpletreeMultivendorDashboard extends Model{
	public function getTotalSellerOrders($data= array()){
		$sql = "SELECT COUNT(pvo.order_id) AS total FROM `" . DB_PREFIX . "order` o JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=o.order_id)";
		$sql.="WHERE pvo.seller_id='".(int)$data['seller_id']."' ";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalsale($data = array()) {
		$sql = "SELECT SUM(total_price) AS total FROM `" . DB_PREFIX . "purpletree_vendor_orders` WHERE seller_id ='".(int)$data['seller_id']."'";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getSellerOrders($data = array()) {
			$sql = "SELECT pvo.order_status_id AS seller_order_status_id,o.order_status_id AS admin_order_status_id,o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = pvo.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS admin_order_status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=o.order_id) ";

		$sql .= " WHERE pvo.order_status_id > 0";
		if(!empty($data['seller_id'])){
			$sql .= " AND pvo.seller_id ='".(int)$data['seller_id']."'";
		}
		$sql .= " group by o.order_id ORDER BY o.order_id DESC LIMIT 5";
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCommissions($data=array()){
		
		$sql = "SELECT pvc.*,pd.name,pvo.total_price,o.currency_code, o.currency_value FROM " . DB_PREFIX . "purpletree_vendor_commissions pvc JOIN " .DB_PREFIX. "product_description pd ON(pd.product_id=pvc.product_id) JOIN " .DB_PREFIX. "purpletree_vendor_orders pvo ON(pvo.product_id=pvc.product_id AND pvo.order_id=pvc.order_id) JOIN `" .DB_PREFIX. "order` o ON(o.order_id=pvo.order_id)";
		
		if(!empty($data['seller_id'])){
			$sql .= " WHERE pvc.seller_id ='".(int)$data['seller_id']."'";
		}

		$sql .= " GROUP BY pvc.id ORDER BY id DESC LIMIT 5";
		
		$query  = $this->db->query($sql);
		return $query->rows;
	}
	

	
	
	
	public function getCurrencySymbol($currency_code){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX. "currency WHERE code='".$this->db->escape($currency_code)."'");
		return $query->row;
	   }
	public function getSellerOrdersTotal($seller_id,$order_id){
		$query = $this->db->query("SELECT value AS total  FROM " . DB_PREFIX . "purpletree_order_total WHERE seller_id = '".(int)$seller_id."' AND order_id = '".(int)$order_id."' AND code='total'");
		return $query->row;
	}

	public function getSellerOrdersCommissionTotal($order_id,$seller_id=NULL){
		
		$sql = "SELECT SUM(commission) AS total_commission  FROM " . DB_PREFIX . "purpletree_vendor_commissions WHERE order_id = '".(int)$order_id."'";
		
		if(!empty($seller_id)){
			$sql .= " AND seller_id = '".(int)$seller_id."'";
		}
		
		$query = $this->db->query($sql);

		return $query->row;
	}
	public function getTotalSellerOrderscommission($seller_id='',$statusid = 5){
		
		 		 $sql = "SELECT pvc.commission FROM " . DB_PREFIX . "purpletree_vendor_commissions pvc JOIN " . DB_PREFIX . "purpletree_vendor_orders pvo ON(pvo.order_id=pvc.order_id AND pvo.seller_id=pvc.seller_id) JOIN `" . DB_PREFIX . "order` o ON(o.order_id=pvc.order_id AND o.order_id=pvo.order_id) WHERE pvc.seller_id = '".(int)$seller_id."' AND o.order_status_id =".$statusid." AND pvo.order_status_id = ".(int)$statusid." GROUP By pvc.id";
		 $query = $this->db->query($sql);
		 return $query->rows;
	}
	public function pendingPayments($filter_data = array()){
		
		 $sql = "SELECT pvo.order_id as order_id,pvo.total_price, pvo.order_status_id as seller_order_status,o.order_status_id AS admin_order_status FROM " . DB_PREFIX . "purpletree_vendor_orders pvo JOIN ". DB_PREFIX ."order o ON(pvo.order_id=o.order_id) WHERE pvo.seller_id = '".(int)$filter_data['seller_id']."' GROUP BY pvo.id";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}

}
?>