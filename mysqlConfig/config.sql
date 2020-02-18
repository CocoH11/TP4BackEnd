drop table if exists `user`;

create table if not exists `user` (

 user_id  int auto_increment,

 user_name varchar(100) NOT NULL,

 user_firstname varchar(100) NOT NULL,

 `user_email` varchar(100) not null,

 `user_login` varchar(100) NOT NULL,

 `user_password` varchar(100) NOT NULL,

 `user_token` varchar(100) NOT NULL,

 primary key (user_id)

) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

insert into user values ('hello', 'hello', 'hello', 'hello', 'hello', 'hello', 'hello');