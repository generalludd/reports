/* In a future version of this site, the teacher table will be split into teacher and 
user to allow for more flexibility, for now, the admin user is identified as user 1000 and this is required for the interface to function*/

INSERT INTO `teacher` (`kTeach`, `user_id`, `username`, `email`, `teachFirst`, `teachLast`, `pwd`, `teachClass`, `gradeStart`, `gradeEnd`, `isAdvisor`, `dbRole`, `status`, `resetHash`, `recModified`, `recModifier`)
VALUES
	(1000, 1, 'administrator', 'email@example.com', 'Database', 'Administrator', '', '', 0, 0, NULL, 1, 1, '', '', '');
