CREATE TABLE events (
    id serial primary key,
	mail varchar(50),
    DateH datetime ,
    title varchar(255),
	Foreign key (mail) references membres (mail)
);
/*
CREATE TABLE events (
    id int not null AUTO_INCREMENT,
	mail varchar(50),
    DateH datetime ,
    title varchar(255),
Primary key(id),
Foreign key (mail) references membres (mail)
);*/
