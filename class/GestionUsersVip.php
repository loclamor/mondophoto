<?php
class GestionUsersVip {
	
	/**
	 * @var GestionUsersVip
	 */
	private static $instance = null;
	
	private $users = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionUsersVip();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return VipUser
	 */
	public function getUserVip($id) {
		if(array_key_exists($id, $this->users)) {
			return $this->users[$id];
		}
		else {
			$user = new VipUser($id);
			$this->users[$id] = $user;
			return $user;
		}
	}
	
	public function getUsersVip($orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM vip_user'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$users = array();
			foreach ($res as $row) {
				$users[] = $this->getUserVip($row['id']);
			}
		}
		else {
			$users = false;
		}
		return $users;
	}
	
	public function getUserVipByMail($mail) {
		
		$res = SQL::getInstance()->exec('SELECT id FROM vip_user WHERE mail LIKE "'.$mail.'";');
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$user = $this->getUserVip($res[0]['id']);
			
		}
		else {
			$user = false;
		}
		return $user;
	}
	
	
}