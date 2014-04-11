<!--<html>
<head>
</head>
<body>
	<div>
		<form>
			<h2>wordpress数据库设置：</h2>
			<ul>
				<li><label>host:</label><input /></li>
				<li><label>username:</label><input /></li>
				<li><label>password:</label><input /></li>
				<li><label>dbname:</label><input /></li>
			</ul>
			<h2>SQLite数据库设置：</h2>
			<ul>
				<li><label>本地db文件路径:</label><input /></li>
			</ul>
		</form>
	</div>
</body>
</html>-->
<?php
include('migrate.php');
$migrate = new Migrate();
$migrate->setMysqlConn( "127.0.0.1", "root", "qazwsx", "wp4dzx" );
$migrate->setWpTbPre( "wp" );
$migrate->setSqliteConn( "D:\\dzx_WorkSpace\\nodejs\\ghost-0.4.2\\content\\data\\ghost-dev.db" );
$migrate->migratePosts();

