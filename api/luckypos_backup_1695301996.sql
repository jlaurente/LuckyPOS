

CREATE TABLE `adjustment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id_no` varchar(15) NOT NULL,
  `comp_id` varchar(10) NOT NULL,
  `adjtype` varchar(6) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `trxn_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `reason` varchar(200) NOT NULL,
  `added_date` datetime NOT NULL,
  `type` varchar(50) NOT NULL,
  `ref_id` bigint(20) DEFAULT NULL,
  `added_by` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

INSERT INTO adjustment VALUES("17","2023060116","100345","Credit","100.00","2023-08-01 02:56:59","2023-08-31 00:00:00","vdbdfd","2023-09-15 17:38:01","Credit Order","8","Eilesiyo");
INSERT INTO adjustment VALUES("18","","100345","Credit","100.00","2023-09-15 23:38:58","2023-09-30 00:00:00","fdfs","2023-09-15 18:39:44","Payment","1","Eilesiyo");
INSERT INTO adjustment VALUES("19","","100345","Debit","-10.00","2023-07-19 23:38:58","2023-08-15 00:00:00","gdf","2023-09-15 18:39:59","Payment","1","Eilesiyo");
INSERT INTO adjustment VALUES("20","2023060116","100345","Credit","77.00","2023-09-30 02:56:59","2023-10-15 00:00:00","fsdds","2023-09-15 18:42:37","Credit Order","8","Eilesiyo");
INSERT INTO adjustment VALUES("21","2023060116","100345","Debit","-77.00","2023-09-30 02:56:59","2023-10-15 00:00:00","ghfgh","2023-09-15 18:42:56","Credit Order","8","Eilesiyo");
INSERT INTO adjustment VALUES("22","","100345","Credit","500.00","2023-09-18 05:52:23","2023-10-15 00:00:00","fsdf","2023-09-21 13:34:33","Payment","3","Admin");



CREATE TABLE `companydetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_name` varchar(50) DEFAULT NULL,
  `comp_id` varchar(10) DEFAULT NULL,
  `amount_limit` bigint(20) DEFAULT NULL,
  `added_date` datetime NOT NULL,
  `added_by` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

INSERT INTO companydetail VALUES("1","MSM Philippines","100234","10000","2023-09-06 05:33:22","Test");
INSERT INTO companydetail VALUES("5","OutsourceIT","100345","15000","2023-09-10 07:50:59","Test");
INSERT INTO companydetail VALUES("6","Cebu General Services, Inc.","100123","5000","2023-09-14 11:08:46","Admin");



CREATE TABLE `customerdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_barcode_num` varchar(15) NOT NULL,
  `cust_id` varchar(15) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `comp_id` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `added_by` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

INSERT INTO customerdetail VALUES("1","432424","432424","Mingasca","Ireseddd","P.","0","100234","2023-09-06 08:46:47","Test");
INSERT INTO customerdetail VALUES("2","002523","002523","Vailoces","Michael","C.","1","100234","2023-09-06 09:25:25","Test");
INSERT INTO customerdetail VALUES("3","002524","002524","Bucao","Alec","C.","1","100345","2023-09-06 09:50:31","Test");
INSERT INTO customerdetail VALUES("4","2023060116","2023060116","Ibrahim","Lillia","B","1","100123","2023-09-06 10:30:35","Test");
INSERT INTO customerdetail VALUES("5","555","555","D","D","D","0","100234","2023-09-13 15:18:45","2023-09-13 15:18:45");



CREATE TABLE `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` varchar(15) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `added_date` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

INSERT INTO payment VALUES("1","100345","-150.25","2023-09-30","2023-09-15 23:38:58","Admin");
INSERT INTO payment VALUES("2","100345","-1445.25","2023-09-30","2023-09-18 05:13:19","Eilesiyo");
INSERT INTO payment VALUES("3","100345","-100.50","2023-10-15","2023-09-18 05:52:23","Eilesiyo");
INSERT INTO payment VALUES("4","100345","-690.00","2023-09-15","2023-09-21 14:13:44","Admin");
INSERT INTO payment VALUES("5","100345","-1269.00","2023-10-15","2023-09-21 14:14:40","Admin");
INSERT INTO payment VALUES("6","100345","-60.00","2023-09-30","2023-09-21 14:15:17","Admin");



CREATE TABLE `transactiondetailcash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(20,2) NOT NULL,
  `added_date` datetime NOT NULL,
  `added_by` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

