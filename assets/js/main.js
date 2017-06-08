$(document).ready(function() {
	get_tasks();
	checkbox();
});

$("#form_data").on('submit',(function(e) {
	jQuery( '.progess_div' ).html('<h4 class="text-light-blue oper_status"><img src="'+base_url+'assets/images/ajax-loader.gif" style="width:20px;height:20px"> Saving</h4>');		 	
	e.preventDefault();
	$.ajax({
		url: base_url+"ajax/insert_data",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		dataType: "json",
		success: function(data)
		{
			if(data.status == 'ok'){
				setTimeout(
					function() 
					{	
						jQuery( '.oper_status' ).html('<span class="green">Saved</span>');
						setTimeout(
							function() 
							{	
								jQuery('.close_model').click();
								get_tasks();
								checkbox();
								jQuery('.form-control').val('');
								jQuery('#item_id').val(0);
								jQuery( '.progess_div' ).html('<button type="submit" id="insert_btn" class="btn btn-primary save_btn">Save</button>');
							}, 1000);
					}, 1000);
			}
		},
		error: function() 
		{
		} 	        
	});
}));


$("#sub_form_data").on('submit',(function(e) {
	jQuery( '.sub_progess_div' ).html('<h4 class="text-light-blue sub_oper_status"><img src="'+base_url+'assets/images/ajax-loader.gif" style="width:20px;height:20px"> Saving</h4>');		 	
	e.preventDefault();
	$.ajax({
		url: base_url+"ajax/insert_sub_data",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		dataType: "json",
		success: function(data)
		{
			if(data.status == 'ok'){
				setTimeout(
					function() 
					{	
						jQuery( '.sub_oper_status' ).html('<span class="green">Saved</span>');
						setTimeout(
							function() 
							{	
								jQuery('.close_model').click();
								jQuery('.form-control').val('');
								jQuery('#sub_item_id').val(0);
								jQuery( '.sub_progess_div' ).html('<button type="submit" id="sub_insert_btn" class="btn btn-primary save_btn">Save</button>');
								get_subs(jQuery('#sub_parent_id').val());
								checkbox();
							}, 1000);
					}, 1000);
			}
		},
		error: function() 
		{
		} 	        
	});
}));


function get_tasks(oper = 'none', filter = 3){
	jQuery('#main-tasks').html('<div class="page-loader"><img src="'+base_url+'assets/images/page_loader.gif"></div>');
	jQuery.post(base_url+"ajax/get_tasks" ,{oper:oper , filter:filter}, function(data){
		if(data.status == 'ok'){
			jQuery('#main-tasks').html(data.data);
			checkbox();
			click_on_main();
			edit_main_click();
			done_action();
			remove_click();
			undo_sub_click();
			filterTasks();
			filterSubs();
			undo_click();
			if(data.is_next != 1){
				jQuery('.next_main').addClass('hidden-btn');
			}else{
				jQuery('.next_main').removeClass('hidden-btn');
			}

			if(data.is_pre != 1){
				jQuery('.pre_main').addClass('hidden-btn');
			}else{
				jQuery('.pre_main').removeClass('hidden-btn');
			}

			if(jQuery('#save_main').val() != 0){
				var task_id = jQuery('#save_main').val();
				jQuery("#"+task_id).addClass('active');
			}
		}
	},"json");	

}

function checkbox(){
	$('input.icheck-red').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });

	$('input.icheck-black').iCheck({
		checkboxClass: 'icheckbox_square-aero',
		radioClass: 'iradio_square-aero',
        increaseArea: '20%' // optional
    });
}

function click_on_main(){
	$('.task-li').each( function(){
		var item = this ;
		jQuery("#"+item.id).click(function(e){
			eventTarget = e.target;
			eventTargetClass = $(eventTarget).attr('class');
			if (eventTarget == this || eventTargetClass == "task-title" ) {
				jQuery('.task-li').removeClass('active');
				jQuery(item).addClass('active');
				jQuery('#save_main').val(item.id);
				get_subs( item.id );
			}			   
		});	
	});
}

