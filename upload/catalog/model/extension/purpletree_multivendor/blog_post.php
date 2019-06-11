<?php
class ModelExtensionPurpletreeMultivendorBlogPost extends Model {
	public function getPost($blog_post_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "purpletree_vendor_blog_post pbp LEFT JOIN " . DB_PREFIX . "purpletree_vendor_blog_post_description pbpd ON (pbp.blog_post_id = pbpd.blog_post_id) WHERE pbp.blog_post_id = '" . (int)$blog_post_id . "' AND pbpd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pbp.status = '1'");

		return $query->row;
	}
	
	public function getTotalCategoryPost($data = array()){
		
		$sql = "SELECT COUNT(DISTINCT pbp.blog_post_id) AS total FROM " . DB_PREFIX . "purpletree_vendor_blog_post pbp
		LEFT JOIN " . DB_PREFIX . "purpletree_vendor_blog_post_description pbpd ON (pbp.blog_post_id = pbpd.blog_post_id) LEFT JOIN " . DB_PREFIX . "purpletree_vendor_blog_post_to_category pbpc ON (pbp.blog_post_id = pbpc.blog_post_id) WHERE  pbpd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pbp.status = '1'"; 
		
		if(!empty($data['blog_category_id'])){ 
			$sql .= " AND pbpc.blog_category_id ='".$data['blog_category_id']."' ";
		}
		
		$sql .= "ORDER BY pbp.sort_order, LCASE(pbp.sort_order) ASC";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
		
	}

	public function getCategoryPost($data = array()) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "purpletree_vendor_blog_post pbp LEFT JOIN " . DB_PREFIX . "purpletree_vendor_blog_post_description pbpd ON (pbp.blog_post_id = pbpd.blog_post_id) LEFT JOIN " . DB_PREFIX . "purpletree_vendor_blog_post_to_category pbpc ON (pbp.blog_post_id = pbpc.blog_post_id) WHERE pbpd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pbp.status = '1'";
		
		if(!empty($data['blog_category_id'])){
			$sql .= " AND pbpc.blog_category_id ='".$data['blog_category_id']."' ";
		}
		
		$sql .= " GROUP BY pbp.blog_post_id";
		
		$sql .= " ORDER BY pbp.sort_order, LCASE(pbp.sort_order) ASC";
		
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
	
	public function getPostCategories($blog_post_id){
		$query = $this->db->query("SELECT pbcd.name, pbcd.blog_category_id FROM " . DB_PREFIX . "purpletree_vendor_blog_post_description pbcd LEFT JOIN " . DB_PREFIX . "purpletree_vendor_blog_post_to_category pbpc ON (pbcd.blog_category_id = pbpc.blog_category_id) WHERE pbpc.blog_post_id = '".$blog_post_id."' AND pbcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;
	}
	
	public function getPostComments($blog_post_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purpletree_vendor_blog_post_comment WHERE blog_post_id = '".(int)$blog_post_id."' AND status ='1'");
		
		return $query->rows;
	}
	
	public function addComment($blog_post_id, $data){
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "purpletree_vendor_blog_post_comment SET name = '" . $this->db->escape($data['name']) . "', email_id = '" . $this->db->escape($data['email_id']) . "', blog_post_id = '" . (int)$blog_post_id . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '0', created_at = NOW(),  updated_at = NOW()");
	}
	
	public function getTotalCategories(){
		
		$query = $this->db->query("SELECT bc.blog_category_id, bcd.name FROM " . DB_PREFIX . "purpletree_vendor_blog_post_to_category  bc JOIN " . DB_PREFIX . "purpletree_vendor_blog_category_description bcd ON bc.blog_category_id = bcd.blog_category_id AND bc.parent_id = 0");
		
		return $query->rows;
	}
	
	public function getTotalChild($parent){
		
		$query = $this->db->query("SELECT bc.blog_category_id, bcd.name FROM " . DB_PREFIX . "purpletree_vendor_blog_post_to_category  bc JOIN " . DB_PREFIX . "purpletree_vendor_blog_category_description bcd ON bc.blog_category_id = bcd.blog_category_id AND bc.parent_id = '" .$parent. "'");
		
		return $query->rows;
	}
	
	public function getPopularBlog($limit){

		$query = $this->db->query("SELECT pbpd.blog_post_id, (SELECT count(pbpc.blog_comment_id) FROM " . DB_PREFIX . "purpletree_vendor_blog_post_comment pbpc WHERE pbpc.blog_post_id = pbpd.blog_post_id GROUP BY pbpc.blog_post_id) as total_comments, pbp.*, pbpd.title FROM " . DB_PREFIX . "purpletree_vendor_blog_post_description pbpd LEFT JOIN " . DB_PREFIX . "purpletree_vendor_blog_post pbp ON (pbp.blog_post_id = pbpd.blog_post_id) WHERE pbpd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY total_comments DESC LIMIT " . (int)$limit);
		
		return $query->rows;

	}
	
	public function getPopularTags(){

		$query = $this->db->query("SELECT pbpd.blog_post_id, (SELECT count(pbpc.blog_comment_id) FROM " . DB_PREFIX . "purpletree_vendor_blog_post_comment pbpc WHERE pbpc.blog_post_id = pbpd.blog_post_id GROUP BY pbpc.blog_post_id) as total_comments, pbpd.post_tags FROM " . DB_PREFIX . "purpletree_vendor_blog_post_description pbpd WHERE  pbpd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY total_comments DESC LIMIT 15");
		
		return $query->rows;

	}

}