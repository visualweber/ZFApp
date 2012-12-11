<?php
/**
 * 
 * Enter description here ...
 * @author TOAN
 *
 */

class App_Util_Saga {
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $usrInfo
	 */
	public static function characterGame($usrInfo) {
		$gserver = new AppModel_Gameserver ();
		$tbl = $gserver->getMapper ()->getDbTable ();
		$adapter = $tbl->getAdapter ();
		$select = $adapter->select ();
		$select->from ( array (
			$tbl->getTableName () ), array (
			'*' ) );
		$select->where ( 'true' );
		$result = $adapter->fetchAll ( $select );
		
		if (count ( $result ) > 0) {
			foreach ( $result as $item ) {
				$url = 'http://s2.saga.like.vn:8899/checkAccount.jsp?username=' . $usrInfo ['username'] . '&server_id=' . $item ['code'] . '&sign=' . md5 ( urlencode ( $usrInfo ['username'] ) . $item ['code'] . 'E6FF6C9EB9DC9AADB541E7F9407E5908' );
				$content = file_get_contents ( $url );
				if (trim ( $content )) {
					return $content;
				}
			}
		}
		return false;
	
	}
}