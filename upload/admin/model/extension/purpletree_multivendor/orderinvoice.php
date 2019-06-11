<?php 
class ModelExtensionPurpletreeMultivendorOrderinvoice extends Model{
	public function getsellerorder($orderid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purpletree_vendor_orders WHERE order_id = '" . (int)$orderid . "'");

		return $query->rows;
	}
	public function getStoreDetail($sellerid) {
			$query = $this->db->query("SELECT pvs.* FROM " . DB_PREFIX . "purpletree_vendor_stores pvs where pvs.seller_id='".(int)$sellerid."'");
		return $query->row;
	}
		public function getOrderProducts($order_id,$product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND product_id= '" . (int)$product_id . "'");

		return $query->row;
	}
	public function getOrderTotals($order_id,$sellerid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purpletree_order_total WHERE order_id = '" . (int)$order_id . "' AND seller_id= '" . (int)$sellerid . "' ORDER BY code");
		return $query->rows;
	}
}