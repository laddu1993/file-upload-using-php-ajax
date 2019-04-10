<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
# ------------------------------
# Created by: Vinil Lakkavatri
# Created date: 07-MAR-2019
#----------------------------------- end
class email_broadcast extends CI_Controller {

    function __construct() { 
        parent::__construct();
        // error_reporting(E_ALL);
        //ini_set("display_errors", 1);
        if (!$this->session->userdata('sess_admin_id')) {
            $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $this->session->set_userdata('redirect_url', $url);
            redirect($this->config->item('admin_base_url'));
        }
        // 
        $level_ids = array('1','2');
        if(!in_array($this->session->userdata('sess_admin_level'), $level_ids)){
            redirect($this->config->item('admin_base_url'));
        }
        $admin_location_details = $this->location_model->get_location($this->session->userdata('sess_admin_location_id'));
        if(!empty($admin_location_details) and is_array($admin_location_details) and isset($admin_location_details['stModulesAllowed'])){
            $stModulesAllowed = explode(',',$admin_location_details['stModulesAllowed']);
            if(in_array('Courses',$stModulesAllowed) !== TRUE){
                redirect($this->config->item('admin_base_url'));
            }
        }
        //load models
        $models = array(
           'crud_model' => 'crud_model',
           'course_model' => 'course_model'
        );
        $this->load->model($models);
        // load libraries
        $this->load->library(array('form_validation', 'common_function', 'pagination', 'email'));
        // global declaration variables
        $this->today = date("Y-m-d H:i:s"); 
        
    }

    /**
     * @param default function in every controller
     * @return html view
    */
    public function index() {
        $data['locations'] = $this->crud_model->fetch_data('tbl_location','inLocationId,stLocationName','inDeleted = 0','stLocationName','ASC');
        $data['broadcast_data'] = current($this->crud_model->fetch_data('tbl_email_broadcast','','in_flag = 0 AND in_deleted = 0'));
        //echo "<pre>";print_r($data);die;
        $this->load->view('email_broadcast',$data);
    }
    
    /**
     * @param Ajax call
     * @param int location_id
     * @return string $data
    */
    function fetch_data_locationwise(){
        if (isset($_POST['location_id']) && !empty($_POST['location_id'])) {
            $location_id = $this->input->post('location_id');
            //echo "<pre>";print_r($location_id);die;
            $data['teams_data'] = $this->crud_model->fetch_data('tbl_team','in_team_id,st_team_name','inLocationId IN ('.$location_id.') AND in_deleted = 0');
            $location_id = explode(",", $location_id);
            if (!empty($location_id)) {
                $where_cond = '';
                $i = 0;
                foreach ($location_id as $key) {
                    $where_cond .= 'FIND_IN_SET('.$key.', inLocationId) OR ';
                }
                $where_cond = rtrim($where_cond," OR");
                if (!empty($where_cond)) {
                    $where_cond .= ' AND in_status = 1 AND in_deleted = 0';
                }
            }
            $data['users_data'] = $this->crud_model->fetch_data('tbl_user','in_user_id,st_firstname,st_lastname,inLocationId',$where_cond);
            echo json_encode($data);
            exit();
        }
    }

    /**
     * @param Ajax call
     * @param File Data
     * @return JSON DATA
    */
    function save_file(){
        $save_path = $this->config->item('upload').'email_broadcast/';
        $actual_url = $this->config->item('upload_url').'email_broadcast/';
        
        if (!empty($_FILES) && isset($_FILES['file_attachment'])) {
            echo "<pre>";print_r($_FILES);
            //$_FILES['file_attachment']['size'] < 10485760
            $i = 0;
            foreach ($_FILES as $key) {
                $rand = rand();
                $exts = array('pdf', 'png', 'jpg', 'ppt', 'pptx', 'doc', 'docx', 'xls', 'xlsx', 'csv');
                $tmp = explode('.', $key['name'][$i]);
                $file_extension = end($tmp);
                if(in_array($file_extension, $exts)){
                    $image_name = 'broadcast_'.$rand.'_'.$key['name'][$i];
                    $tmp_image_name = $key['tmp_name'][$i];
                    $image_type = $key['type'][$i];
                    $image_size = $key['size'][$i];
                    $status = move_uploaded_file($tmp_image_name, $save_path.$image_name);
                    $file_type = explode("/", $image_name);
                    $file_type = end($file_type);
                    $file_type = explode(".", $file_type);
                    $file_type[] = end($file_type);
                    $web_url[$i] = $actual_url.$image_name;
                }else{
                    $file_arr = array('status' => 'failed', 'message' => 'File Extension is not allowed.');
                    break;
                }
                $i++;
            }
            echo "<pre>";print_r($web_url);die;  
            $file_arr = array('web_url' => $actual_url.$image_name, 'status' => 'success', 'message' => 'File uploaded successfully', 'file_name' => $image_name, 'file_path' => $save_path.$image_name, 'file_type' => $file_type);
            
        }else{
            $file_arr = array('status' => 'failed', 'message' => 'File Size should not be more than 10 MB.');
        }
        echo json_encode($file_arr);
        exit();
    }

