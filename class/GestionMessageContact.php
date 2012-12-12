<?php
class GestionMessageContact {
	
	/**
	 * @var GestionMessageContact::
	 */
	private static $instance = null;
	
	private $messages = array();
	
	public static function getInstance() {
		if(is_null(self::$instance)) {
		self::$instance = new GestionMessageContact();
		}
		return self::$instance;
	}
	
	/**
	 * @param integer $id
	 * @return MessageContact
	 */
	public function getMessageContact($id) {
		if(array_key_exists($id, $this->messages)) {
			return $this->messages[$id];
		}
		else {
			$message = new MessageContact($id);
			$this->messages[$id] = $message;
			return $message;
		}
	}
	
	public function getMessagesContact($orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM message_contact'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$messages = array();
			foreach ($res as $row) {
				$messages[] = $this->getMessageContact($row['id']);
			}
		}
		else {
			$messages = false;
		}
		return $messages;
	}
	
	public function getMessagesContactNonLu($orderby = 'id', $desc = false) {
		$desc = $desc?' DESC':' ASC';
		if(!is_null($orderby) && !empty($orderby)) {
			
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT id FROM message_contact WHERE estLu = 0'.$orderby.$desc);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$messages = array();
			foreach ($res as $row) {
				$messages[] = $this->getMessageContact($row['id']);
			}
		}
		else {
			$messages = false;
		}
		return $messages;
	}
	
	
}