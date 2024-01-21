<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

/**
 * @author administrator
 * This class works with the "teacher" table.
 * This class offers tools to manage login, access and permissions
 */
class Auth_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  /**
   * @param string $username
   *
   * @return boolean
   * does the username exist in the database?
   */
  function is_user(string $username): bool {
    $this->db->where("username", $username);
    $this->db->from("teacher");
    $count = $this->db->get()->num_rows();
    $result = FALSE;

    if ($count == 1) {
      $result = TRUE;
    }

    return $result;
  }

  /**
   *
   * @param string $username
   * @param string $password
   *
   * @return \stdClass|null match a username to a password and return basic user information for
   * match a username to a password and return basic user information for
   *   starting a login session
   */
  function validate(string $username, string $password): ?stdClass {
    $this->db->where("username", $username);
    $this->db->where("pwd", $this->encrypt($password));
    $this->db->select("teacher.kTeach as kTeach, dbRole,gradeStart,gradeEnd,isAdvisor");
    $this->db->from("teacher");
    $query = $this->db->get();
    $count = $query->num_rows();
    $output = NULL;
    if ($count == 1) {
      $output = $query->row();
    }
    return $output;
  }

  /**
   *
   * @param int $kTeach
   * get the permissions of the specific user
   */
  function get_role(int $kTeach) {
    $this->db->where("kTeach", $kTeach);
    $this->db->select("dbRole");
    $this->db->from("teacher");
    $result = $this->db->get()->row();
    return $result->dbRole;
  }

  /**
   * @param int $kTeach
   * @param string $role
   * set the database role of a given user (admin, teacher, editor, aide)
   */
  function set_role(int $kTeach, string $role): void {
    $this->db->where("kTeach", $kTeach);
    $data["dbRole"] = $role;
    $this->db->update("teacher", $data);
  }

  /**
   * @param int $kTeach
   * get the short name for a given user id
   */
  function get_username(int $kTeach) {
    $this->load->model("teacher_model");
    $teacher = $this->teacher_model->get($kTeach, "username");
    return $teacher->username;
  }

  /**
   * @param int $kTeach
   * @param string $new
   *
   * @param string|null $old
   *
   * @return bool if the process works, return true, if it doesn't (ie. old password is not
   * if the process works, return true, if it doesn't (ie. old password is not
   *   found), returns false.
   */
  function change_password(int $kTeach,  string $new, string $old = NULL): bool {
    $result = FALSE;
    $username = $this->get_username($kTeach);
    $userID = $this->session->userdata ( "userID" );
    $clear_hash = TRUE;

    if ($userID == ROOT_USER && $kTeach != $userID) {
      $this->load->model('teacher_model', 'teacher');
      $user = $this->teacher->get($kTeach);
      $is_valid = TRUE;
      $old_password = $user->pwd;
      $clear_hash = FALSE;
    } else {
      $is_valid = $this->validate($username, $old);
      $old_password = $this->encrypt($old);
    }
    if ($is_valid) {
      $this->db->where("username", $username);
      $this->db->where("pwd", $old_password);
      $data["pwd"] = $this->encrypt($new);
      if($clear_hash){
        $data["resetHash"] = "";
      }
      $this->db->update("teacher", $data);
      if ($this->validate($username, $new)) {
        $result = TRUE;
      }
    }
    return $result;
  }

  /**
   * @param string $text
   *
   * @return string
   * convert any varchar into a 32bit md5 encrypted string
   */
  function encrypt(string $text): string {
    return md5(md5($text));
  }

  function email_exists($email) {
    $output = FALSE;
    $this->db->where("email", $email);
    $this->db->select("kTeach");
    $this->db->from("teacher");
    $row = $this->db->get()->row();
    if (!empty($row)) {
      $output = $row->kTeach;
    }
    return $output;
  }

  /**
   * @param int $kTeach
   *
   * @return string
   * create a 32bit md5 hash of the current date/time that can be used in a
   *   reset uri string to verify the user has has requested a change for a
   *   lost password.
   */
  function set_resetHash($kTeach) {
    $hash = $this->encrypt(now());
    $data["resetHash"] = $hash;
    $this->db->where("kTeach", $kTeach);
    $this->db->update("teacher", $data);
    return $hash;
  }

  /**
   * @param int $kTeach
   * @param 32bit varchar $resetHash
   * @param string $password
   *
   * @return boolean
   * using the hash from a uri as validation, this allows a user to reset a
   *   lost password
   */
  function reset_password($kTeach, $resetHash, $password) {
    $this->db->where("kTeach", $kTeach);
    $this->db->where("resetHash", $resetHash);
    $this->db->where("`resetHash` IS NOT NULL");
    $data["pwd"] = $this->encrypt($password);
    $data["resetHash"] = "";
    $this->db->update("teacher", $data);
    $username = $this->get_username($kTeach);
    return $this->validate($username, $password);
  }

  /**
   * @param int $kTeach
   * @param string $action
   * usually logs logins and log-outs. This could be used for other purposes,
   *   but is not
   */
  function log(int $kTeach, string $action): void {
    $data["kTeach"] = $kTeach;
    $data["action"] = $action;
    $data["time"] = mysql_timestamp();
    $data["username"] = $this->get_username($kTeach);
    $this->db->insert("user_log", $data);
  }

  /**
   * @return object array
   * returns a list of all the users (First and Last names) and usernames
   *   (active users only)
   */
  function get_usernames() {
    $this->db->select("username");
    $this->db->select("CONCAT(teachFirst,' ', teachLast) as user", FALSE);
    $this->db->where("status", 1);
    $this->db->from("teacher");
    $this->db->order_by("username");
    $result = $this->db->get()->result();
    return $result;
  }

  /**
   * @param array $options
   *
   * @return array of objects
   * returns an array of results from a db query of the user_log based on an
   *   optional array of limiting values (username, time_start, time_end as
   *   date_range array, and log action)
   */
  function get_log($options = []) {
    if (!empty($options)) {
      $keys = array_keys($options);
      $values = array_values($options);
      for ($i = 0; $i < count($options); $i++) {
        $myKey = $keys[$i];
        $myValue = $values[$i];
        if ($myKey != "date_range") {
          $this->db->where($myKey, $myValue);
        }
        else {
          $this->db->where("(time >= '" . $myValue["time_start"] . "' AND time <= '" . $myValue["time_end"] . "')");
        }
      }
    }
    $this->db->select("username,time,action");
    $this->db->from("user_log");
    $this->db->order_by("username", "ASC");
    $this->db->order_by("time", "DESC");

    $result = $this->db->get()->result();
    return $result;
  }

  function clean_logs($before_date) {
    $this->db->where('time<', $before_date);
    $this->db->delete('user_log');
    $this->_log();
  }

  function _log($target = "log", $live_server = FALSE) {
    if ($_SERVER['HTTP_HOST'] == "reports" || $live_server == TRUE) {
      $this->session->set_flashdata($target, $this->db->last_query());
    }
  }

}