    /**
     * @param POST DATA
     * @param Note: Email Broadcast Data
     * @return redirect with respective URL
    */
    function save_email_broadcast(){
        if (isset($_POST['action']) && !empty($_POST)) {
            $sql_data = '';
            $location_id = $this->input->post('locations');
            $subject = $this->input->post('subject');
            $description_email = $this->input->post('description_email');
            $st_team = $this->input->post('users');
            $other_users = $this->input->post('other_users');
            $additional_email_addresses = $this->input->post('tags');
            $send_now = $this->input->post('send_now');
            $send_datetime_date = $this->input->post('datetime_date');
            $crud_type = $this->input->post('crud_type');
            $broadcast_id = $this->input->post('broadcast_id');
            $st_file_attachment = $this->input->post('st_file_attachment');
            $st_email_file_attachment = $this->input->post('st_email_file_attachment');
            //echo "<pre>";print_r($_POST);die;
            if (isset($_FILES['file_attachment']) && !empty($_FILES['file_attachment']['name'])) {
                if ($_FILES['file_attachment']['size'] < 10485760) {
                    /*$rand = rand();
                    $status = move_uploaded_file($_FILES['file_attachment']['tmp_name'], $this->config->item('upload').'email_broadcast/broadcast_'.$rand.'_'.$_FILES['file_attachment']['name']);
                    $st_file_attachment = $this->config->item('upload_url').'email_broadcast/broadcast_'.$rand.'_'.$_FILES['file_attachment']['name'];
                    $st_email_file_attachment = $this->config->item('upload').'email_broadcast/broadcast_'.$rand.'_'.$_FILES['file_attachment']['name'];*/
                }else{
                    $this->session->set_flashdata('message', 'File size should be less than 10 MB');
                    redirect('email_broadcast', 'refresh');
                }
            }
            if (!empty($st_team) || !empty($other_users) || !empty($additional_email_addresses)) {
                $email_users = array();
                if (!empty($st_team) && in_array('all', $st_team)) {
                    $locations = $location_id;
                    if (!empty($locations)) {
                        $where_cond = '';
                        foreach ($locations as $key) {
                            $where_cond .= 'FIND_IN_SET('.$key.', inLocationId) OR ';
                        }
                        $where_cond = rtrim($where_cond," OR");
                        if (!empty($where_cond)) {
                            $where_cond .= ' AND in_status = 1 AND in_deleted = 0';
                        }
                        $all_users = $this->crud_model->fetch_data('tbl_user','st_email',$where_cond);
                        if (!empty($all_users)) {
                            foreach ($all_users as $key) {
                                $email_users[] = $key['st_email'];
                            }
                        }
                    }
                    
                }else{
                    if (!empty($st_team)) {
                        foreach ($st_team as $key) {
                            $columns = 't2.st_email';
                            $tbl_cond = 't2.in_user_id = t1.in_user_id';
                            $where_cond = 't1.in_deleted = 0 AND t1.in_team_id = '.$key.' AND t2.in_status = 1 AND t2.in_deleted = 0';
                            $team_users[] = $this->crud_model->left_join('tbl_team_member t1','tbl_user t2',$columns,$tbl_cond,$where_cond);
                        }
                        if (!empty($team_users)) {
                            foreach ($team_users as $value) {
                                foreach ($value as $key) {
                                    $email_users[] = $key['st_email'];
                                }
                            }
                        }
                    }

                    if (!empty($other_users)) {
                        $other_users = implode(",", $other_users);
                        $additional_users = $this->crud_model->fetch_data('tbl_user','st_email','in_user_id IN ('.$other_users.') AND in_status = 1 AND in_deleted = 0');
                        if (!empty($additional_users)) {
                            foreach ($additional_users as $key) {
                                $email_users[] = $key['st_email'];
                            }
                        }
                    }
                }

                if (!empty($additional_email_addresses)) {
                    $st_addtional_emails = explode(",", $additional_email_addresses);
                    if (!empty($st_addtional_emails)) {
                        foreach ($st_addtional_emails as $key) {
                            $email_users[] = $key;
                        }
                    }
                }
                $email_users = array_unique($email_users);
            }
            // insert or updation process to tbl_email_broadcast
            $data['inLocationId'] = !empty($location_id) ? implode(",", $location_id) : NULL;
            $data['st_subject'] = !empty($subject) ? $subject : NULL;
            $data['st_team'] = !empty($st_team) ? implode(",", $st_team) : NULL;
            $data['st_add_recipients'] = !empty($other_users) ? $other_users : NULL;
            $data['st_additional_addresses'] = !empty($additional_email_addresses) ? $additional_email_addresses : NULL;
            $data['st_file_attachment'] = !empty($st_file_attachment) ? $st_file_attachment : NULL;
            $data['st_desc'] = nl2br($description_email);
            $data['dt_created'] = $this->today;
            if (!empty($send_now) && $send_now == 'now') {
                $data['dt_sendtime'] = $this->today;
            }else{
                $data['dt_sendtime'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $send_datetime_date)));
            }
            if (!empty($crud_type) && $crud_type == 'Update' && !empty($broadcast_id)) {
                $updat_data['in_deleted'] = 1;
                $up_dt = $this->crud_model->update_data('tbl_email_broadcast_users',$updat_data,'in_broadcast_id = '.$broadcast_id.'');
                $insert_data = $this->crud_model->update_data('tbl_email_broadcast',$data,'in_broadcast_id = '.$broadcast_id.'');
            }else{
                $last_insert_id = $this->crud_model->insert_data('tbl_email_broadcast',$data);
            }
            $last_insert_id = !empty($broadcast_id) ? $broadcast_id : $last_insert_id;
            if (!empty($email_users)) {
                $sql_data .= "INSERT INTO tbl_email_broadcast_users (st_email, in_broadcast_id, st_subject, st_desc, st_file_attachment, in_flag, dt_created, dt_sendtime) VALUES";
                foreach ($email_users as $key) {
                    $sql_data .= '("'.$key.'",'.$last_insert_id.',"'.$subject.'", "'.htmlspecialchars($data['st_desc']).'", "'.$st_email_file_attachment.'", 0, "'.$data['dt_created'].'","'.$data['dt_sendtime'].'"),';
                }
                if (!empty($sql_data)) {
                    $trim_sql_data = rtrim($sql_data,",");
                    $this->crud_model->execute_query('INSERT',$trim_sql_data);
                }
            }
            if ($crud_type == 'Update') {
                $this->session->set_userdata('message', 'Email Broadcast successfully updated');
            }else{
                $this->session->set_userdata('message', 'Please wait till 10 min email broadcast will be sent.');
            }
            redirect('email_broadcast/');
        }
    }

    /**
     * @param Ajax call
     * @param Note: This is test email shoot for email broadcast: nothing get save in the database.
     * @return JSON DATA: with boolean
    */
    function test_email_broadcast(){
        if (isset($_POST['test_email_id']) && !empty($_POST)) {
            $this->load->library('email');
            $email_id = $this->input->post('test_email_id');
            $subject = $this->input->post('subject');
            $description = $this->input->post('description');
            $file_path = !empty($this->input->post('st_email_file_attachment')) ? $this->input->post('st_email_file_attachment') : null;
            $templete = file_get_contents(base_url()."email-template/email-broadcast.html");
            if(!empty($templete)){
                $src =  base_url().'email-template/images/dmk-logo.png';
                $message = $description;
                $templete = str_replace("##src##" ,$src ,$templete);
                $templete = str_replace("##user_name##" ,'User',$templete);
                $templete = str_replace("##message##" ,$message ,$templete);
                if (base_url() == 'http://crystal99/dmk/') {
                    $email_shoot = $this->common_function->send_mail_test4($this->email,$email_id,$subject,$templete,$file_path,$cc_email=null);
                }else{
                    $email_shoot = $this->common_function->send_mail($this->email, $email_id, $subject, $templete, $this->config->item('admin_email_from'), '' ,$this->config->item('admin_from_name'),'','','',$file_path);
                }
            }
            echo json_encode(array('status' => $email_shoot));
            exit();
        }
    }

}