INSERT INTO transactiondetailcash VALUES("1","200.00","2023-09-08 07:33:14","Test");
INSERT INTO transactiondetailcash VALUES("2","150.00","2023-09-08 13:39:19","Test");
INSERT INTO transactiondetailcash VALUES("3","250.57","2023-09-08 13:43:53","Test");
INSERT INTO transactiondetailcash VALUES("5","210.00","2023-09-12 06:13:46","Admin");
INSERT INTO transactiondetailcash VALUES("7","12.00","2023-09-15 15:23:59","Admin");
INSERT INTO transactiondetailcash VALUES("8","12.00","2023-09-15 15:24:22","Admin");
INSERT INTO transactiondetailcash VALUES("9","12.00","2023-09-15 15:31:14","Admin");
INSERT INTO transactiondetailcash VALUES("10","12.00","2023-09-15 15:31:16","Admin");
INSERT INTO transactiondetailcash VALUES("11","12.00","2023-09-15 15:31:16","Admin");
INSERT INTO transactiondetailcash VALUES("12","12.00","2023-09-15 15:31:16","Admin");
INSERT INTO transactiondetailcash VALUES("13","12.00","2023-09-15 15:31:30","Admin");
INSERT INTO transactiondetailcash VALUES("14","10.00","2023-09-15 15:32:20","Admin");
INSERT INTO transactiondetailcash VALUES("15","3.00","2023-09-15 15:33:23","Admin");
INSERT INTO transactiondetailcash VALUES("16","8.50","2023-09-15 15:33:42","Admin");
INSERT INTO transactiondetailcash VALUES("17","701.50","2023-09-18 04:55:16","Eilesiyo");



CREATE TABLE `transactiondetailcredit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id_no` varchar(15) NOT NULL,
  `comp_id` varchar(10) NOT NULL,
  `amount` float(20,2) NOT NULL,
  `due_date` date NOT NULL,
  `added_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `added_by` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

INSERT INTO transactiondetailcredit VALUES("6","002524","100345","150.00","2023-09-30","2023-09-30 02:47:40","Admin");
INSERT INTO transactiondetailcredit VALUES("7","002523","100234","160.00","2023-10-15","2023-09-30 02:54:26","Admin");
INSERT INTO transactiondetailcredit VALUES("8","2023060116","100345","200.00","2023-10-15","2023-09-30 02:56:59","Admin");
INSERT INTO transactiondetailcredit VALUES("9","2023060116","100345","50.00","2023-09-30","2023-09-30 03:06:20","Admin");
INSERT INTO transactiondetailcredit VALUES("10","002524","100345","600.00","2023-09-30","2023-09-30 13:56:58","Admin");
INSERT INTO transactiondetailcredit VALUES("11","432424","100123","200.00","2023-09-30","2023-09-30 09:50:28","Eilesiyo");
INSERT INTO transactiondetailcredit VALUES("12","432424","100123","30.00","2023-09-30","2023-09-15 15:11:53","Admin");
INSERT INTO transactiondetailcredit VALUES("13","432424","100123","300.00","2023-09-30","2023-09-15 15:14:21","Admin");
INSERT INTO transactiondetailcredit VALUES("14","432424","100123","-2.00","2023-09-30","2023-09-15 15:14:56","Admin");
INSERT INTO transactiondetailcredit VALUES("15","432424","100123","0.00","2023-09-30","2023-09-15 15:19:53","Admin");
INSERT INTO transactiondetailcredit VALUES("16","2023060116","100345","305.00","2023-09-30","2023-09-15 15:38:49","Admin");
INSERT INTO transactiondetailcredit VALUES("17","002524","100345","100.00","2023-11-15","2023-09-19 18:12:12","Eilesiyo");
INSERT INTO transactiondetailcredit VALUES("18","2023060116","100345","100.00","2023-11-15","2023-09-19 18:15:31","Eilesiyo");
INSERT INTO transactiondetailcredit VALUES("19","002524","100345","67.00","2023-10-15","2023-09-19 18:22:30","Eilesiyo");
INSERT INTO transactiondetailcredit VALUES("20","002524","100345","267.00","2023-10-15","2023-09-19 18:22:56","Eilesiyo");
INSERT INTO transactiondetailcredit VALUES("21","2023060116","100345","600.00","2023-09-02","2023-09-02 16:12:16","Eilesiyo");
INSERT INTO transactiondetailcredit VALUES("22","2023060116","100345","234.00","2023-09-30","2023-09-02 16:24:14","Eilesiyo");
INSERT INTO transactiondetailcredit VALUES("23","2023060116","100345","125.00","2023-10-15","2023-09-20 16:27:14","Eilesiyo");
INSERT INTO transactiondetailcredit VALUES("24","2023060116","100345","125.00","2023-10-15","2023-09-21 02:43:53","Admin");
INSERT INTO transactiondetailcredit VALUES("25","2023060116","100345","100.00","2023-09-30","2023-09-05 02:46:25","Admin");
INSERT INTO transactiondetailcredit VALUES("26","2023060116","100345","50.00","2023-10-15","2023-09-21 03:35:35","Admin");
INSERT INTO transactiondetailcredit VALUES("27","2023060116","100345","90.00","2023-10-15","2023-09-21 09:53:41","Admin");



CREATE TABLE `userdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `added_date` datetime NOT NULL,
  `added_by` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

INSERT INTO userdetail VALUES("1","admin","admin","Admin","Admin","A.","Administrator","1","1","2023-09-05 10:17:12","Test");
INSERT INTO userdetail VALUES("6","cashier","cashier","Villareal","Eilesiyo","B.","","2","1","2023-09-06 03:19:09","Test");
INSERT INTO userdetail VALUES("7","dexter01","dexter01","Suerte","Dexter","B.","","2","0","2023-09-10 13:43:49","Test");



CREATE TABLE `userrole` (
  `role_id` int(11) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO userrole VALUES("1","Administrator");
INSERT INTO userrole VALUES("2","Cashier");

