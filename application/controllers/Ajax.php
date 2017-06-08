<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function index()
	{
		echo "Forrbidden";
	}

	public function insert_data(){
		$data = array();
		foreach ($_POST as $key => $value) {
			$data[$key] = $value;
		}
		echo $this->_insert_data($data);
	}

	function _insert_data($data){
		if ($data['item_id'] == 0) {
			unset($data['item_id']);
			$this->db->insert('tasks', $data);
			$inserted_id = $this->db->insert_id();

			$result = array('inserted_id' => $inserted_id, 'status' => 'ok');
			echo json_encode($result);
			exit();
		}else{
			$id = $data['item_id'];
			unset($data['item_id']);
			$this->db->where('id', $id);
			$this->db->update('tasks', $data);
			$result = array('inserted_id' => $id, 'status' => 'ok');
			echo json_encode($result);
			exit();
		}
	}

	public function insert_sub_data(){
		$data = array();
		foreach ($_POST as $key => $value) {
			$data[$key] = $value;
		}
		echo $this->_insert_sub_data($data);
	}

	function _insert_sub_data($data){
		if ($data['item_id'] == 0) {
			unset($data['item_id']);
			$this->db->insert('tasks', $data);
			$inserted_id = $this->db->insert_id();
			$this->Main_model->add_effect($data['parent_id']);
			$result = array('inserted_id' => $inserted_id, 'status' => 'ok');
			echo json_encode($result);
			exit();
		}else{
			$id = $data['item_id'];
			unset($data['item_id']);
			$this->db->where('id', $id);
			$this->db->update('tasks', $data);
			$result = array('inserted_id' => $id, 'status' => 'ok');
			echo json_encode($result);
			exit();
		}
	}



	public function get_tasks(){
		$oper = $this->input->post('oper');
		$filter = $this->input->post('filter');
		if(!get_cookie('offset')){
			$offset = 0;
		}else{
			$offset = get_cookie('offset');	
		}

		switch ($oper) {
			case 'inc':
			$offset = $offset+1;
			break;

			case 'dec':
			$offset = $offset-1;
			break;	
			
			default:
			$offset = $offset;
			break;
		}

		$tasks = $this->Main_model->get_tasks(0,20,$offset*20,$filter);
		$data = '';
		foreach ($tasks as $task) {
			switch ($task->status) {
				case 0:
				$status = '<span class="badge badge-yellow pull-right">In Progress</span>';
				$label = '<label class="white-bg"><input class="icheck-red done-btn" type="checkbox" value="option1"></label>';
				break;

				case 1:
				$status = '<span class="badge badge-green pull-right">Done</span>';
				$label = '<span class="label label-green"><i class="fa fa-arrow-up white"></i></span>';
				break;

				case 2:
				$status = '<span class="badge badge-lightBlue pull-right">Complete</span>';
				$label = '<span class="label label-lightBlue"><i class="fa fa-check white"></i></span>';
				break;		

				default:
				$status = '<span class="badge badge-yellow pull-right">In Progress</span>';
				$label = '<label class="white-bg"><input class="icheck-red done-btn" type="checkbox" value="option1"></label>';
				break;
			}

			$is_parent = $this->Main_model->is_parent($task->id);

			if($task->status != 0 && !$is_parent){
				$hidden_class = "";
			}else{
				$hidden_class = "hidden-btn";
			}
			
			$dropDown = '<div class="btn-group pull-right">
			<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-gear"></i> Action <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a class="edit_btn_main"><i class="fa fa-pencil-square-o"></i> Edit</a></li>
				<li id="redoLi_'.$task->id.'" class="'.$hidden_class.' "><a class="undo_btn"><i class="fa fa-reply"></i> Undo</a></a></li>
				<li><a class="remove_btn"><i class="fa fa-trash-o"></i> Remove</a></li>
			</ul>
		</div>';

		$data .='<li class="task-li pointer" id="'.$task->id.'"><div class="check_task">'.$label.'</div><span class="task-title">'.$task->title.' </span><span class="taskNums">('.$task->tasks_num.')</span>'.$dropDown.'<div class="status_div">'.$status.'</div></li>';
	}

	$cookie = array(
		'name'   => 'offset',
		'value'  => $offset,
		'expire' => 86500,
		);
	set_cookie($cookie);

	$count = $this->Main_model->get_task_count(0);
	$offset_count = $count / 20;
	$offset_floor = floor($offset_count);

	if($offset_floor <= $offset || $offset_count == 1){
		$is_next = 0;
	}else{
		$is_next = 1;
	}

	if($offset == 0){
		$is_pre = 0;
	}else{
		$is_pre = 1;
	}
	$result = array('offset'=>get_cookie('offset'),'data' => $data, 'status' => 'ok','is_next'=>$is_next,'is_pre'=>$is_pre);
	
	echo json_encode($result);
	exit();
}

