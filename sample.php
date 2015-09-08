<?php

// こんな感じに使えると良いな...

// SQLから更新後スキーマを作成
$db1 = MySQLDatabase::fromSQL($sql);
// PDOから現在のスキーマを作成
$db2 = MySQLDatabase::fromPDO($pdo);

// 差分チェック
if($db1 != $db2) {
  // 差分を作成
  $dbdiff = $db2.diff($db1);
  // 差分をSQLとして表示
  echo $dbdiff;
  // 更新
  $dbdiff.migrate($pdo);
}

// テーブル名やカラム名のリネームは別に情報を渡す
// 差分チェック
if($db1 != $db2) {
  // 差分を作成
  $dbdiff = $db2.diff($db1,
  	['table'=>[
  	    'oldname1'=>'newname1',
  	    'oldname2'=>'newname2'
  	 ],
  	 'column'=>[
  		'table1'=>[
  			'oldcolumn1'=>'newcolumn1',
  			'oldcolumn2'=>'newcolumn2'
  		],
  		'table2'=>[
  			'oldcolumn1'=>'newcolumn1'
  		],
  	 ]

  	]);
  // 差分をSQLとして表示
  echo $dbdiff;
  // 更新
  $dbdiff.migrate($pdo);
}
