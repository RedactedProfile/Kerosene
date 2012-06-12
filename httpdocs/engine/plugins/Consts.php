<?php
class DateFormat {
	const RAW 			= 1;
	const SHORT 		= 2;
	const MEDIUM 		= 3;
	const LONG 			= 4;
	const MYSQL 		= 5;
	const SHORT_TIME 	= 6;
	const MEDIUM_TIME 	= 7;
	
}

class Filter {
	const ALL			= 1;
	const PUBLISHED		= 2;
	const UNPUBLISHED 	= 3;
	const ACTIVE 		= 4;
	const INACTIVE 		= 5;
}

class Sort {
	const ASC			= 1;
	const DESC			= 2;
}

class Target {
	const _SAME			= 1;
	const _NEW			= 2;
}