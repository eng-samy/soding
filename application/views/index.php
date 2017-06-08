<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Viewport metatags -->
    <meta name="HandheldFriendly" content="true" />
    <meta name="MobileOptimized" content="320" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>Soding Task</title>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/pace.css">
    <script src="<?php echo base_url(); ?>assets/js/pace.min.js"></script>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/icheck/skins/square/aero.css">

</head>
<body class="">

    <section id="main-container">

        <section id="left-navigation">

           <div class="col-md-12 col-sm-12 no-padding">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left pointer link-color main-btn" data-toggle="modal" data-target="#newTask"><i class="fa fa-plus"></i> New Task</h3>

                    <h3 class="panel-title text-center"> Main Tasks  </h3>
                </div>
                <div class="panel-heading seconad_header">
                    <h3 class="panel-title text-center"> <span class="pre_main pagination_btn hidden-btn" value="0"><i class="fa fa-angle-left"></i> Pre</span> 

                        <div class="filterDiv">
                           <input class="icheck-black filterTasks" type="radio" name="radioMain"
                           id="radioRedCheckbox5" value="3" checked="checked"> <label> ALL </label>
                       </div>    

                       <div class="filterDiv">
                        <input class="icheck-black filterTasks" type="radio" name="radioMain"
                        id="radioRedCheckbox5" value="0"> <label> In Progress </label>
                    </div>
                    <div class="filterDiv">
                        <input class="icheck-black filterTasks" type="radio" name="radioMain"
                        id="radioRedCheckbox5" value="1"> <label> Done </label>
                    </div>
                    <div class="filterDiv">
                        <input class="icheck-black filterTasks" type="radio" name="radioMain"
                        id="radioRedCheckbox5" value="2"> <label> Completed </label>
                    </div>
                    <span class="next_main hidden-btn pagination_btn" value="1"> Next <i class="fa fa-angle-right"></i> </span>  </h3>
                </div>
                <div class="panel-body no-padding">
                    <div class="nano nano-recent-activities">
                        <div class="nano-content nano-activities">
                            <div class="feed-box">
                                <ul class="ls-feed" id="main-tasks">




                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>


    <!--Page main section start-->
    <section id="min-wrapper">
        <div id="main-content">
          <div class="col-md-12 col-sm-12 no-padding">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left pointer link-color sub-btn hidden-btn" data-toggle="modal" data-target="#newSubTask"><i class="fa fa-plus"></i> New Sub Task</h3>


                    <h3 class="panel-title text-center sub_task_title"><i class="fa fa-list-ul"></i> Dependencies Tasks </h3>
                    <h3 class="panel-title pull-right pointer link-color back-btn hidden-btn"><i class="fa fa-angle-double-left"></i> Back</h3>
                    <input type="hidden" id="back_value" value="0">
                    <input type="hidden" id="save_main" value="0">
                </div>
                <div class="panel-heading seconad_header">
                    <h3 class="panel-title text-center"> <span class="pre_sub pagination_btn hidden-btn" value="0"><i class="fa fa-angle-left"></i> Pre</span> 

                       <div class="filterDiv">
                           <input class="icheck-black filterSubs" type="radio" name="radioSub"
                           id="radioRedCheckbox5" value="3" checked="checked"> <label> ALL </label>
                       </div>    

                       <div class="filterDiv">
                        <input class="icheck-black filterSubs" type="radio" name="radioSub"
                        id="radioRedCheckbox5" value="0"> <label> In Progress </label>
                    </div>
                    <div class="filterDiv">
                        <input class="icheck-black filterSubs" type="radio" name="radioSub"
                        id="radioRedCheckbox5" value="1"> <label> Done </label>
                    </div>
                    <div class="filterDiv">
                        <input class="icheck-black filterSubs" type="radio" name="radioSub"
                        id="radioRedCheckbox5" value="2"> <label> Completed </label>
                    </div>

                    <span class="next_sub hidden-btn pagination_btn" value="1"> Next <i class="fa fa-angle-right"></i> </span>  </h3>
                </div>
                <div class="panel-body no-padding">
                    <div class="nano nano-recent-activities">
                        <div class="nano-content nano-activities">
                            <div class="feed-box">
                                <ul class="ls-feed" id="sub-tasks">

                                    <h3 class="text-center empty-list">Select Main Task to display its Dependencies</h3>


                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div> 

    </div>
</div>



</section>
<!--Page main section end -->

</section>

<div class="modal fade" id="newTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="form_data" class="form-horizontal ls_form" method="post">
        <input type="hidden" id="parent_id" name="parent_id" value="0">
        <input type="hidden" id="item_id" name="item_id" value="0">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header label-primary white">
                    <button type="button" class="close close_model" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabelSuccess">Task Data</h4>
                </div>
                <div class="modal-body">
                    <div class="panel-body col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Title</label>
                            <input type="text" class="form-control" id="task_title" name="title" required="required" />
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="progess_div">
                        <button type="submit" id="insert_btn" class="btn btn-primary save_btn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="modal fade" id="newSubTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="sub_form_data" class="form-horizontal ls_form" method="post">
        <input type="hidden" name="parent_id" id="sub_parent_id" value="0">
        <input type="hidden" name="item_id" id="sub_item_id" value="0">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header label-primary white">
                    <button type="button" class="close close_model" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabelSuccess">Task Data</h4>
                </div>
                <div class="modal-body">
                    <div class="panel-body col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Title</label>
                            <input type="text" class="form-control" id="sub_task_title" name="title" required="required" />
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="sub_progess_div">
                        <button type="submit" id="sub_insert_btn" class="btn btn-primary save_btn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/lib/jquery-1.11.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/multipleAccordion.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lib/jquery.easing.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.nanoscroller.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/switchery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-switch.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.easypiechart.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-progressbar.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/layout.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/icheck.min.js"></script>
<script type="text/javascript">
    var base_url = "<?php echo base_url();  ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>

</body>
</html>