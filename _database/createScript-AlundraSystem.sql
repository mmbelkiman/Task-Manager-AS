/*
Author: Elton Martins Marcelino
Version: 1.0
Copyright:  All rights reserved
Date: 2014/06/12 : 19:55
Campinas-SP | Brazil
*/

/* CREATE QUERIES */

-- CREATE DATABASE dbAlundraSystem
create database dbAlundraSystem;

-- USE DATABASE dbAlundraSystem
use dbAlundraSystem;

-- CREATE TABLE tbJobs
create table tbJobs
(
idJob int auto_increment
,job varchar(100)
,primary key(idJob)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbUsers
create table tbUsers
(
idUser int auto_increment
,name varchar(100)
,lastName varchar(100)
,email varchar(100)
,photo varchar(250)
,username varchar(200)
,password varchar(250)
,secretQuestion varchar(250)
,secretAnswer varchar(250)
,activeUser bit
,primary key(idUser)
,unique(email)
,unique(username)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbUsersXtbJobs
create table tbUsersXtbJobs
(
	idJob int
	,idUser int
	,foreign key(idJob) references tbJobs(idJob) 
	,foreign key(idUser) references tbUsers(idUser) 
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbReminders
create table tbReminders
(
	idUser   int
	,reminder varchar(10000)
	,foreign key(idUser) references tbUsers(idUser) 
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbStatus
create table tbStatus
(
idTaskStatus int auto_increment
,taskStatus varchar(100)
,primary key(idTaskStatus)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbTasks
create table tbTasks
(
idTask int auto_increment
,idJob int
,idTaskStatus int
,name varchar(300)
,description varchar(300)
,createDate datetime
,updateDate datetime
,idUserDevelop int
,idUserCreation int
,hoursPlanned time
,hoursReal time
,activeTask bit
,deletTime datetime
,primary key(idTask)
,foreign key(idTaskStatus) references tbStatus(idTaskStatus)
,foreign key(idJob) references tbJobs(idJob)
,foreign key(idUserDevelop) references tbUsers(idUser)
,foreign key(idUserCreation) references tbUsers(idUser)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbTasksComments
create table tbTasksComments
(
idComment int auto_increment
,idTask int
,idUser int
,commentText text
,dateComment datetime
,activeComment bit
,deletTime datetime
,primary key(idComment)
,foreign key(idTask) references tbTasks(idTask)
,foreign key(idUser) references tbUsers(idUser)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbTests
create table tbTests
(
idTest int auto_increment
,idTask int
,testDate datetime
,testDateReady datetime
,primary key(idTest)
,foreign key(idTask) references tbTasks(idTask)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbTestsComments
create table tbTestsComments
(
idComment int auto_increment
,idTest int
,idUser int
,commentText text
,dateComment datetime
,activeComment bit
,deletTime datetime
,primary key(idComment)
,foreign key(idTest) references tbTests(idTest)
,foreign key(idUser) references tbUsers(idUser)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

create table tbTestsStatus
(
idTestStatus int auto_increment
,testStatus varchar(100)
,primary key(idTestStatus)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbTestsxtbUsers
create table tbTestsXtbUsers
(
idTest int
,idUser int
,idTestStatus int
,testDateChange datetime
,foreign key(idTest) references tbTests(idTest)
,foreign key(idUser) references tbUsers(idUser)
,foreign key(idTestStatus) references tbTestsStatus(idTestStatus)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE tbCommits
create table tbCommits
(
idCommit int auto_increment
,idTask int
,files varchar(10000)
,dateUpdate datetime
,dateUpload datetime
,primary key(idCommit)
,foreign key(idTask) references tbTasks(idTask)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;