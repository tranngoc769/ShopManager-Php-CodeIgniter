<?php

class Admin_model extends CI_Model {

	
	public function get_users()
	{
		return $this->db->get_where(USER, array("user_type" => "user"));
	}

	

	public function banUser($userid = "")
	{
		return $this->db->where("user_id", $userid)->update(USER, array("ban_flag" => 1));
	}

	
	public function upgrade_user($userid = "")
	{
		$res = $this->db->where("user_id", $userid)->update(USER, array("user_type" => 'dev'));
        $this->db->where("user_id", $userid)->delete("upgrade_table");
		return $res;
	}
	public function reject_user($userid = "")
	{
        return $this->db->where("user_id", $userid)->delete("upgrade_table");
		
	}

	public function unbanUser($userid = "")
	{
		return $this->db->where("user_id", $userid)->update(USER, array("ban_flag" => 0));
	}
}
