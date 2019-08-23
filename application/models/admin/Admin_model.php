<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model{


	public function get_period(){

		$today = date("Y-m-d");  
		$today=date("Y-m-d");
		$this->db->where('start',$today);
		$q=$this->db->get('scheduledate')->row_array();
	
		$this->db->where('ScheduleType',$q['title']);
		$p=$this->db->get('scheduletype')->row_array();
		$period_list=explode("|",$p['PeriodAccess']);
	
		$now= new Datetime('now');
		$data['username']=$_SESSION['username'];

		foreach($period_list as $v){

			$sql= "SELECT * FROM `period` WHERE `Period`='$v'";
			$result=$this->db->query($sql)->row_array();
	
			$start=new Datetime($result['PeriodStartTime']);
			$end=new Datetime($result['PeriodEndTime']);
		
			if($now >= $start && $now <= $end){
					$data['period']=$v;
					
				}
		
			
			
			
		}
		return $data['period'];
	
	
		// echo "<pre>";
	

		// $sql= "SELECT * FROM `period` WHERE `Period` IN(1,2,4,5)";
		// $result=$this->db->query($sql)->row_array();
		// print_r($result);

		// 	// $now= new Datetime('now');
		// 	// $data['username']=$_SESSION['username'];
		// 	// $q=$this->db->get('period')->result_array();
		// 	// foreach($q as $v){
		// 	// 	$start=new Datetime($v['PeriodStartTime']);
		// 	// 	$end=new Datetime($v['PeriodEndTime']);
		// 	// 	//$a=$start->format('H:i:s');
				
		// 	// 	if($now >= $start && $now <= $end){
		// 	// 		$data['period']=$v['Period'];
		// 	// 	}
		// 	// }
		// 	// return $data['period'];
			
	}

	public function get_day_type(){
		$today=date("Y-m-d");
		$this->db->where('start',$today);
		$q=$this->db->get('scheduledate')->result_array();
		
		return $q;
		
	}
	public function get_day_count(){
		
		$sql = "SELECT  count(DISTINCT  `start`) as 'count' FROM `vschedule_date` where `start` between '2019-07-01' AND '2019-07-16'";
		$q=$this->db->query($sql)->result_array();
		return $q;
		
	}

	public function get_user_detail(){
		$id = $this->session->userdata('admin_id');
		$query = $this->db->get_where('admin', array('admin_id' => $id));
		return $result = $query->row_array();
	}
	//--------------------------------------------------------------------
	public function update_user($data){
		$id = $this->session->userdata('admin_id');
		$this->db->where('admin_id', $id);
		$this->db->update('admin', $data);
		return true;
	}
	//--------------------------------------------------------------------
	public function change_pwd($data, $id){
		$this->db->where('admin_id', $id);
		$this->db->update('admin', $data);
		return true;
	}
	//-----------------------------------------------------
	function get_admin_roles()
	{
		$this->db->from('admin_roles');
		$this->db->where('admin_role_status',1);
		$query=$this->db->get();
		return $query->result_array();
	}

	//-----------------------------------------------------
	function get_admin_by_id($id)
	{
		$this->db->from('admin');
		$this->db->join('admin_roles','admin_roles.admin_role_id=admin.admin_role_id');
		$this->db->where('admin_id',$id);
		$query=$this->db->get();
		return $query->row_array();
	}

	//-----------------------------------------------------
	function get_all()
	{
		$this->db->from('admin');
		$this->db->join('admin_roles','admin_roles.admin_role_id=admin.admin_role_id');
		
		if($this->session->userdata('filter_type')!='')
			$this->db->where('admin.admin_role_id',$this->session->userdata('filter_type'));

		if($this->session->userdata('filter_status')!='')
			$this->db->where('admin.is_active',$this->session->userdata('filter_status'));

		$filterData = $this->session->userdata('filter_keyword');
		$where = "(
		admin_roles.admin_role_title like '%$filterData%' OR
		admin.firstname like '%$filterData%' OR
		admin.lastname like '%$filterData%' OR
		admin.email like '%$filterData%' OR
		admin.mobile_no like '%$filterData%' OR
		admin.username like '%$filterData%'
		)";
		$this->db->where($where);

		$this->db->order_by('admin.admin_id','desc');
			//$this->db->limit($limit, $offset);
		$query = $this->db->get();
		$module = array();
		if ($query->num_rows() > 0) 
		{
			$module = $query->result_array();
		}
		return $module;
	}

		//-----------------------------------------------------
	public function add_admin($data){
		$this->db->insert('admin', $data);
		return true;
	}

		//---------------------------------------------------
		// Edit Admin Record
	public function edit_admin($data, $id){
		$this->db->where('admin_id', $id);
		$this->db->update('admin', $data);
		return true;
	}

		//-----------------------------------------------------
	function change_status()
	{		

		$this->db->set('is_active',$this->input->post('status'));
		$this->db->where('admin_id',$this->input->post('id'));
		$this->db->update('admin');
	} 
	function change_tardy_status($a,$b,$c)
	{		
		$this->db->set($b,$c);
		$this->db->where('AttendanceId',$a);
		$this->db->update('attendance');
		return true;
	} 
	function change_access_status()
	{		
		$this->db->set('is_active',$this->input->post('status'));
		$this->db->where('id',$this->input->post('id'));
		$this->db->update('vterminal_access');
	} 
	function change_terminal_status()
	{		
		$this->db->set('IsEnabled',$this->input->post('status'));
		$this->db->where('HallPassID',$this->input->post('id'));
		$this->db->update('hallpass');
	} 

	public function get_all_hallpass(){

		//$this->db->where('is_admin', 0);
		//$this->db->order_by('created_at', 'desc');
		$query = $this->db->get('hallpass');
		return $result = $query->result_array();
		
	}

	public function get_master(){

		//$this->db->where('is_admin', 0);
		//$this->db->order_by('created_at', 'desc');
		
		$query = $this->db->get('master_control');
		return $result = $query->result_array();
	}
	public function get_emergency_list(){

		//$this->db->where('is_admin', 0);
		//$this->db->order_by('created_at', 'desc');
		
		$query = $this->db->get('emergency');
		return $result = $query->result_array();
	}
	public function get_import_csv(){
		$sql='SELECT * FROM `import_csv`';
		$query=$this->db->query($sql);
		return $query->result();
	}




		public function import_csv_student($data){
			foreach ($data as $value) {

					$student_array=array(
						'student_local_id' => $value['student_local_id'],
						'student_type' => $value['student_type'],
						'last_name' => $value['last_name'],
						'first_name' => $value['first_name'],
						'graduation_cohort' => $value['graduation_cohort'],
						'birthdate' => $value['birthdate'],
						'gender' => $value['gender'],
						'student_email' => $value['student_email'],
					
					);
					$parent_array=array(
			
						'parent_last_name' => $value['parent_last_name'],
						'student_local_id' => $value['student_local_id'],
						'parent_last_name' => $value['parent_last_name'],
						'parent_first_name' => $value['parent_first_name'],
						'parent_address' => $value['parent_address'],
						'parent_city' => $value['parent_city'],
						'parent_state' => $value['parent_state'],
						'parent_zip' => $value['parent_zip'],
						'parent_email' => $value['parent_email'],
					);

					
					$this->db->insert('student_table',$student_array);
					$this->db->insert('parent_table',$parent_array);
			}
		print_r($data);
		}
	

	public function add_terminal($data){
		
		$this->db->insert('hallpass', $data);
	
		return true;
	}


		//-----------------------------------------------------
	function delete($id)
	{		
		$this->db->where('admin_id',$id);
		$this->db->delete('admin');
	} 
	//Teacher Start
	public function get_all_teacher(){
		//$sql='SELECT * FROM `teacher` as t INNER JOIN department as d ON t.DepartmentID=d.DepartmentID ';
		$sql='SELECT * FROM `teacher` as t INNER JOIN department as d ON t.DepartmentID=d.DepartmentID ';
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function get_teacher_hallpass($data,$id){
		$this->db->where('TeacherID',$data);
		$this->db->where('PassTypeID',$id);
		$query=$this->db->get('teacher_hallpass_access');
		return $query->result_array();
	}

	public function get_terminal_info($a,$b,$c){
		

		$this->db->distinct();
		$this->db->where('location',$a);
		$this->db->where('start',$b);
		$this->db->where('period_number',$c);
		
		$query=$this->db->get('vschedule_date');
		
		
		return $query->result_array();
	}
	public function get_terminal_hallpass($data){
	
		$this->db->where('IDNumber',$data);
		$query=$this->db->get('vterminal_access');

	
		return $query->result_array();
	}
	public function get_terminal_access($data,$id){
		$this->db->where('TeacherID',$data);
		$this->db->where('PassTypeID',$id);
		$query=$this->db->get('vterminal_access');
		return $query->result_array();
	}
	public function get_teacher_byteacherid($teacherid){
		$sql='SELECT * FROM teacher where TeacherID=?';
		$query=$this->db->query($sql,array($teacherid));
		return $query->result();
	}
	public function get_department(){
		$sql='SELECT * FROM `department`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function manage_teacher($type,$data){
	
		if($type=='delete-teacher'){
			$this->db->where('TeacherID',$data);
			$this->db->delete('teacher');
			return ($this->db->affected_rows() != 1) ? false : true;
		}else{
			$values=array(
				'IDNumber'=>$data[1],
				'FirstName'=>$data[2],
				'LastName'=>$data[3],
				'Gender'=>$data[4],
				'BirthDate'=>$data[5],
				'ContactNumber'=>$data[6],
				'DepartmentID'=>$data[7],
				'Password'=>$data[8]
			);
			if($type=='edit-teacher'){
				$this->db->where('teacherid', $data[9]);
				$this->db->update('teacher', $values);
				return ($this->db->affected_rows() != 1) ? false : true;
			}else{
				$this->db->insert('teacher',$values);
				return ($this->db->affected_rows() != 1) ? false : true;
			}
			
		}
		
	}
	//Teacher End
	//Student Start
	public function get_all_student(){
		$sql='SELECT * FROM `vstudent_parent`';
		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function get_student_bystudentid($studentid){
		$sql='SELECT * FROM student where StudentID=?';
		$query=$this->db->query($sql,array($studentid));
		return $query->result();
	}
	public function get_race(){
		$sql='SELECT * FROM `race`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function get_section(){
		$sql='SELECT * FROM `section`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function get_grade_level(){
		$sql='SELECT * FROM `gradelevel`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function get_distinction(){
		$sql='SELECT * FROM `distinction`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function manage_student($type,$data){
		
		if($type=='delete-student'){
			$this->db->where('StudentID',$data);
			$this->db->delete('student');
			return ($this->db->affected_rows() != 1) ? false : true;
		}else{
			$values=array(
				'IDNumber'=>$data[1],
				'FirstName'=>$data[2],
				'LastName'=>$data[3],
				'Gender'=>$data[4],
				'BirthDate'=>$data[5],
				'ContactNumber'=>$data[6],
				'RaceID'=>$data[7],
				'SectionID'=>$data[8],
				'GradeLevelID'=>$data[9],
				'DistinctionID'=>$data[10],
				'Password'=>$data[11],
				'IsEnabled'=>1
			);
			if($type=='edit-student'){
				$this->db->where('studentid', $data[13]);
				$this->db->update('student', $values);
				return ($this->db->affected_rows() != 1) ? false : true;
			}else{
				$this->db->insert('student',$values);
				return ($this->db->affected_rows() != 1) ? false : true;
			}
			
		}
		
	}


	public function import_csv($data){
		//print_r($data);
		foreach ($data as $value) {
			//echo($value);
				$this->db->insert('import_csv',$value);
				
		}//ako muna sir. may tetest lang ako
		//haha nakita ko na sir ahha yung ito palaa (return ($this->db->affected_rows() != 1) ? false : true;) kaya nag stop yung loop
	//	
	}


	public function get_import_courses(){
		$sql='SELECT * FROM `courses`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function get_import_department(){
		$sql='SELECT * FROM `department`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function get_import_section(){
		$sql='SELECT * FROM `section`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function get_import_race(){
		$sql='SELECT * FROM `race`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function import_race($data){
		
		foreach ($data as $value) {
			//echo($value);
				$this->db->insert('race',$value);
				
		}//ako muna sir. may tetest lang ako
		//haha nakita ko na sir ahha yung ito palaa (return ($this->db->affected_rows() != 1) ? false : true;) kaya nag stop yung loop
	//	
	}
	public function get_import_student_schedule(){
		$sql='SELECT * FROM `student_schedule`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function import_student_schedule($data){
		foreach ($data as $value) {
			//echo($value);
				$this->db->insert('student_schedule',$value);
				
		}//ako muna sir. may tetest lang ako
		//haha nakita ko na sir ahha yung ito palaa (return ($this->db->affected_rows() != 1) ? false : true;) kaya nag stop yung loop
	//	
	}
	public function get_import_classes(){
		$sql='SELECT * FROM `class_list`';

		$query=$this->db->query($sql);
		return $query->result_array();
	}
	public function get_teacher_course($data){
		//$this->db->select('course_code','period_number','teacher_id_number');
		$sql='SELECT DISTINCT `teacher_id_number`,`period_number`,`course_code`,`class_type`,`short_desc`,`location`FROM `vteacher_classes`';
		$query=$this->db->query($sql);
		return $query->result_array();

	}
	public function get_student_class($data){
		//$this->db->select('course_code','period_number','teacher_id_number');
		$this->db->where('student_id_number',$data);
		$query=$this->db->get('student_schedule');

		return $query->result_array();
	}

	public function get_import_terminal(){
		$sql='SELECT * FROM `location`';
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function import_classes($data){
		
		foreach ($data as $value) {
			//echo($value);
				$this->db->insert('class_list',$value);
				
		}

	}
	
	public function create_terminal_users(){
	
		$result=$this->db->get('class_list')->result_array();
		foreach($result as $r){

			$this->db->where('username',$r['location']);
			$q=$this->db->get('admin')->result_array();

			if($q==null){
				$data_array=array(
					'username'=>$r['location'],
					'password'=>password_hash($r['location'], PASSWORD_BCRYPT),
					'is_verify'=>1,
					'is_admin'=>1,
					'is_active'=>1,
					'admin_role_id'=>9,
					'token' => md5(rand(0,1000)),    
						'last_ip' => '',
						'created_at' => date('Y-m-d : h:m:s'),
						'updated_at' => date('Y-m-d : h:m:s'),
				);
				$this->db->insert('admin',$data_array);
			}

			$this->db->where('section',$r['section']);
			$s=$this->db->get('section')->result_array();
			if($s==null){
				$this->db->insert('section',array('section'=>$r['section']));
			}

			$this->db->where('username',$r['teacher_id_number']);
			$t=$this->db->get('admin')->result_array();
			if($t==null){
				$data_array=array(
					'username'=>$r['teacher_id_number'],
					'id_number'=>$r['teacher_id_number'],
					'password'=>password_hash($r['teacher_id_number'], PASSWORD_BCRYPT),
					'is_verify'=>1,
					'is_admin'=>1,
					'is_active'=>1,
					'admin_role_id'=>10,
					'token' => md5(rand(0,1000)),    
						'last_ip' => '',
						'created_at' => date('Y-m-d : h:m:s'),
						'updated_at' => date('Y-m-d : h:m:s'),
				);
				$this->db->insert('admin',$data_array);
				$this->db->insert('teacher',array('IDNumber'=>$r['teacher_id_number']));
				
			}
			
		}
		
	
		
		

	}
	public function import_terminal($data){
		
		foreach ($data as $value) {
			//echo($value);
				$this->db->insert('location',$value);
				
		}//ako muna sir. may tetest lang ako
		//haha nakita ko na sir ahha yung ito palaa (return ($this->db->affected_rows() != 1) ? false : true;) kaya nag stop yung loop
	//	
	}
	public function import_section($data){
		
		foreach ($data as $value) {
			//echo($value);
				$this->db->insert('section',$value);
				
		}//ako muna sir. may tetest lang ako
		//haha nakita ko na sir ahha yung ito palaa (return ($this->db->affected_rows() != 1) ? false : true;) kaya nag stop yung loop
	//	
	}
	public function import_department($data){
		
		foreach ($data as $value) {
			//echo($value);
				$this->db->insert('department',$value);
				
		}//ako muna sir. may tetest lang ako
		//haha nakita ko na sir ahha yung ito palaa (return ($this->db->affected_rows() != 1) ? false : true;) kaya nag stop yung loop
	//	
	}
	
	
	public function import_courses($data){
		//print_r($data);
		foreach ($data as $value) {
			//echo($value);
				$this->db->insert('courses',$value);
				
		}//ako muna sir. may tetest lang ako
		//haha nakita ko na sir ahha yung ito palaa (return ($this->db->affected_rows() != 1) ? false : true;) kaya nag stop yung loop
	//	
	}

	public function delete_hallpass($id){
	$this->db->where('HallPassID',$id);
	$this->db->delete('hallpass');
	return ($this->db->affected_rows() != 1) ? false : true;}

	public function add_gracetime($data){
		$this->db->update('period', $data);
		return true;
	
		
	
	}public function change_data_status($data){
	  
		if ($data=='hallpass'){
		$this->db->set('is_active',$this->input->post('status'));
		$this->db->where('HallPassID',$this->input->post('id'));
		$this->db->update('hallpass');
		return true;}
		else{
		$this->db->set('is_active',$this->input->post('status'));
		$this->db->where('id',$this->input->post('id'));
		$this->db->update('master_control');
		}
	
	}
	public function teacher_hallpass($data){
		$this->db->set('hallpassAccess',$data);
		$this->db->update('teacher');
	}
	public function student_hallpass($data){
		$this->db->set('hall_pass_access',$data);
		$this->db->update('student');
	}
	public function check_student_if_enrolled($a,$b){
		
		$this->db->distinct();
		$this->db->where('term','S1');
		$this->db->where('start',date("Y-m-d"));
		$this->db->where('teacher_id_number',$a);
		$this->db->where('period_number',$b);
	
		$q=$this->db->get('vstudent_roster')->result_array();
		return $q;
		
	}
	public function check_student_mot($a){
	
	

		$this->db->distinct();
		$this->db->where('AttendanceDate',date("Y-m-d"));
		$this->db->where('attendance_time_mot !=','');
		$result=$this->db->get('vmot')->result_array();




		return $result;
	
		
	}
	public function check_student_rosters($a,$b){
		
		$this->db->distinct();
		$this->db->where('term','S1');
		$this->db->where('start',date("Y-m-d"));
		$this->db->where('teacher_id_number',$a);
	    $this->db->where('period_number',$b);
		$q=$this->db->get('vstudent_roster')->result_array();
		return $q;
	}
	// roster list with data on the hallpass
	public function check_student_rosters_data($a){
		
		$this->db->distinct();
		$this->db->where('term','S1');
		//$this->db->where('start',date("Y-m-d"));
		$this->db->where('teacher_id_number',$a);
	    //$this->db->where('period_number',$b);
		$q=$this->db->get('vstudent_roster_list')->result_array();
		return $q;
	}

	public function get_student_class_access($a,$b){
		
		$this->db->distinct();
		$this->db->where('term','S1');
		$this->db->where('start',date("Y-m-d"));
		$this->db->where('student_local_id',$a);
		$this->db->where('period_number',$b);
		$query=$this->db->get('vstudent_roster')->result_array();

		return $query;
		
	}
	public function get_student_secretary_access($a){
		
		$this->db->distinct();
		$this->db->where('term','S1');
		$this->db->where('start',date("Y-m-d"));
		$this->db->where('student_local_id',$a);
		//$this->db->where('attendance_time')
		$query=$this->db->get('vstudent_roster')->result_array();

		return $query;
		
	}
	public function check_hallpass_logs($a,$b)
	{
		$this->db->distinct();
		$this->db->where('AttendanceDate',date("Y-m-d"));
		$this->db->where('teacher_id_number',$a);
		$this->db->where('period_number',$b);
		$this->db->where('date_time_ended','0000-00-00 00:00:00');
		$result['active']=$this->db->get('vstudent_hallpass_logs')->result_array();

		$this->db->distinct();
		$this->db->where('AttendanceDate',date("Y-m-d"));
		$this->db->where('teacher_id_number',$a);
		$this->db->where('period_number',$b);
		$this->db->where('date_time_ended !=','0000-00-00 00:00:00');
		$result['expired']=$this->db->get('vstudent_hallpass_logs')->result_array();
		
		//print_r($query);
	
		return $result;

	}

	public function ac_create_student_schedule(){
		
		$this->db->select('class_id');
		$this->db->where('student_id_number');
		$this->db->where('course_code');
		$this->db->where('teacher_id_number');
		$this->db->where('section');
		$this->db->where('grade_level');
		$this->db->where('period');
		$this->db->where('schedule_type');
		$this->db->where('location');
		$this->db->where('distiction');
		$this->db->where('school_year');
		$this->db->where('semester');
		$q=$this->db->get('class_master');
		if($q=null){
			foreach($q as $v){

			}
		}
		

	//this create schedule of all students for all the calendar Year:
		

	}

	public function record_student_hallpass($a){
		$this->db->where('attendance_id',$_SESSION['AttendanceID']);
		$this->db->where('hallpass',$a['hallpass']);
		$this->db->where('is_active',1);
		//check if ther is an active hallpass if 
		$q=$this->db->get('attendance_hallpass')->result_array();
		if($q==null){
		$data_array=array(
			'attendance_id'=>$_SESSION['AttendanceID'],
			'is_active'=>1,
			'date_time_ended'=>'',
			'hallpass'=>$a['hallpass']);
			$this->db->insert('attendance_hallpass',$data_array);}
		else{
			
			print_r("i");

		}
		
	}
	
	public function record_attendace_mot($a,$b){
	
		
		$now = new Datetime('now');
		$date=$now->format('y-m-d');
		$time=$now->format('H:i:s');

		$this->db->where('class_id',$a);
		$this->db->where('AttendanceDate',$date);
		$this->db->where('PeriodID',$b);
		$q=$this->db->get('attendance')->result_array();
		//check if attendance is already available 
		if($q==null){
		
				$this->db->set('class_id',$a);
				$this->db->set('AttendanceDate',$date);
				$this->db->set('attendance_time_mot',$time);
				$this->db->set('PeriodID',$b);
				$this->db->insert('attendance');

				// $this->db->where('class_id',$a);
				// $this->db->where('AttendanceDate',$date);
				// $this->db->where('PeriodID',$b);
				// $q=$this->db->get('attendance')->result_array();
				// $this->session->set_userdata('AttendanceID',$q[0]['AttendanceID']);
				return true;
		}
		else{return false;}
	
	
	}
	
	public function record_attendace($a){

		
		$now = new Datetime('now');
		$date=$now->format('y-m-d');
		$time=$now->format('H:i:s');

		$this->db->where('class_id',$a);
		$this->db->where('AttendanceDate',$date);
		$this->db->where('PeriodID',$_SESSION['period_number']);
		$q=$this->db->get('attendance')->result_array();
	
		
		//check if attendance is already available 
		if($q==null){
		
				$this->db->set('class_id',$a);
				$this->db->set('AttendanceDate',$date);
				$this->db->set('AttendanceTime',$time);
				$this->db->set('PeriodID',$_SESSION['period_number']);
				$this->db->insert('attendance');

				$this->db->where('class_id',$a);
				$this->db->where('AttendanceDate',$date);
				$this->db->where('PeriodID',$_SESSION['period_number']);
				$q=$this->db->get('attendance')->result_array();
				$this->session->set_userdata('AttendanceID',$q[0]['AttendanceID']);
		return 'recorded';
		}
		elseif ($q[0]['class_id']!=null && $q[0]['attendance_time_mot']!=null) {
			 $this->db->set('AttendanceTime',$time);
			 $this->db->where('AttendanceID',$q[0]['AttendanceID']);
			 $this->db->update('attendance');
			 return 'welcome to class';
		}
		else{

			//attendance available then check attendance hallpass
			//print('<pre>');
			//print_r($q);
			$this->session->set_userdata('AttendanceID',$q[0]['AttendanceID']);
			$this->db->where('attendance_id',$q[0]['AttendanceID']);
			$this->db->where('is_active',1);
			$result=$this->db->get('attendance_hallpass')->result_array();
			if($result==null){
				return true;
			}else{
				$now =new DateTime('now');
				$time_end=$now->format("Y-m-d H:i:s");
				//print_r($now->date);
				$this->db->set('is_active',0);
				$this->db->set('date_time_ended',$time_end);
				$this->db->where('attendance_id',$q[0]['AttendanceID']);
				$this->db->where('is_active',1);
				$this->db->update('attendance_hallpass');
				return 'updated';
				//$this->db->set('date_time_ended',$result[0])
			}
			
			//return 'check if there is an active hall pass ';
		}

	}

	public function student_access($data){
		
		$sql='SELECT StudentID,hall_pass_access FROM `student`';
		$query=$this->db->query($sql);
		$result=$query->result_array();
		

		foreach ($result as $v){
	
			$access=explode('|',$v['hall_pass_access']);
		

		
			foreach($access as $a){
				$data_array=array(
					'access'=>$a,
					'student_id_number'=>$v['StudentID'],
					'is_active'=>'1',
					'time_limit'=>'5');
			
			$this->db->select('student_id_number,access');
			$this->db->where('student_id_number',$v['StudentID']);
			$this->db->where('access',$a);
			$query=$this->db->get('student_access');
			$result=$query->result_array();
		

			if ( $query->num_rows() > 0 ) 
			{
				$var='';
			}
			else
			{
				$this->db->insert('student_access',$data_array);
				
			}


			}
		

		
			
		

		}
		
	}
	

	public function teacher_access($data){
		
		$sql='SELECT teacherID,hallpassAccess FROM `teacher`';
		$query=$this->db->query($sql);
		$result=$query->result_array();
		

		foreach ($result as $v){
	
			$access=explode('|',$v['hallpassAccess']);

		
			foreach($access as $a){
				$data_array=array(
					'access'=>$a,
					'teacherID'=>$v['teacherID'],
					'is_active'=>'1',
					'time_limit'=>'5');
			
			$this->db->select('teacherID,access');
			$this->db->where('teacherID',$v['teacherID']);
			$this->db->where('access',$a);
			$query=$this->db->get('teacher_access');
			$result=$query->result_array();
		

			if ( $query->num_rows() > 0 ) 
			{
				$var='';
			}
			else
			{
				$this->db->insert('teacher_access',$data_array);
				
			}


			}
		

		
			
		

		}
		
	}



	//Stuent End

}


?>