/*
Author: Elton Martins Marcelino
Version: 1.0
Copyright:  All rights reserved
Date: 2014/06/12 : 19:55
Campinas-SP | Brazil
*/

-- INSERT QUERIES

-- USE DATABASE dbAlundraSystem
use dbAlundraSystem;

-- INSERT INTO tbJobs
insert into tbJobs (job) values ('Front-end Developer'),('Back-end Developer'),('Systems Analyst'),('Tester'),('Web Designer'),('Administrator');

-- INSERT INTO tbStatus
insert into tbStatus (taskStatus) values ('Open'),('Development'),('Test'),('Completed');

-- INSERT INTO tbTestsStatus
insert into tbTestsStatus (testStatus) values ('Analysis'),('Approved'),('Disapproved');

-- INSERT INTO tbUser (ADMIN)
insert into tbUsers (name,lastName,email,photo,username,password,secretQuestion,secretAnswer,activeUser) 
values ('Administrator','System','_admin-email','','admin','$2a$10$Cf1zG1ePJhKlBJo040F6aeqD8yk26Xt3MnVPKtFYjZyfwhzfPBXbuCf1zG1ePJhKlBJo040F6al','adminquestion','adminanswer',1);

insert into tbUsersXtbJobs (idJob,idUser) 
select 6, idUser from tbUsers where username = 'admin';

insert into tbReminders VALUES (1,'my R');