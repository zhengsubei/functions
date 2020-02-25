<?php
	// session 与cookie  的区别
	// 1.cookie将用户的数据写给用户的浏览器
	// 2、session将洗洗写到用户独占的session中，后台服务器中，生成32位的id来区分，并且把session以cookie的心事发送到客户端
	// Session是在服务端保存的一个数据结构，用来跟踪用户的状态，这个数据可以保存在集群、数据库、文件中；
	// Cookie是客户端保存用户信息的一种机制，用来记录用户的一些信息，也是实现Session的一种方式。



	// 第一步是开启session：  session_start();  它的作用是开启session，并随机生成一个唯一的32位的session_id
	// 二  写入些数据  $_SESSION['hello'] = 123、
?> 


