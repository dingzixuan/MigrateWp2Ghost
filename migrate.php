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
		$queryStr = "SELECT * FROM {$this->_wp_tb_pre}posts WHERE post_type='post';";
		mysql_query("set names utf8;");
		$result = mysql_query( $queryStr, $this->_mysql_conn );
		$num_rows = 0;
		$sth = $this->_sqlite_conn->prepare('INSERT INTO `posts` (`uuid`, `title`, `slug`, `html`, `featured`, `page`, `status`, `language`, `author_id`, `created_at`, `created_by`, `published_at`) VALUES (:uuid, :title, :slug, :html, :featured, :page, :status, :language, :author_id, :created_at, :created_by, :published_at)');
		while ( $row = mysql_fetch_object( $result ) ) {
			$res = $sth->execute(
                array(':uuid' => '63240255-4c39-4458-a673-0eab8c31827d',	//看ghost的uuid生成算法
                ':title'=>$row->post_title, 
                ':slug'=>$row->post_title,	//将title中的特殊符号替代掉？
                ':html'=>$row->post_content,	//还有markdown字段，添加html转markdown的组件
                ':featured'=>0,
                ':page'=>0, 
                ':status'=>'published', 
                ':language'=>'en_US', 
                ':author_id'=>1,
                ':created_at'=>$row->post_date, 
                ':created_by'=>1, 
                ':published_at'=>$row->post_date, 
                )
        	);
			$num_rows++;
		}
		echo $num_rows;
		mysql_free_result( $result );
	}

	public function getAllWpPosts() {
		$queryStr = "SELECT * FROM {$this->_wp_tb_pre}posts WHERE post_type='post';";
		mysql_query("set names utf8;");
		$result = mysql_query( $queryStr, $this->_mysql_conn );
		$num_rows = 0;
		$fetch_result = array();
		while ( $row = mysql_fetch_object( $result ) ) {
			echo $row->post_title;
			echo "<br/>";
			echo $row->post_content;
			echo "<br/>"; 
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