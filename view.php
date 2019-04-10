<?php
   $base_url = $this->config->item('base_url');
   $admin_base_url = $this->config->item('admin_base_url');
   $page = "email_broadcast";
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <!--<![endif]-->
   <!-- BEGIN HEAD -->
   <head>
      <meta charset="utf-8" />
      <title>MyDMK | Email Broadcast</title>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta content="width=device-width, initial-scale=1" name="viewport" />
      <meta content="" name="description" />
      <meta content="" name="author" />
      <!-- BEGIN GLOBAL MANDATORY, THEME GLOBAL, THEME LAYOUT STYLES -->
      <?php include('includes/common-styles.php'); ?>
      <!-- END GLOBAL MANDATORY, THEME GLOBAL, THEME LAYOUT STYLES -->
      <!-- BEGIN PAGE LEVEL PLUGINS FOR EDIT EVENT PAGE -->
      <link href="<?php echo $base_url; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
      <link href="<?php echo $base_url; ?>assets/global/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css" />
      <link href="<?php echo $base_url; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
      <link href="<?php echo $base_url; ?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
      <link href="<?php echo $base_url; ?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
      <!-- END PAGE LEVEL PLUGINS FOR EDIT EVENT PAGE -->
      <!-- Summernote CSS -->
      <link href="<?php echo $base_url; ?>assets/global/plugins/bootstrap-summernote/summernote.css" rel="stylesheet" type="text/css" />
      <!-- Summernote CSS -->
      <!-- BEGIN COMMON POPUPS -->
      <style type="text/css">
      .note-group-select-from-files {
        display: none;
      }
      .note-btn-group .btn-group{
         display: none;
      }
      .note-btn-group .btn-group .note-insert{
         display: none;
      }
      </style>
      <?php include('includes/common-popups.php'); ?>
      <!-- END COMMON POPUPS -->
   </head>
   <!-- END HEAD -->
   <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
      <!-- BEGIN HEADER -->
      <div id="loader" style="display: none;"><img src="<?php echo base_url(); ?>assets/pages/img/loader.gif" width="45" height="45" align="absmiddle">&nbsp;<span id="loadertext">Loading...</span></div>
      <?php include('includes/header.php'); ?>
      <!-- END HEADER --> 
      <!-- BEGIN HEADER & CONTENT DIVIDER -->
      <div class="clearfix"> </div>
      <!-- END HEADER & CONTENT DIVIDER --> 
      <!-- BEGIN CONTAINER -->
      <div class="page-container">
      <!-- BEGIN SIDEBAR -->
      <?php include('includes/navigation.php'); ?>
      <!-- END SIDEBAR --> 
      <!-- BEGIN CONTENT -->
      <div class="page-content-wrapper">
         <!-- BEGIN CONTENT BODY -->
         <div class="page-content">
            <div class="page-bar">
               <h3 class="page-title">Email Broadcast</h3>
            </div>
            <div class="clearfix"></div>
            <!-- BEGIN EDITOR-->
            <div class="row">
               <div class="col-md-12">
                  <!-- BEGIN EXTRAS PORTLET-->
                  <div class="portlet light form-fit bordered">
                     <div class="portlet-body form">
                        <div class="form-body">
                           <div class="row">
                              <div class="col-md-12">
                                 <!-- BEGIN EXTRAS PORTLET-->
                                 <div class="portlet light form-fit bordered">
                                    <div class="portlet-body form">
                                       <form class="form-horizontal form-bordered" action="<?php echo $admin_base_url ?>email_broadcast/save_email_broadcast" method="post" id="email_broadcast_users" enctype="multipart/form-data">
                                          <div class="form-body">
                                             <div class="form-group">
                                                <label for="select2-button-addons-single-input-group" class="control-label col-md-2">Select Location</label>
                                                <div class="col-md-4">
                                                   <div class="input-group col-md-12 col-sm-12 col-xs-12 select2-bootstrap-prepend">
                                                      <select id="location_id" class="form-control js-data-example-ajax" name="locations[]" onchange="my_location(this.id)" multiple>
                                                         <option>Select Location</option>
                                                         <?php if(!empty($locations)){ foreach ($locations as $value) {
                                                            $broadcast_locations = explode(",", $broadcast_data['inLocationId']);
                                                            if(in_array($value['inLocationId'], $broadcast_locations)){
                                                          ?>
                                                         <option value="<?php echo $value['inLocationId']; ?>" selected><?php echo $value['stLocationName']; ?></option>
                                                         <?php }else{ ?>
                                                         <option value="<?php echo $value['inLocationId']; ?>"><?php echo $value['stLocationName']; ?></option>
                                                         <?php } } } ?>                                                                     
                                                      </select>
                                                   </div>
                                                   <span class="help-block"> Select Location</span>
                                                   <span id="location_id_err"></span>
                                                </div>
                                             </div>
                                             <div class="form-group form-group-one">
                                                <label class="control-label col-md-2">Subject</label>
                                                <div class="col-md-4">
                                                   <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter Subject" value="<?php echo (!empty($broadcast_data['st_subject']) ? $broadcast_data['st_subject'] : ''); ?>">
                                                   <span id="subject_err"></span>
                                                </div>
                                             </div>
                                             <div class="form-group form-group-one">
                                                <label class="control-label col-md-2">Description</label>
                                                <div class="col-md-8">
                                                  <textarea class="form-control description" id="summernote_1" name="description_email" placeholder="Enter Description" data-required="1" name="description" rows="3" ><?php echo !empty($broadcast_data['st_desc']) ? $broadcast_data['st_desc'] : ''; ?></textarea>
                                                  <span id="summernote_1_err"></span>
                                                  <h6 class="pull-left count_message" id="event_desc"></h6>
                                                </div>
                                             </div>
                                             <div class="form-group form-group-one">
                                                <label class="control-label col-md-2">Attachment</label>
                                                <div class="col-md-4">
                                                  <div class="input-group" id="wrapper">
                                                      <div type="text" class="form-control" placeholder="" data-trigger="fileinput"><i class="fa fa-file"></i></div>
                                                      <span class="input-group-addon btn default btn-file"> <span class="fileinput-new"> Select file </span>
                                                      <input type="file" name="file_attachment[]" id="file_attachment" multiple>
                                                      <input type="hidden" name="st_file_attachment" id="st_file_attachment" value="<?php echo (!empty($broadcast_data['st_file_attachment']) ? $broadcast_data['st_file_attachment'] : ''); ?>">
                                                      <?php if(!empty($broadcast_data['st_file_attachment'])){ $file_path_acc = explode("/", $broadcast_data['st_file_attachment']); } ?>
                                                      <input type="hidden" name="st_email_file_attachment" id="st_email_file_attachment" value="<?php echo (!empty($broadcast_data['st_file_attachment']) ? $this->config->item('upload').$file_path_acc[6].'/'.$file_path_acc[7] : '') ?>">
                                                      </span>
                                                   </div>
                                                   <div style="line-height:2"> Accepted File Types: .jpg, .png, .pdf, .ppt, .pptx, .doc, .docx, .xls, .xlsx, .csv</div>
                                                   <span>Maximum File Size: 10 MB</span><br />
                                                   <span id="result"></span>
                                                </div>
                                                <div class="col-md-4" id="show_image" style="display: none;">
                                                  <img src="" width="100px" height="100px" >
                                                </div>
                                             </div>
                                             <div class="form-group">
                                                <label for="select2-button-addons-single-input-group" class="control-label col-md-2">Select Teams</label>
                                                <div class="col-md-4">
                                                   <div class="input-group col-md-12 col-sm-12 col-xs-12 select2-bootstrap-prepend">
                                                      <select id="multi_team_ids" class="form-control js-data-example-ajax" name="users[]" multiple>
                                                      </select>
                                                   </div>
                                                   <span id="teams_id_err"></span> 
                                                </div>
                                             </div>
                                             <!-- new  field added -->
                                             <div class="form-group">
                                                <label for="select2-button-addons-single-input-group" class="control-label col-md-2">Select Additional Recipients</label>
                                                <div class="col-md-4">
                                                   <div class="input-group col-md-12 col-sm-12 col-xs-12 select2-bootstrap-prepend">
                                                      <select id="multi_user_ids" class="form-control js-data-example-ajax" name="other_users[]" multiple="">
                                                      </select>
                                                   </div>
                                                   <span id="other_users_err"></span>
                                                </div>
                                             </div>
                                             <!-- end of new field added -->
                                             <!-- new  field additional email address -->
                                             <div class="form-group">
                                                <label for="select2-button-addons-single-input-group" class="control-label col-md-2">Additional Email Addresses</label>
                                                <div class="col-md-4">
                                                   <div class="input-group col-md-12 col-sm-12 col-xs-12 select2-bootstrap-prepend">
                                                      <textarea class="form-control description" name="tags" id="additional_email_recipients" placeholder="Enter Comma Separated Email IDs"><?php echo !empty($broadcast_data['st_additional_addresses']) ? $broadcast_data['st_additional_addresses'] : ''; ?></textarea>
                                                   </div>
                                                   <span id="additional_email_recipients_err"></span>
                                                </div>
                                             </div>
                                             <!-- end of new field additional email address -->   
                                             <div class="form-group form-group-one  date_and_time_clonnable_div date_and_time_div">
                                                <label class="control-label col-md-2"> Send Email Date and Time</label>
                                                <div class="col-md-10">
                                                   <div class="row">
                                                      <div class="col-lg-3 col-md-5 col-sm-5 col-xs-10 lappi-991-768-m-b-15">
                                                         <div class="input-group date">
                                                            <input type="text" size="16" readonly="" class="form-control" id="datetime_date" name="datetime_date" value="<?php if(isset($broadcast_data['dt_sendtime'])){ echo date("d/m/Y H:i", strtotime($broadcast_data['dt_sendtime'])); } ?>">
                                                            <input type="hidden" id="date_datetime">
                                                            <span class="input-group-btn">
                                                            <button class="btn default date-set form_datetime" type="button"> <i class="fa fa-calendar"></i> </button>
                                                            </span> 
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="form-group form-group-one  date_and_time_clonnable_div date_and_time_div">
                                                <label class="control-label col-md-2"> </label>
                                                <div class="col-md-10">
                                                   <div class="row">
                                                      <div class="col-lg-3 col-md-5 col-sm-5 col-xs-10 lappi-991-768-m-b-15">
                                                         <div class="checkbox">
                                                            <label>
                                                            <input type="checkbox" value="now" name="send_now" id="send_now">
                                                            Send Now
                                                            </label>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="form-group form-group-one  date_and_time_clonnable_div date_and_time_div">
                                                <label class="control-label col-md-2"> </label>
                                                <div class="col-md-10">
                                                   <div class="row">
                                                      <div class="col-lg-3 col-md-5 col-sm-5 col-xs-10 lappi-991-768-m-b-15">
                                                         <div class="checkbox">
                                                            <label>
                                                            <input type="checkbox" value="now" name="test_email" id="test_email">
                                                            Test Email
                                                            </label>
                                                         </div>
                                                      </div>
                                                      <div class="col-lg-3 col-md-5 col-sm-5 col-xs-10 lappi-991-768-m-b-15 test_mail" style="display:none;">
                                                         <input type="email" name="test_email_id" id="test_email_id" class="form-control" placeholder="Enter Email ID">
                                                      </div>
                                                      <div class="col-lg-3 col-md-5 col-sm-5 col-xs-10 lappi-991-768-m-b-15 test_mail" style="display:none;">
                                                         <button type="button" id="send_email" class="btn green">Send Email</button>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="form-actions">
                                                <div class="row">
                                                   <div class="col-md-offset-2 col-md-10">
                                                      <?php if(!empty($broadcast_data['in_broadcast_id'])){ ?>
                                                      <input type="hidden" name="crud_type" value="Update">
                                                      <input type="hidden" name="broadcast_id" value="<?php echo $broadcast_data['in_broadcast_id']; ?>">
                                                      <button type="submit" id="save_broadcast" class="btn green">Update</button>
                                                      <?php }else{ ?>
                                                      <button type="submit" id="save_broadcast" class="btn green">Save</button>
                                                      <?php } ?>
                                                      <input type="hidden" name="action" value="update_email_broadcast">
                                                   </div>
                                                </div>
                                             </div>
                                       </form>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- END TAB-->
               <div class="clearfix"></div>
            </div>
            <!-- END CONTENT BODY --> 
         </div>
         <!-- END CONTENT --> 
      </div>
      <!-- END Import New clinic under event -->
      <!-- END CONTAINER --> 
      <!-- BEGIN FOOTER -->
      <?php include('includes/footer.php'); ?>
      <!-- END FOOTER --> 
      <!-- BEGIN CORE PLUGINS, THEME GLOBAL, THEME LAYOUT SCRIPTS -->
      <?php include('includes/common-scripts.php'); ?>
      <!-- END CORE PLUGINS, THEME GLOBAL, THEME LAYOUT SCRIPTS --> 
      <!-- BEGIN CORE PLUGINS --> 
      <script type="text/javascript">
        $(document).ready(function (){
          '<?php if($this->session->userdata('message') and !empty($this->session->userdata('message'))) { ?>'
              //alert('message');
              $("#modal_message_succes_fail").html('<?php echo $this->session->userdata('message'); ?>');
              $("#dmk_resources_uploaded-success").modal('show');
          '<?php   $this->session->unset_userdata('message');} ?>'

         // if already scheduled data then using ajax teams and other users will be get selected
          '<?php if (!empty($broadcast_data["inLocationId"])) { ?>'
            var location_id = '<?php echo $broadcast_data["inLocationId"]; ?>';
            $.ajax({
                 url: "<?php echo $admin_base_url; ?>email_broadcast/fetch_data_locationwise",
                 method: "post",
                 beforeSend: function () {
                     $('#loader').show();
                 },
                 async: false,
                 data: { location_id:location_id },
                 dataType:'json',
                 complete: function () {
                     $('#loader').hide();
                 },
                 success: function (data){
                     $('#loader').hide();
                     //console.log(data.teams_data[0].in_team_id);
                     if (data.teams_data != null) {
                        var teams = '<?php echo $broadcast_data["st_team"]; ?>';

                        var teams = teams.split(',');
                        console.log(teams);
                        var team_row = '<option >Select Teams</option>';
                        team_row += '<option value="all">All Users</option>';
                        for(var i=0; i<data.teams_data.length;i++){
                           if (teams.indexOf(data.teams_data[i].in_team_id) > -1) {
                              team_row += '<option value="'+data.teams_data[i].in_team_id+'" selected>'+data.teams_data[i].st_team_name+'</option>';
                           }else{
                              team_row += '<option value="'+data.teams_data[i].in_team_id+'">'+data.teams_data[i].st_team_name+'</option>';
                           }    
                         }
                         $('#multi_team_ids').html(team_row);
                     }
                     if (data.users_data != null) {
                         var st_add_recipients = '<?php echo $broadcast_data["st_add_recipients"]; ?>';
                         var st_add_recipients = st_add_recipients.split(',');
                         var user_row = '<option >Select Additional Recipients</option>';
                         for(var i=0; i<data.users_data.length;i++){
                           if (st_add_recipients.indexOf(data.users_data[i].in_user_id) > -1) {
                              user_row += '<option value="'+data.users_data[i].in_user_id+'" selected>'+data.users_data[i].st_firstname+'</option>';
                           }else{
                              user_row += '<option value="'+data.users_data[i].in_user_id+'">'+data.users_data[i].st_firstname+'</option>';
                           }
                         }
                         //console.log(user_row);
                         $('#multi_user_ids').html(user_row);
                     }
                 }
             });
          '<?php } ?>'

            $("#send_now").click(function(){
               if($('#send_now').parent().hasClass('checked')){
                  $('#date_datetime').val($('#datetime_date').val());
                  $('#datetime_date').val('');
               }else{
                  $('#datetime_date').val($('#date_datetime').val());
                  $('#date_datetime').val('');
               }
            });

        });

        $("#test_email").click(function(){
            if($("#test_email").is(':checked')){
              $('.test_mail').show();
            }else{
              $('.test_mail').hide();
            }
        });

        $('#send_email').click(function(){
          var test_email_id = $('#test_email_id').val();
          var subject = $('#subject').val();
          var description = $('#summernote_1').val();
          var st_email_file_attachment = $('#st_email_file_attachment').val();
          if (subject == '') {
               $('#subject_err').text('Please enter Subject.');
               $('#subject_err').css('color','#e73d4a');
               $('#subject_err').show();
               return false;
         }else{
             $('#subject_err').hide();
         }
         if (description == '') {
               $('#summernote_1_err').text('Please enter Description.');
               $('#summernote_1_err').css('color','#e73d4a');
               $('#summernote_1_err').show();
               return false;
         }else{
             $('#summernote_1_err').hide();
         }
          if (test_email_id == '') {
            alert('Please enter email ID');
            return false;
          }
          $('#send_email').hide();
          $.ajax({
                 url: "<?php echo $admin_base_url; ?>email_broadcast/test_email_broadcast",
                 method: "post",
                 beforeSend: function () {
                     $('#loader').show();
                 },
                 data: { test_email_id:test_email_id,description: description,subject: subject,st_email_file_attachment: st_email_file_attachment },
                 dataType:'json',
                 complete: function () {
                     $('#loader').hide();
                 },
                 success: function (data){
                     $('#loader').hide();
                     if (data.status == true) {
                        $('#test_email_id').val('');
                        alert('Email Sent Successfully');
                        $('#send_email').show();
                     }
                 }
            });
          
        });

        $('#save_broadcast').click(function(){
            var location_id = $("#location_id").val();
            var description_email = $('#summernote_1').val();
            var subject = $('#subject').val();
            var teams_id = $('#multi_team_ids').val();
            var other_users = $('#multi_user_ids').val();
            var additional_email_recipients = $('#additional_email_recipients').val();
            
            if (location_id == null || location_id == '') {
               $('#location_id_err').text('Please select location.');
               $('#location_id_err').css('color','#e73d4a');
               $('#location_id_err').show();
               return false;
            }else{
               $('#location_id_err').hide();
            }

            if (subject == '') {
                  $('#subject_err').text('Please enter Subject.');
                  $('#subject_err').css('color','#e73d4a');
                  $('#subject_err').show();
                  return false;
            }else{
                $('#subject_err').hide();
            }

            if (description_email == '') {
                  $('#summernote_1_err').text('Please enter Description.');
                  $('#summernote_1_err').css('color','#e73d4a');
                  $('#summernote_1_err').show();
                  return false;
            }else{
                $('#summernote_1_err').hide();
            }

            if ((teams_id == null || teams_id == '') && (other_users == null || other_users == '') && additional_email_recipients == '') {
             $('#teams_id_err').text('Please select Registered Attendees/Specified Team.');
             $('#teams_id_err').css('color','#e73d4a');
             $('#teams_id_err').show();
             $('#other_users_err').text('Please select Registered Attendees/Specified Team.');
             $('#other_users_err').css('color','#e73d4a');
             $('#other_users_err').show();
             $('#additional_email_recipients_err').text('Please enter Additional Email Recipients.');
             $('#additional_email_recipients_err').css('color','#e73d4a');
             $('#additional_email_recipients_err').show();
             return false;
           }else{
             $('#teams_id_err').hide();
             $('#other_users_err').hide();
             $('#additional_email_recipients_err').hide();
           }

           $('#loader').show();
           $('#save_broadcast').hide();
           $('#email_broadcast_users').submit();

        });

        /* File Upload Using Ajax */
        var MyApp = MyApp || {};
        MyApp.Method = {
            file_browse: function(formData, url) {
                var result;
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: formData,
                    enctype: 'multipart/form-data',
                    contentType: false,
                    cache: false,
                    processData: false,
                    async: false,
                    url: url,
                    beforeSend: function(xhr) {
                      $('#loader').show();
                    },
                    success: function(data) {
                      $('#loader').show();
                        result = data;
                    },
                    error: function(data) {
                        result = data;
                    }
                });
                return result;
            }
        };
        $('#file_attachment').on('change', function(e) {
            e.preventDefault();
            var formData = new FormData();
            $.each($("#wrapper input[type=file]"), function(i, obj) {
               $.each(obj.files,function(j, file){
                  formData.append('file_attachment['+j+']', file);
               });
            });
            var image_data = MyApp.Method.file_browse(formData, '<?php echo $admin_base_url; ?>email_broadcast/save_file');
            if (image_data.status == 'success') {
                var pdf_url = '<?php echo $base_url; ?>assets/layouts/layout/img/pdf-300x300.png';
                var doc_url = '<?php echo $base_url; ?>assets/layouts/layout/img/docx-200X200.png';
                var ppt_url = '<?php echo $base_url; ?>assets/layouts/layout/img/ppt-512X512.png';
                var excel_url = '<?php echo $base_url; ?>assets/layouts/layout/img/excel-300X300.png';
                $('#result').css('color','#000000');
                $('#loader').hide();
                $('#result').html(image_data.message);
                $('#st_file_attachment').val(image_data.web_url);
                $('#st_email_file_attachment').val(image_data.file_path);
                $('#show_image').show();
                if (image_data.file_type == 'pdf') {
                  $('#show_image img').attr("src", pdf_url);
                }else if(image_data.file_type == 'doc' || image_data.file_type == 'docx'){
                  $('#show_image img').attr("src", doc_url);
                }else if(image_data.file_type == 'ppt' || image_data.file_type == 'pptx'){
                  $('#show_image img').attr("src", ppt_url);
                }else if(image_data.file_type == 'xls' || image_data.file_type == 'xlsx' || image_data.file_type == 'csv'){
                  $('#show_image img').attr("src", excel_url);
                }else{
                  $('#show_image img').attr("src", image_data.web_url);
                }
            } else {
                err_d = 'Image is not Uploaded!';
                $('#loader').hide();
                $('#result').html(image_data.message);
                $('#result').css('color','#e73d4a');
            }
        });

         $(document).ready(function() {
            $('.note-insert').remove();
            $('div.note-group-select-from-files').remove();
         });
         
         function my_location(e){
             var location_id = $('#'+e).val();
             location_id = location_id.toString();
             $.ajax({
                 url: "<?php echo $admin_base_url; ?>email_broadcast/fetch_data_locationwise",
                 method: "post",
                 beforeSend: function () {
                     $('#loader').show();
                 },
                 data: { location_id:location_id },
                 dataType:'json',
                 complete: function () {
                     $('#loader').hide();
                 },
                 success: function (data){
                     $('#loader').hide();
                     //console.log(data.teams_data[0].in_team_id);
                     if (data.teams_data != null) {
                         var team_row = '<option >Select Teams</option>';
                         team_row += '<option value="all">All Users</option>';
                         for(var i=0; i<data.teams_data.length;i++){
                             team_row += '<option value="'+data.teams_data[i].in_team_id+'">'+data.teams_data[i].st_team_name+'</option>';
                         }
                         //console.log(team_row);
                         $('#multi_team_ids').html(team_row);
                     }
                     if (data.users_data != null) {
                         var user_row = '<option >Select Additional Recipients</option>';
                         for(var i=0; i<data.users_data.length;i++){
                             user_row += '<option value="'+data.users_data[i].in_user_id+'">'+data.users_data[i].st_firstname+'</option>';
                         }
                         console.log(user_row);
                         $('#multi_user_ids').html(user_row);
                     }
                 }
             });
         }
         
      </script>
      <!-- END PAGE LEVEL PLUGINS EDIT EVENT PAGE--> 
      <!-- BEGIN PAGE LEVEL SCRIPTS FOR EDIT EVENT PAGE--> 
      <script type="text/javascript">
         $(".js-data-example-ajax").select2();
      </script>
      <!-- END CORE PLUGINS --> 
      <!-- BEGIN THEME GLOBAL SCRIPTS --> 
      <!-- END THEME GLOBAL SCRIPTS --> 
      
      <!-- BEGIN PAGE LEVEL PLUGINS EDIT EVENT PAGE--> 
      <script src="<?php echo $base_url; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script> 
      <script src="<?php echo $base_url; ?>assets/global/plugins/icheck/icheck.min.js" type="text/javascript"></script> 
      <script src="<?php echo $base_url; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
      <script src="<?php echo $base_url; ?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
      <script type="text/javascript">
         $("#datetime_date").datetimepicker({
            format: "dd/mm/yyyy hh:ii",
            minView :1,
            autoclose: true,
            pickTime: false,
            useCurrent: false,
            startDate: new Date()
         });
      </script>
      <!-- END PAGE LEVEL PLUGINS EDIT EVENT PAGE--> 
      <!-- BEGIN PAGE LEVEL SCRIPTS FOR EDIT EVENT PAGE--> 
      <script src="<?php echo $base_url; ?>assets/pages/scripts/form-icheck.min.js" type="text/javascript"></script> 
      <script src="<?php echo $base_url; ?>assets/pages/scripts/components-select2.min.js" type="text/javascript"></script> 
      <!-- END PAGE LEVEL SCRIPTS FOR EDIT EVENT PAGE--> 
      <!-- BEGIN THEME LAYOUT SCRIPTS --> 
      <!-- END THEME LAYOUT SCRIPTS --> 
      <!-- BEGIN ADDITIONAL CUSTOM SCRIPTS --> 
      <script src="<?php echo $base_url; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script> 
      <script src="<?php echo $base_url;?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script> 
      <script src="<?php echo $base_url; ?>assets/pages/scripts/view/edit_event.js" type="text/javascript"></script> 
      <script src="<?php echo $base_url; ?>assets/layouts/layout/scripts/new_custom.js" type="text/javascript"></script>
      <script src="<?php echo $base_url;?>assets/layouts/layout/scripts/custom.js" type="text/javascript"></script> 
      <!-- Summer Note JS  -->
      <script src="<?php echo $base_url;?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
      <script src="<?php echo $base_url;?>assets/pages/scripts/components-editors.min.js" type="text/javascript"></script> 
      <script src="<?php echo $base_url;?>assets/global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript"></script>  
      <!-- Summer Note JS -->
   </body>
</html>
