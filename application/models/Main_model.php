<?php 
class Main_model extends CI_Model {

	public function get_tasks($parent,$limit,$offset,$filter){
		$this->db->select('c.id,c.title,c.status,c.parent_id,COUNT(s.id) as tasks_num');
		$this->db->from('tasks as c');
		$this->db->join('tasks as s', 'c.id = s.parent_id', 'left');
		$this->db->group_by('c.id');
		$this->db->where('c.parent_id', $parent);
		if($filter != 3){
			$this->db->where('c.status', $filter);
		}
		$this->db->order_by('c.id', 'desc');
		$this->db->limit($limit, $offset);
		$results = $this->db->get();
		return $results->result();
	}

	public function get_task($id){
		$this->db->where('id', $id);
		$results = $this->db->get('tasks');
		return $results->row();
	}

	public function get_task_count($id){
		$this->db->where('parent_id', $id);
		$this->db->from('tasks');
		$results = $this->db->count_all_results();
		return $results;
	}

	function get_task_status($id){
		$this->db->select('status');
		$this->db->where('id', $id);
		$results = $this->db->get('tasks');
		return $results->row()->status;
	}

	function is_parent($id){
		$this->db->where('parent_id', $id);
		$this->db->from('tasks');
		$results = $this->db->count_all_results();
		if($results != 0){
			return true;
		}else{
			return false;
		}
	}



	public function related_object($parent_id = 0) {
		$categories = array();
		$this->db->from('tasks');
		$this->db->where('parent_id', $parent_id);
		$result = $this->db->get()->result();
		foreach ($result as $mainCategory) {
			$category = array();
			$category['status'] = 's_'.$mainCategory->status;
			$category['sub_categories'] = $this->related_object($mainCategory->id);
			$categories[$mainCategory->id] = $category;
		}
		return $categories;
	}

	public function is_effect($id) {
		$categories = array();
		$this->db->from('tasks');
		$this->db->where('id', $id);
		$result = $this->db->get()->result();
		foreach ($result as $mainCategory) {
			$category = array();
			$category['status'] = $mainCategory->status;
			$category['id'] = $mainCategory->id;
			$category['parent_id'] = $mainCategory->parent_id;
			$is_complete = $this->is_complete($mainCategory->parent_id);
			if($is_complete){
				$this->completeTask($mainCategory->parent_id);
			}else{
				$this->doneTask($mainCategory->parent_id);
			}
			$category['parent_categories'] = $this->is_effect($category['parent_id']);
			$categories[$mainCategory->id] = $category;
		}
		return $categories;
	}

	public function is_effect_delete($id) {
		$categories = array();
		$this->db->from('tasks');
		$this->db->where('id', $id);
		$result = $this->db->get()->result();
		foreach ($result as $mainCategory) {
			$category = array();
			$category['status'] = $mainCategory->status;
			$category['id'] = $mainCategory->id;
			$category['parent_id'] = $mainCategory->parent_id;
			$is_complete = $this->is_complete($mainCategory->id);
			if($is_complete){
				$this->completeTask($mainCategory->id);
			}
			$category['parent_categories'] = $this->is_effect_delete($category['parent_id']);
			$categories[$mainCategory->id] = $category;
		}
		return $categories;
	}

	function add_effect($id){
		$this->db->from('tasks');
		$this->db->where('id', $id);
		$result = $this->db->get()->result();
		foreach ($result as $mainCategory) {
			$status = $mainCategory->status;
			$parent_id = $mainCategory->parent_id;
			if($status == 2){
				$this->doneTask($mainCategory->id);
				$this->add_effect($parent_id);
			}else{
				return false;
			}

		}
	}

	public function completeTask($id){
		$this->db->where('id', $id);
		$this->db->update('tasks', array('status'=>2));
	}

	public function doneTask($id){
		$this->db->where('id', $id);
		$this->db->update('tasks', array('status'=>1));
	}

	public function redoTask($id){
		$this->db->where('id', $id);
		$this->db->update('tasks', array('status'=>0));
	}

	public function search_on_array( $needle, $haystack, $strict=false, $path=array() )
	{
		if( !is_array($haystack) ) {
			return false;
		}

		foreach( $haystack as $key => $val ) {
			if( is_array($val) && $subPath = $this->search_on_array($needle, $val, $strict, $path) ) {
				$path = array_merge($path, array($key), $subPath);
				return $path;
			} elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
				$path[] = $key;
				return $path;
			}
		}
		return false;
	}

	public function is_complete($parent_id = 0) {
		$related_object = $this->related_object($parent_id);
		$search_for_proccess_status = $this->search_on_array('s_0',$related_object);
		if(!$search_for_proccess_status){
			return true;
		}else{
			return false;
		}
	}

	public function get_parent($id){
		$this->db->select('parent_id');
		$this->db->where('id', $id);
		$query = $this->db->get('tasks');
		return $query->row()->parent_id;

	}

}
?>