function get_subs(id,oper = 'none',filter = 3){
	jQuery('#sub-tasks').html('<div class="page-loader"><img src="'+base_url+'assets/images/page_loader.gif"></div>');
	jQuery.post(base_url+"ajax/get_subs" ,{parent_id:id,oper:oper,filter:filter}, function(data){
		if(data.status == 'ok'){

			if(data.is_next != 1){
				jQuery('.next_sub').addClass('hidden-btn');
			}else{
				jQuery('.next_sub').removeClass('hidden-btn');
			}

			if(data.is_pre != 1){
				jQuery('.pre_sub').addClass('hidden-btn');
			}else{
				jQuery('.pre_sub').removeClass('hidden-btn');
			}

			jQuery('.sub-btn').removeClass('hidden-btn');
			if(data.parent_id != 0){
				jQuery('.back-btn').removeClass('hidden-btn');
				jQuery('#back_value').val(data.parent_id);
			}else{
				jQuery('.back-btn').addClass('hidden-btn');
			}
			jQuery('#sub_parent_id').val(id);
			jQuery('.sub_task_title').html('<i class="fa fa-list-ul"></i> '+data.parent_title);
			jQuery('#sub-tasks').html(data.data);
			jQuery('li#'+id+" .taskNums").html("("+data.count+")");
			checkbox();
			sub_done_action();
			click_on_sub();
			edit_sub_click();
			done_action();
			remove_sub_click();
			undo_sub_click();
			filterTasks();
			filterSubs();
		}
	},"json");
}


function click_on_sub(){
	$('.sub-task-li').each( function(){
		var item = this ;
		jQuery("#"+item.id).click(function(e){
			eventTarget = e.target;
			eventTargetClass = $(eventTarget).attr('class');
			if (eventTarget == this || eventTargetClass == "task-title" ) {
				jQuery('.sub-task-li').removeClass('active');
				jQuery(item).addClass('active');
				get_subs( item.id );
			}			   
		});	
	});
}

jQuery(".back-btn").on('click',function(){
	get_subs(jQuery('#back_value').val());
});

function edit_main_click(){
	$('.edit_btn_main').each( function(){
		var item = this ;
		jQuery(item).click(function(){
			var task_id = jQuery(item).closest('li.task-li').attr('id');
			var task_title = jQuery('li#'+task_id+" .task-title").html();
			jQuery('#task_title').val(task_title);
			jQuery('#item_id').val(task_id);
			jQuery('.main-btn').click(); 
		});	
	});
}

function edit_sub_click(){
	$('.edit_btn_sub').each( function(){
		var item = this ;
		jQuery(item).click(function(){
			var task_id = jQuery(item).closest('li.sub-task-li').attr('id');
			var task_title = jQuery('li#'+task_id+" .task-title").html();
			jQuery('#sub_task_title').val(task_title);
			jQuery('#sub_item_id').val(task_id);
			jQuery('.sub-btn').click(); 

		});	
	});
}


function remove_click(){
	$('.remove_btn').each( function(){
		var item = this ;
		jQuery(item).click(function(){
			bootbox.confirm("Are you sure?", function(result) {
				if(result){
					var task_id = jQuery(item).closest('li.task-li').attr('id');
					jQuery.post(base_url+"ajax/remove_main_task" ,{task_id:task_id}, function(data){
						if(data.status == 'ok'){
							jQuery('li#'+task_id).hide(500);
							jQuery('#sub-tasks').html(data.message);
							jQuery('.back-btn').addClass('hidden-btn');
							jQuery('.sub-btn').addClass('hidden-btn');
							jQuery('.sub_task_title').html('<i class="fa fa-list-ul"></i> Dependencies Tasks ');
						}
					},"json"); 
				}
			});
		});	
	});
}

function remove_sub_click(){
	$('.remove_btn_sub').each( function(){
		var item = this ;
		jQuery(item).click(function(){
			bootbox.confirm("Are you sure?", function(result) {
				if(result){
					var task_id = jQuery(item).closest('li.sub-task-li').attr('id');
					var main_task = jQuery('#save_main').val();
					jQuery.post(base_url+"ajax/remove_task" ,{task_id:task_id,main_task:main_task}, function(data){
						if(data.status == 'ok'){
							jQuery('li#'+task_id).hide(500);
							jQuery('li#'+main_task+" .status_div").html(data.main_status_result);
							jQuery('li#'+main_task+" .check_task").html(data.main_label);
						}
					},"json");
				}
			});
		});	
	});
}

