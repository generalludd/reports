-- Dummy teacher seed data for grades K-8
-- Run after schema.sql and admin_user.sql

INSERT INTO `teacher` (`kTeach`, `user_id`, `username`, `email`, `teachFirst`, `teachLast`, `pwd`, `teachClass`, `gradeStart`, `gradeEnd`, `isAdvisor`, `dbRole`, `status`, `resetHash`, `recModified`, `recModifier`)
VALUES
  -- Kindergarten (grade 0)
  (1,  NULL, 'sjohnson',   'sjohnson@school.edu',   'Sarah',    'Johnson',  '3714faf5c6953aad726265f1e94e8bb5', 'Kindergarten',  0, 0, 1, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),

  -- 1st-2nd grade (2 teachers)
  (2,  NULL, 'mchen',      'mchen@school.edu',      'Michael',  'Chen',     '3714faf5c6953aad726265f1e94e8bb5', '1st-2nd Grade', 1, 2, 1, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),
  (3,  NULL, 'erodriguez', 'erodriguez@school.edu', 'Emily',    'Rodriguez','3714faf5c6953aad726265f1e94e8bb5', '1st-2nd Grade', 1, 2, 1, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),

  -- 3rd-4th grade (2 teachers)
  (4,  NULL, 'dthompson',  'dthompson@school.edu',  'David',    'Thompson', '3714faf5c6953aad726265f1e94e8bb5', '3rd-4th Grade', 3, 4, 1, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),
  (5,  NULL, 'lmartinez',  'lmartinez@school.edu',  'Lisa',     'Martinez', '3714faf5c6953aad726265f1e94e8bb5', '3rd-4th Grade', 3, 4, 1, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),

  -- Humanities teachers for grades 5-8 (2 teachers)
  (6,  NULL, 'jwilson',    'jwilson@school.edu',    'James',    'Wilson',   '3714faf5c6953aad726265f1e94e8bb5', 'Humanities',    5, 8, 1, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),
  (7,  NULL, 'panderson',  'panderson@school.edu',  'Patricia', 'Anderson', '3714faf5c6953aad726265f1e94e8bb5', 'Humanities',    5, 8, 1, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),

  -- Science teacher for grades 5-8
  (8,  NULL, 'rgarcia',    'rgarcia@school.edu',    'Robert',   'Garcia',   '3714faf5c6953aad726265f1e94e8bb5', 'Science',       5, 8, 0, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),

  -- Math teacher for grades 5-8
  (9,  NULL, 'jlee',       'jlee@school.edu',       'Jennifer', 'Lee',      '3714faf5c6953aad726265f1e94e8bb5', 'Math',          5, 8, 0, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),

  -- Art teacher for grades K-8
  (10, NULL, 'tbrown',     'tbrown@school.edu',     'Thomas',   'Brown',    '3714faf5c6953aad726265f1e94e8bb5', 'Art',           0, 8, 0, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),

  -- Music teacher for grades K-8
  (11, NULL, 'kwhite',     'kwhite@school.edu',     'Karen',    'White',    '3714faf5c6953aad726265f1e94e8bb5', 'Music',         0, 8, 0, 2, 1, NULL, '2026-04-17T00:00:00', 'admin'),

  -- Spanish teacher for grades K-8
  (12, NULL, 'crivera',    'crivera@school.edu',    'Carlos',   'Rivera',   '3714faf5c6953aad726265f1e94e8bb5', 'Spanish',       0, 8, 0, 2, 1, NULL, '2026-04-17T00:00:00', 'admin');

ALTER TABLE `teacher` AUTO_INCREMENT = 100;


-- Teacher subjects

INSERT INTO `teacher_subject` (`kTeach`, `subject`, `gradeStart`, `gradeEnd`)
VALUES
  -- Sarah Johnson - Kindergarten
  (1, 'Reading',        0, 0),
  (1, 'Writing',        0, 0),
  (1, 'Math',           0, 0),
  (1, 'Science',        0, 0),
  (1, 'Social Studies', 0, 0),

  -- Michael Chen - 1st-2nd Grade
  (2, 'Reading',        1, 2),
  (2, 'Writing',        1, 2),
  (2, 'Math',           1, 2),
  (2, 'Science',        1, 2),
  (2, 'Social Studies', 1, 2),

  -- Emily Rodriguez - 1st-2nd Grade
  (3, 'Reading',        1, 2),
  (3, 'Writing',        1, 2),
  (3, 'Math',           1, 2),
  (3, 'Science',        1, 2),
  (3, 'Social Studies', 1, 2),

  -- David Thompson - 3rd-4th Grade
  (4, 'Reading',        3, 4),
  (4, 'Writing',        3, 4),
  (4, 'Math',           3, 4),
  (4, 'Science',        3, 4),
  (4, 'Social Studies', 3, 4),

  -- Lisa Martinez - 3rd-4th Grade
  (5, 'Reading',        3, 4),
  (5, 'Writing',        3, 4),
  (5, 'Math',           3, 4),
  (5, 'Science',        3, 4),
  (5, 'Social Studies', 3, 4),

  -- James Wilson - Humanities 5-8
  (6, 'History',        5, 8),
  (6, 'Language Arts',  5, 8),
  (6, 'Literature',     5, 8),

  -- Patricia Anderson - Humanities 5-8
  (7, 'History',        5, 8),
  (7, 'Language Arts',  5, 8),
  (7, 'Literature',     5, 8),

  -- Robert Garcia - Science 5-8
  (8, 'Science',        5, 8),

  -- Jennifer Lee - Math 5-8
  (9, 'Math',           5, 8),

  -- Thomas Brown - Art K-8
  (10, 'Art',           0, 8),

  -- Karen White - Music K-8
  (11, 'Music',         0, 8),

  -- Carlos Rivera - Spanish K-8
  (12, 'Spanish',       0, 8);
