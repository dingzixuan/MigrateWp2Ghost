<?php
class Migrate {
	private $_mysql_conn;
	private $_sqlite_conn;
	private $_wp_tb_pre = "";
	private $_options= array();	//以后用于存放迁移选项

	public function setMysqlConn( $host, $user, $pw, $db ) {
		$this->_mysql_conn = mysql_connect( $host, $user, $pw, true );
		mysql_select_db( $db, $this->_mysql_conn );
	}

	public function setSqliteConn($path) {
		//$this->_sqlite_conn = sqlite_open($path);	//因为sqlite_open不支持sqlite3，所以先用pdo
		$dsn = 'sqlite:'.$path;
		$this->_sqlite_conn = new PDO($dsn);
	}

	public function setWpTbPre( $prefix ) {
		$this->_wp_tb_pre = $prefix."_";
	}

	public function migratePosts() {

	}

	public function getAllWpPosts() {
		$queryStr = "SELECT * FROM {$this->_wp_tb_pre}posts;";
		$result = mysql_query( $queryStr, $this->_mysql_conn );
		$num_rows = 0;
		$fetch_result = array();
		while ( $row = mysql_fetch_object( $result ) ) {
			$fetch_result[$num_rows] = $row;
			$num_rows++;
		}
		echo $num_rows;
		mysql_free_result( $result );
	}

	public function getAllGhPosts() {
		$queryStr = "SELECT * FROM posts;";
		$result = $this->_sqlite_conn->query( $queryStr );
		$num_rows = 0;
		$fetch_result = array();
		while ( $row = $result->fetch(PDO::FETCH_OBJ) ) {
			$fetch_result[$num_rows] = $row;
			$num_rows++;
		}
		echo $num_rows;
	}

}