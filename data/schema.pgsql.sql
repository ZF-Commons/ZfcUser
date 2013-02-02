CREATE TABLE public.user
(                                                                                                                                                                                                                                                                              
  user_id		serial NOT NULL,
	username	character varying(255) DEFAULT NULL UNIQUE,
	email		character varying(255) DEFAULT NULL UNIQUE,
	display_name	character varying(50) DEFAULT NULL,
	password	character varying(128) NOT NULL,
	state		smallint,

CONSTRAINT user_pkey 		PRIMARY KEY (user_id),
CONSTRAINT user_username_key 	UNIQUE (username),
CONSTRAINT user_email_key 	UNIQUE (email)
);