public function get_subs(){
	$filter = $this->input->post('filter');
	$oper = $this->input->post('oper');
	if(!get_cookie('sub_offset')){
		$offset = 0;
	}else{
		$offset = get_cookie('sub_offset');	
	}

	switch ($oper) {
		case 'inc':
		$offset = $offset+1;
		break;

		case 'dec':
		$offset = $offset-1;
		break;	

		default:
		$offset = $offset;
		break;
	}

	$parent_id = $this->input->post('parent_id');
	$tasks = $this->Main_model->get_tasks($parent_id,20,$offset*20,$filter);
	$current_task = $this->Main_model->get_task($parent_id);
	$data = '';
	if(isset($tasks) && count($tasks) != 0){
		foreach ($tasks as $task) {
			switch ($task->status) {
				case 0:
				$status = '<span class="badge badge-yellow pull-right">In Progress</span>';
				$label = '<label class="white-bg"><input class="icheck-red sub-done-btn" type="checkbox" value="option1"></label>';
				break;

				case 1:
				$status = '<span class="badge badge-green pull-right">Done</span>';
				$label = '<span class="label label-green"><i class="fa fa-arrow-up white"></i></span>';
				break;

				case 2:
				$status = '<span class="badge badge-lightBlue pull-right">Complete</span>';
				$label = '<span class="label label-lightBlue"><i class="fa fa-check white"></i></span>';
				break;		

				default:
				$status = '<span class="badge badge-yellow pull-right">In Progress</span>';
				$label = '<label class="white-bg"><input class="icheck-red sub-done-btn" type="checkbox" value="option1"></label>';
				break;
			}

			$is_parent = $this->Main_model->is_parent($task->id);

			if($task->status != 0 && !$is_parent){
				$hidden_class = "";
			}else{
				$hidden_class = "hidden-btn";
			}

			
			$dropDown = '<div class="btn-group pull-right">
			<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-gear"></i> Action <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a class="edit_btn_sub"><i class="fa fa-pencil-square-o"></i> Edit</a></li>
				<li id="redoLi_'.$task->id.'" class="'.$hidden_class.' "><a class="undo_btn_sub"><i class="fa fa-reply"></i> Undo</a></a></li>
				<li><a class="remove_btn_sub"><i class="fa fa-trash-o"></i> Remove</a></li>
			</ul>
		</div>';

		$data .='<li class="sub-task-li pointer" id="'.$task->id.'"><div class="check_task">'.$label.'</div><span class="task-title">'.$task->title.'</span> <span class="taskNums">('.$task->tasks_num.')</span>'.$dropDown.'<div class="status_div">'.$status.'</div></li>';
	}
}else{
	$data .= ' <h3 class="text-center empty-list">There are no Dependencies Tasks</h3>';
}

$cookie = array(
	'name'   => 'sub_offset',
	'value'  => $offset,
	'expire' => 86500,
	);
set_cookie($cookie);

$count = $this->Main_model->get_task_count($parent_id);
$offset_count = $count / 20;
$offset_floor = floor($offset_count);

if($offset_floor <= $offset || $offset_count == 1){
	$is_next = 0;
}else{
	$is_next = 1;
}

if($offset == 0){
	$is_pre = 0;
}else{
	$is_pre = 1;
}
$result = array('offset'=>$offset,'data' => $data, 'status' => 'ok', 'count'=>count($tasks), 'parent_id'=>$current_task->parent_id , 'parent_title'=>$current_task->title,'is_next'=>$is_next,'is_pre'=>$is_pre);
echo json_encode($result);
exit();
}

public function done_action(){
	$task_id = $this->input->post('task_id');
	$main_task = $this->input->post('main_task');
	$is_complete = $this->Main_model->is_complete($task_id);
	if($is_complete){
		$this->Main_model->completeTask($task_id);
		$this->Main_model->is_effect($task_id);
		$status = '<span class="badge badge-lightBlue pull-right">Complete</span>';
		$label = '<span class="label label-lightBlue"><i class="fa fa-check white"></i></span>';
		$is_redo = 1;
	}else{
		$this->Main_model->doneTask($task_id);
		$status = '<span class="badge badge-green pull-right">Done</span>';
		$label = '<span class="label label-green"><i class="fa fa-arrow-up white"></i></span>';
		$is_redo = 0;
	}

	$is_main_complete = $this->Main_model->is_complete($main_task);
	if($is_main_complete){
		$this->Main_model->completeTask($main_task);
	}else{
		$this->Main_model->doneTask($main_task);
	}
	$main_task_status = $this->Main_model->get_task_status($main_task);

	if($task_id == $main_task){
		$btn = 'done-btn';
	}else{
		$btn = 'sub-done-btn';
	}
	switch ($main_task_status) {
		case 0:
		$main_status_result = '<span class="badge badge-yellow pull-right">In Progress</span>';
		$main_label = '<label class="white-bg"><input class="icheck-red '.$btn.'" type="checkbox" value="option1"></label>';
		break;

		case 1:
		$main_status_result = '<span class="badge badge-green pull-right">Done</span>';
		$main_label = '<span class="label label-green"><i class="fa fa-arrow-up white"></i></span>';
		break;

		case 2:
		$main_status_result = '<span class="badge badge-lightBlue pull-right">Complete</span>';
		$main_label = '<span class="label label-lightBlue"><i class="fa fa-check white"></i></span>';
		break;		

		default:
		$main_status_result = '<span class="badge badge-yellow pull-right">In Progress</span>';
		$main_label = '<label class="white-bg"><input class="icheck-red '.$btn.'" type="checkbox" value="option1"></label>';
		break;
	}

	$result = array('status' => 'ok', 'status_label'=>$status, 'label'=>$label ,  'main_status_result' =>$main_status_result, 'main_label'=>$main_label,'is_redo'=>$is_redo);
	echo json_encode($result);
	exit();
}

