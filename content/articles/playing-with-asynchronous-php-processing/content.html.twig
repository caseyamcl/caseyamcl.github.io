<p>
	A subject that has intrigued me lately is this topic of
	asynchronous PHP processing.  The idea is you have a web application that
	includes a task which takes a long time, like, say processing video files or
	sending mass emails.  Your users should be able to click a button, get a
	notification that their job is waiting to be processed, and then go about 
	their business.	
</p>

<p>
	There are all sorts of really cool queuing technologies, all of
	which have PHP libraries.  So creating and managing job queues was no problem.
	My confusion was how to setup a worker script to process those queues.
</p>

<p>
	There is absolutely no way to get around the fact that you will need at-least
	two distinct, simultaneous processes running on your server in order to make
	this happen.  The process that initiates the queue jobs is presumably Apache
	or other webserver software.  The second process runs in the background;
	it sits and waits for jobs and processes them as they come in.
</p>

<p>
	The only way to have a process continually listening for jobs on a Linux
	server is for it to be a daemon.  And, of course, unless you are a system
	administrator with root privileges, setting up a reliable daemon is not
	really an option.  So, if you are using a shared hosting provider, you are
	kind of out-of-luck.
</p>

<p>
	Or, are you?
</p>

<p>
	One way to make it happen is to use CRON, MySQL, and PHP-CLI, all of which
	good shared hosting providers allow you to use.  The idea is this: Every
	minute, a cron-task spawns a PHP-CLI script.  The script is smart enough to
	see if prior instance of itself is still running, and if not, it processes 
	the job queue.  The queue can be a simple MySQL database table, or something 
	fancier like a Beanstalkd queue or some implementation of Zend_queue.
</p>

<p>
	Here's how the script would run:
</p>

<p>[[Image Here]]</p>

<p>
	And, below is really rough example of how I might begin to implement
	this in PHP, with logging thrown in. Now, of course, this is not production
	code, but it's a nice template to start with.
</p>