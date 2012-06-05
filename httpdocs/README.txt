///////////////////////////////////////////
/////////// Welcome to Applewood /////////
/////////////////////////////////////////
////////////////////////////////////////


////////////////////
///   Credits  ////
//////////////////

Site Engine: "Black Jaguar Framework", written by Kyle Harrison <silent.coyote1@gmail.com> © Black Jaguar Studios 2012, https://github.com/DJDarkViper/Black-Jaguar-Framework

Kerosene CMS: Kyle Harrison <kyle@navigatormm.com> (© Navigator Multimedia Inc 2012)
Programming: Kyle Harrison <kyle@navigatormm.com>
Front Design: Chris Kormish <chrisk@navigatormm.com>
Administration Design: Alex Marshall <alexm@navigatormm.com>
Additional CSS: Alex Marshall <alexm@navigatormm.com>


//////////////////////////
///   What is this?  ////
////////////////////////


This site is a big web app disguised as a site so it may seem a little different
in approach than what you may be used too, so please read this carefully if your
not familair wtih MVC coding practices.

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

Q: When i type in a URL, whats happening?
A: The system operates in URI segments, in which is broken into english like this:
"Site URL, Controller, Method, Argument(s)".
so:
http://www.mysite.com/controller/method/argument/argument/argument (etc and so on and so fourth)

How this system operates, is it looks for a controller file in the /app/controllers directory that matches
the name of the URI segment, so http://www.mysite.com/contact is going to look for /app/controllers/contact.php

In order for this to work right, this site operates entirely on an OOP based level using classes.
But dont worry, its very easy to look at.
Inside this contact.php file will be the minimum this:

<?
class contact {

	public function index() {

	}

}?>

The class name must be the same name as the file, case-sensitive.
Inside will be a list of "methods" if you are unfamilair with OOP terminology, think of a "method" exactly
like a standard function.
Notice the "index" method being listed here. This is THE default method called when no 2nd URI segment is specified.
so to recap: http://www.mysite.com/contact
will do this:
1. look for /app/controllers/contact.php
2. It will instantiate a new "contact" class, allowing us to use the properties (variables) and methods (functions) inside
3. If no other segment is provided, will call index() automatically

this said, to jump to a new "page" inside of contact (say a specific segment of the site, like a business location?)
http://www.mysite.com/contact/business-locations

the controller for this to work should now look like this:

<?
class contact {

	public function index() {
	}

	public function business_locations() {
	}

}?>
(Note: when a hyphen is provided in a URI segment, such as about-us, it will automatically be converted to underscore (_) for usage in code)

If any further URI segments are found, these are injected into the method as arguments, so:

http://www.mysite.com/contact/person/billy-bob

the controller would need to look like this:

<?
class contact {

	public function index() {
	}

	public function person($name) {

	}
}?>

so the $name argument in the person method will be injected with "billy-bob" as a value

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

Q: What the heck is MVC?
A: MVC stands for "Model-View-Controller".
The point of MVC is to separate Logic from Display, meaning as little PHP code to be used in the Display files as possible.
Of course, you cant avoid it most times, so the system employs this workflow:
Controllers: The point of contact, handles requests to and from models, and injects them into requested views.
Model: A database and object handler, consider these files as the point of contact with a data source of some kind (here its MySQL)
View: As raw HTML as possible, these are injected "template" files if you will, that are loaded upon request at specific points. 
			Note: views can load more views inside of themselves

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

Q: Okay, so how do i use any of these files? include()?
A: Hell no.
Use the load class.
All you need to do to load anything into usage, is "load::" and whatever you want.
load::model(""); // will load in a model to be used at this point, these files are located in /app/models
load::view(""); // will load in a view file to be used at this point, these files are located in /app/views
load::assistant(""); // Loads in an assistant to be used at this point, these files are located in /app/assistants
load::plugin(""); // Loads in a plugin to be used at this point, these files are located in /app/plugins

If you find that you are using a model, assistant or plugin a lot or on all pages, check out the /config/autoload.php file
and add it to its appropriate list. You will no longer need to use the load class to use whats in the specifed files.
(no there is no autoload for views)

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

Q: What is a plugin and an assistant?
A: Honestly, anything.
This is mostly for organization purposes to place two very specific kinds of files. But this is whats supposed to go there.

Assistant: is a single or list of orphan functions available for useage.
Heres a good one:

/app/assistants/string_helpers.php
<?
if(!function_exists("bar")) {
	function bar($text) {
		$text = "bar";
		return $text;
	}
}
if(!function_exists("foo")) {
	function foo($text) {
		$text = "foo".bar();
		return $text;
	}
}
?>

An example here, is the HTML Helpers assistant built into the engine:

<?=inc("jq");?>




Plugins: is a class file that contains a high level of functionality about a specific "thing"

/app/plugins/dateStuff.php
<?
class dateStuff {

	$date = null;

	public function __construct($date) {
		$this->date = strtotime($date);
	}

	public function getDate() {
		return date("D m, Y", $this->date);
	}
}
?>

An example here, is the Database Driver plugin built into the engine:

$db->where("id", 1);	// setup where clause
$fetch = $db->select("*")->from("table")->get()->results(); // (this plugin supports Chainable Methods, but its not required)

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

I hope this gets you up off the ground in finding where you have to go to find specific things. to modify.
Again if your not used to MVC, sorry upfront for the confusion, its definitely a different beast.
But the good part about it is? It allows you to get a site up and deployed at extreme rapid rates.


You can download the engine/framework used here: https://github.com/DJDarkViper/Black-Jaguar-Framework
Its constantly worked on, and if you have any input, or wish to contribute code to it, please fork it and send a pull request :)



Much love,


~K