public function undo_action(){
	$task_id = $this->input->post('task_id');
	$main_task = $this->input->post('main_task');
	$this->Main_model->redoTask($task_id);
	$this->Main_model->is_effect($task_id);

	if($task_id == $main_task){
		$btn = 'done-btn';
	}else{
		$btn = 'sub-done-btn';
	}
	$status = '<span class="badge badge-yellow pull-right">In Progress</span>';
	$label = '<label class="white-bg"><input class="icheck-red '.$btn.'" type="checkbox" value="option1"></label>';

	$main_task_status = $this->Main_model->get_task_status($main_task);

	switch ($main_task_status) {
		case 0:
		$main_status_result = '<span class="badge badge-yellow pull-right">In Progress</span>';
		$main_label = '<label class="white-bg"><input class="icheck-red '.$btn.'" type="checkbox" value="option1"></label>';
		break;

		case 1:
		$main_status_result = '<span class="badge badge-green pull-right">Done</span>';
		$main_label = '<span class="label label-green"><i class="fa fa-arrow-up white"></i></span>';
		break;

		case 2:
		$main_status_result = '<span class="badge badge-lightBlue pull-right">Complete</span>';
		$main_label = '<span class="label label-lightBlue"><i class="fa fa-check white"></i></span>';
		break;		

		default:
		$main_status_result = '<span class="badge badge-yellow pull-right">In Progress</span>';
		$main_label = '<label class="white-bg"><input class="icheck-red '.$btn.'" type="checkbox" value="option1"></label>';
		break;
	}

	$result = array('status' => 'ok', 'status_label'=>$status, 'label'=>$label ,  'main_status_result' =>$main_status_result, 'main_label'=>$main_label);
	echo json_encode($result);
	exit();
}

public function undo_main_action(){
	$task_id = $this->input->post('task_id');
	$this->Main_model->redoTask($task_id);
	$btn = 'done-btn';

	$status = '<span class="badge badge-yellow pull-right">In Progress</span>';
	$label = '<label class="white-bg"><input class="icheck-red '.$btn.'" type="checkbox" value="option1"></label>';

	$result = array('status' => 'ok', 'status_label'=>$status, 'label'=>$label);
	echo json_encode($result);
	exit();
}

public function effect(){
	$task_id = $this->input->get('task_id');
	$is_effect = $this->Main_model->is_effect($task_id);
	print_r($is_effect);
}

public function remove_task(){
	$task_id = $this->input->post('task_id');
	$parent_id = $this->Main_model->get_parent($task_id);
	$main_task = $this->input->post('main_task');
	$this->db->where('id', $task_id);
	$this->db->delete('tasks');

	$this->Main_model->is_effect_delete($parent_id);

	$main_task_status = $this->Main_model->get_task_status($main_task);

	if($task_id == $main_task){
		$btn = 'done-btn';
	}else{
		$btn = 'sub-done-btn';
	}
	switch ($main_task_status) {
		case 0:
		$main_status_result = '<span class="badge badge-yellow pull-right">In Progress</span>';
		$main_label = '<label class="white-bg"><input class="icheck-red '.$btn.'" type="checkbox" value="option1"></label>';
		break;

		case 1:
		$main_status_result = '<span class="badge badge-green pull-right">Done</span>';
		$main_label = '<span class="label label-green"><i class="fa fa-arrow-up white"></i></span>';
		break;

		case 2:
		$main_status_result = '<span class="badge badge-lightBlue pull-right">Complete</span>';
		$main_label = '<span class="label label-lightBlue"><i class="fa fa-check white"></i></span>';
		break;		

		default:
		$main_status_result = '<span class="badge badge-yellow pull-right">In Progress</span>';
		$main_label = '<label class="white-bg"><input class="icheck-red '.$btn.'" type="checkbox" value="option1"></label>';
		break;
	}

	$result = array('status' => 'ok','main_status_result' =>$main_status_result, 'main_label'=>$main_label);
	echo json_encode($result);
	exit();

}

public function remove_main_task(){
	$task_id = $this->input->post('task_id');
	$this->db->where('id', $task_id);
	$this->db->delete('tasks');
	$message = '<h3 class="text-center empty-list">Select Main Task to display its Dependencies</h3>';

	$result = array('status' => 'ok','message' => $message);
	echo json_encode($result);
	exit();

}

}
