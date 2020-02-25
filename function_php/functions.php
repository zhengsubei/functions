<?php

/**  1
 * 该函数实现文件上传,上传成功后返回上传的文件名
 * @param $uploadfile Array  上传文件数组
 * @param $path       string 文件存储路径
 */
function upload($uploadfile, $path)
{
    $filename = mt_rand(100000, 999999); //随机数
    $filename .= $uploadfile['name'];
    if (move_uploaded_file($uploadfile['tmp_name'], $path.$filename)){
        return $filename;
    } else {
        return false;
    }
}

/**  2
 *创建保存信息的函数
 * @param $array array 包含需要保存的信息的字段
 * @param $str   string 用什么分割符拼接字符串
 */
 function save($array, $str, $dataFile)
 {
     $data = implode($array,"|")."\n";//字符串拼接
     if (file_put_contents($dataFile, $data, FILE_APPEND)){
    return true;     
     } else {
         return false;
     }  
 }
 
 /**  3
  * 生成向数据库添加数据语句
  * @param $table  string  数据库名
  * @param $fields  obj   要添加的对象
  */
 function createInsertSql($table, $fields)
{
    //insert into 表名 set key=value
    $insert = "insert into ";
    $insert .= $table;
    $insert .= " set ";
    foreach ($fields as $key=>$val) {
        $insert .= "`$key`='".$val."',";
    }
    $insert = rtrim($insert, ",");
    return $insert;
}


/** 4
 *生成更改数据库数据语句
 *@param $table string 修改的表名       "student"
 *@param $fields array 修改的字段键值对  ["name"=>"uxpe"]
 *@param $where string 条件 		     	'id=5'
 *  例如 update student set `name`='upxe' where id=5
 */
function createUpdateSql($table, $fields, $where)
{
    $update = "update ";
    $update .= " $table ";
    $update .= " set ";
    foreach ($fields as $key=>$val) {
        $update .= "`$key`='".$val."',";
    }
    $update = rtrim($update, ",");//从字符串右侧移除字符
    $update .= " where ".$where;
    return $update;
}
    
/** 5
 * 生成删除数据库元素语句
 * @param $table  string 表名 "student"
 * @param $where  string 删除条件	"id=5"
 *  如：delete from student where id=5
 */
function createDeleteSql($table, $where)
{
    $delete = "delete from $table where $where";
    return $delete;
}
    
/** 6
 * 查询语句
 * @param $table string 
 * @param $fields string (*|多字段,分隔
 * )
 * @param $where string
 * @param $groupField=null 分组字段
 * @param $order array  ([字段，排序方式])
 * @param $limit  string|array 查询limit
 * 
 * select * from `student` where 1=1 order by id asc limit 1
 */  
 function createSelectSql($table, $fields, $where, $order, $limit, $groupField=null)
 {
     $select = "select $fields from `$table` ";
     //判定有没有传入条件
     if (!empty($where)) {
         $select .= " where $where ";
     }
     //判定排序要求
     if (!empty($order)) {
         $select .= " order by {$order[0]} {$order[1]} ";
     }
     //判定是否需要分组
     if (!empty($groupField)) {
         $select .= " group by $groupField ";
     }
     
     //limit判定是否是一个数组
     if (is_array($limit)) {
         $select .= "limit {$limit[0]}, {$limit[1]}";
     } else {
         if (!empty($limit)) {
             $select .= "limit $limit";
         }
     }
     return $select;
 }
     
	 
/** 7
 * 数据库查询，返回查询结果数据
 */
 function select($table, $fields, $where, $order, $limit)
{
    global $link;
    $sql = createSelectSql($table, $fields, $where, $order, $limit);
    $result = mysqli_query($link, $sql);
    $datas = mysqli_fetch_all($result,MYSQLI_ASSOC);
    return $datas;
}

/** 8
 * 数据库信息更新
 */
function update($table, $array, $where)
{
    global $link;
    $sql = createUpdateSql($table,  $array, $where);
    mysqli_query($link, $sql);
}

/** 9
 * 数据库信息插入，返回查询结果数据
 */
 function insert($table, $fields)
{
    global $link;
    $sql = createInsertSql($table, $fields);
    $result = mysqli_query($link,$sql);
    return $result;
}

          
/** 10
 *删除数据库信息，返回数据库信息
 */         
 function delete($table, $where)
 {
      global $link;
      $sql = createDeleteSql($table, $where);
      return mysqli_query($link, $sql);
}
 
 /** 11
 * 判断文件夹是否存在  创建文件夹
 */     
//	if(!file_exists('./myimgs')){
//		mkdir("./myimgs");
//	}

/**
 * 筛选表单1中的匹配信息，将指定值相同的项合并，想信息传递个另一个表单，获取相应信息，并加载充分项的数量
 * */
 function($message){
 	$arr=[];
		foreach($message as $value){
			array_push($arr,$value['ub_book']);//将$message 中ub_book对应值存到arr数组中
		}
		$arr = array_count_values($arr);//将数组处理成关联数组，key为ub_book值，value为出现次数
		$book="";
		$out=[];
		foreach($arr as $key=>$value){
			$table = "book_message";
			$fields = "*";
			$where ="b_id='".$key."'";
			$order = ['b_id','asc'];
			$limit = null;
			$bookdatas = select($table, $fields, $where, $order, $limit);
			$arr = array("booknum"=>"$value");
//			print_r($bookdatas);
			$book = array_merge_recursive($bookdatas[0],$arr);//把两个数组合并为一个数组
			array_push($out,$book);
		}
		echo json_encode($out);
 }
?>