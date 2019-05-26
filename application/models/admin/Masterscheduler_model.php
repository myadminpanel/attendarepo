<?php
	class Masterscheduler_model extends CI_Model{

		public function get_all(){

			//$this->db->where('is_admin', 0);
			//$this->db->order_by('created_at', 'desc');
			$query = $this->db->get('period');
			return $result = $query->result_array();
		}

		public function check_student_id(){
			$query= $this->db->get('student');
			return $result = $query->result_array();
		}

		public function add_scheduledate($data){
		//	print_r($data);gt 
			$this->db->insert('scheduledate', $data);
			return ($this->db->affected_rows() != 1) ? false : true;
		}

		
			
		//---------------------------------------------------
		// get all users for server-side datatable processing (ajax based)
		public function get_scheduletype(){
			$sql='SELECT * FROM `scheduletype`';
			$query=$this->db->query($sql);
			return $query->result_array();
		}
		public function get_all_periods(){
			$sql='SELECT * FROM `period`';
			$query=$this->db->query($sql);
			return $query->result_array();
		}
		function get_period_access()
		{
			$this->db->from('scheduletype');
			$query=$this->db->get();
			$data=array();
			foreach($query->result_array() as $v)
			{
				$data[]=$v['ScheduleType'].'/'.$v['PeriodAccess'];
			}
			return $data;
		} 	

		public function get_period_access_by_type($type){
			$query = $this->db->get_where('scheduletype', array('ScheduleType' => $type));
			return $result = $query->row_array();
		}


		//---------------------------------------------------
		// Get user detial by ID
		public function get_user_by_id($id){
			$query = $this->db->get_where('users', array('id' => $id));
			return $result = $query->row_array();
		}

		//---------------------------------------------------
		// Edit user Record
		public function edit_user($data, $id){
			$this->db->where('id', $id);
			$this->db->update('users', $data);
			return true;
		}

		public function get_data_array()
		{
			$query = $this->db->get('scheduledate');
			return $result = $query->result_array();
			
		}
	
		public function get_all_scheduletype()
		{
			$query = $this->db->get('scheduletype');
			return $result = $query->result_array();
		}


		//---------------------------------------------------
		// Change user status
		//-----------------------------------------------------
		function change_status()
			{		
				$this->db->set('is_active',$this->input->post('status'));
				$this->db->where('HallPassID',$this->input->post('id'));
				$this->db->update('hallpass');
			} 


			function set_access()
			{
				if($this->input->post('status')==1)
				{
					$this->db->set('admin_role_id',$this->input->post('admin_role_id'));
					$this->db->set('module',$this->input->post('module'));
					$this->db->set('operation',$this->input->post('operation'));
					$this->db->insert('module_access');
				}
				else
				{
					$this->db->where('admin_role_id',$this->input->post('admin_role_id'));
					$this->db->where('module',$this->input->post('module'));
					$this->db->where('operation',$this->input->post('operation'));
					$this->db->delete('module_access');
				}
		


		} 
		public function delete_scheduledate($scheduledateid){
			$this->db->where('ScheduleDateID',$scheduledateid);
			$this->db->delete('ScheduleDate');
			return ($this->db->affected_rows() != 1) ? false : true;
		}



	}

?>