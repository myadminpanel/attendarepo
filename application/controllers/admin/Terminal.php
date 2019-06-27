<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Terminal extends MY_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->library('rbac');
			$this->load->model('admin/admin_model', 'admin');
			$this->rbac->check_module_access();
			
			
		}

		public function index(){
			//$data['title'] = 'General Settings';
			$data['view'] = 'terminal/terminal_modal';
			$data['view1'] = 'terminal/alertmodal';
			$data['username']=$_SESSION['username'];
			//$result=$this->admin->get_terminal_hallpass($data['username']);
		
			$this->load->view('terminal/index',$data);

		}
		public function get_info()
		{
			$data['teacher_id_number']=$_SESSION['teacher_id_number'];
		
			$result=$this->admin->get_terminal_hallpass($data['teacher_id_number']);
			echo json_encode($result);

		}
		
		public function get_terminal_info()
		{
			$data['date']=$this->input->post('data');
			$now= new Datetime('now');
		
			$data['username']=$_SESSION['username'];
			$q=$this->db->get('period')->result_array();
			foreach($q as $v){
				$start=new Datetime($v['PeriodStartTime']);
				$end=new Datetime($v['PeriodEndTime']);

				if($now >= $start && $now <= $end){
					$data['period']=$v['Period'];
					
				}
			}
		
			$result=$this->admin->get_terminal_info($data['username'],$data['date'],$data['period']);
			
			$this->session->set_userdata('teacher_id_number', $result[0]['teacher_id_number']);
			$this->session->set_userdata('class_code', $result[0]['class_code']);
			$this->session->set_userdata('period_number', $result[0]['period_number']);
			echo json_encode($result);

		
		}
		public function get_student_student_hallpass(){
		$a=$this->input->post('id');
		$b=$this->input->post('hallpass');
		echo $a;
		echo $b;
		}

		public function get_student_schedule(){

			$data['id']=$this->input->post('id');
		
			$data['class_code']=$_SESSION['class_code'];
			//$data['class_code']=$this->input->post('class_code');
			$result=$this->admin->get_student_class_access($data['id'],$data['class_code']);
	
			if($result==null){
				echo json_encode($result);
				
			}else{
				$result=$this->admin->record_attendace($result['id']);
				echo json_encode($result);
			}
		}
		public function test(){
			// $response	=array(
			// 	'csrfName' => $this->security->get_csrf_token_name(),
            //     'csrfHash' => $this->security->get_csrf_hash()
			// );
			if($this->username=='123'){
				$response=array(
					'Teacher'=>'Abdul Jakul',
					'Subject'=>'Physical Education',
					'AvailableUntil'=>'12:00:00',
					'HallPassLock'=>'12:00:00'
				);
			}
			if($this->username=='1234'){
				$response=array(
					'Teacher'=>'Jakul Salsalani',
					'Subject'=>'Science',
					'AvailableUntil'=>'13:00:00',
					'HallPassLock'=>'13:00:00'
				);
			}
			echo json_encode($response);
		}
	}

?>	