function undo_sub_click(){
	$('.undo_btn_sub').each( function(){
		var item = this ;
		jQuery(item).click(function(){
			var task_id = jQuery(item).closest('li.sub-task-li').attr('id');
			var main_task = jQuery('#save_main').val();
			jQuery.post(base_url+"ajax/undo_action" ,{task_id:task_id,main_task:main_task}, function(data){
				if(data.status == 'ok'){
					jQuery('li#'+task_id+" .status_div").html(data.status_label);
					jQuery('li#'+task_id+" .check_task").html(data.label);
					jQuery('li#'+main_task+" .status_div").html(data.main_status_result);
					jQuery('li#'+main_task+" .check_task").html(data.main_label);
					jQuery('#redoLi_'+task_id).addClass('hidden-btn');
					checkbox();
					sub_done_action();
					filterTasks();
					filterSubs();
				}
			},"json");
		});	
	});
}

function undo_click(){
	$('.undo_btn').each( function(){
		var item = this ;
		jQuery(item).click(function(){
			var task_id = jQuery(item).closest('li.task-li').attr('id');
			jQuery.post(base_url+"ajax/undo_main_action" ,{task_id:task_id}, function(data){
				if(data.status == 'ok'){
					jQuery('li#'+task_id+" .status_div").html(data.status_label);
					jQuery('li#'+task_id+" .check_task").html(data.label);
					jQuery('#redoLi_'+task_id).addClass('hidden-btn');
					checkbox();
					done_action();
					filterTasks();
					filterSubs();
				}
			},"json");
		});	
	});
}

$('#newTask').on('hidden.bs.modal', function () {
	jQuery('.form-control').val('');
	jQuery('#item_id').val(0);
})

$('#newSubTask').on('hidden.bs.modal', function () {
	jQuery('.form-control').val('');
	jQuery('#sub_item_id').val(0);
})

function done_action(){
	$('.done-btn').each( function(){
		var item = this ;
		$(item).on('ifChecked', function(event){
			var task_id = jQuery(item).closest('li').attr('id');
			var main_task = task_id;
			jQuery.post(base_url+"ajax/done_action" ,{task_id:task_id,main_task:main_task}, function(data){
				if(data.status == 'ok'){
					jQuery('li#'+task_id+" .status_div").html(data.status_label);
					jQuery('li#'+task_id+" .check_task").html(data.label);
					if(data.is_redo == 1){
						jQuery('#redoLi_'+task_id).removeClass('hidden-btn');
						undo_sub_click();
					}
					filterTasks();
					filterSubs();
				}
			},"json");
		});
	});
}

function sub_done_action(){
	$('.sub-done-btn').each( function(){
		var item = this ;
		$(item).on('ifChecked', function(event){
			var task_id = jQuery(item).closest('li').attr('id');
			var main_task = jQuery('#save_main').val();
			jQuery.post(base_url+"ajax/done_action" ,{task_id:task_id, main_task:main_task}, function(data){
				if(data.status == 'ok'){
					jQuery('li#'+task_id+" .status_div").html(data.status_label);
					jQuery('li#'+task_id+" .check_task").html(data.label);
					jQuery('li#'+main_task+" .status_div").html(data.main_status_result);
					jQuery('li#'+main_task+" .check_task").html(data.main_label);
					if(data.is_redo == 1){
						jQuery('#redoLi_'+task_id).removeClass('hidden-btn');
						undo_sub_click();
					}
				}
			},"json");
		});
	});
}


$('.next_main').on('click', function () {
	get_tasks('inc');
})

$('.pre_main').on('click', function () {
	get_tasks('dec');
})


$('.next_sub').on('click', function () {
	sub_parent_id = jQuery("#sub_parent_id").val();
	get_subs(sub_parent_id,'inc');
})

$('.pre_sub').on('click', function () {
	sub_parent_id = jQuery("#sub_parent_id").val();
	get_subs(sub_parent_id,'dec');
})

function filterTasks(){
	$('.filterTasks').each( function(){
		var item = this ;
		$(item).on('ifChecked', function(event){
			var filterby = jQuery(item).val();
			get_tasks('none',filterby);
		});
	});
}

function filterSubs(){
	$('.filterSubs').each( function(){
		var item = this ;
		$(item).on('ifChecked', function(event){
			sub_parent_id = jQuery("#sub_parent_id").val();
			var filterby = jQuery(item).val();
			get_subs(sub_parent_id,'none',filterby);
		});
	});
}
