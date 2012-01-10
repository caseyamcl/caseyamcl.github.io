<p>
	I'm a big CodeIgniter fan, and we use it a lot in my work.  One CI-Related
	issue that has been troublesome for my team has been the ability to setup
	development environments on everybody's computers.
</p>

<p>
	All of us use different operating systems, different path and URLs, and 
	different database configurations, among other things.  In addition, our testing
	and production servers also have a variety of different environments.  So, 
	it turns out that there are a lot of settings in each of our <var>application/config</var>
	files that need to be different for each computer.
</p>

<p>
	For example, when working on email functionality, our Windows developers
	typically need to Configure CI with SMTP, while our Linux/Mac folks use <em>sendmail</em>.
</p>

<p>
	Of course, some configuration directives should remain the same across all
	development environments.  Things like <em>language</em>, <em>charset</em>,
	<em>enable_hooks</em>, etc. should not change from computer to computer.
</p>

<p>
	It is possible to make some configuration directives consistent across 
	environments and some variables unique.
</p>

<h3>Making a <em>local.config.php</em> file in CodeIgniter</h3>

<p>
	The solution is to create a local configuration file that overrides whichever
	settings you wish to be different from machine to machine.  This way, local
	environments can set certain settings locally, and use the defaults in the
	<var>application/config</var> folder by default.  Here's how to do it&hellip;
</p>

<ol>
	<li>
			Create a local configuration file, and add certain configuration settings
			that you want to override.  You can put it in your document root folder, 
			and name it <var>config.local.php</var>. For example:
			<pre><code>
&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/* Local Configuration Options */
$config["base_url"] = "http://localhost/projects/my_project/";
$config["index_page"] = "index.php";
$config["uri_protocol"] = "AUTO";
$config["email_protocol"] = "sendmail";
$config["mailpath"] = "/usr/bin/sendmail";
$config["smtp_host"] = "";
$config["smtp_user"] = "";
$config["smtp_pass"] = "";
$config["smtp_port"] = "25";
$config["smtp_timeout"] = "5";
$config['log_threshold'] = 4;
//..and whatever else you want to override..
 
/* EOF */</code></pre>
	</li>
	
	<li>
		Set your version control system to ignore this file.  If you’re using
		Mercurial, simply add a line to the .hgignore file:
		<pre class="code">^config\.local\.php$</pre>
		If you're on GIT, just add this:
		<pre class="code">config.local.php</pre>
	</li>
	
	<li>
		Subclass the <var>core/Config.php</var> library to read the local configuration
		file after reading the default one every time.  To do this, create the
		file <var>application/core/MY_Config.php</var> in your application folder, and add
		the following code inside that file:
		<pre class="code"><code>
&lt;?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Config extends CI_Config
{
	/**
	 * Load a config file - Overrides built-in CodeIgniter config file loader
	 * 
	 * @param string $file
	 * @param boolean $use_sections
	 * @param boolean $fail_gracefully 
	 */
	function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		parent::load($file, $use_sections, $fail_gracefully);
 
		//Local settings override permanent settings always.
		if (is_readable(FCPATH . 'system/config.local.php'))
			parent::load('system/config.local.php', $use_sections, $fail_gracefully);
	}
}
 
/* EOF: MY_Config */</code></pre>
	</li>
</ol>

<p>And that's that.</p>

<h3>But wait, you also have to override the database settings!</h3>

<p>
	Another file that is likely to be completely different from one environment
	to the next is the <var>application/config/database.php</var> file.  Since 
	CodeIgniter seems to read the database settings a bit differently than normal
	settings, we have to go through one extra step to override those.  It’s a bit
	messier than I’d like, but gets the job done:
</p>

<ol>
	<li>
		In the <var>local.config.php</var> file you created, add a line for each
		database setting you want to override, but put the database settings in their
		own sub-array:
		<pre class="code">
//...inside the local config file...
$config["db"]["hostname"] = "localhost";
$config["db"]["username"] = "localuser";
$config["db"]["password"] = "";
$config["db"]["database"] = "local_db";
$config["db"]["db_debug"] = "TRUE";
		</pre>
	</li>
	
	<li>
		Add the following code at the end of your <var>application/config/database.php</var>
		file to read those settings after reading your default database settings:
		<pre>
			<code>//If there is a local config file, overwrite the settings with that..
if (is_readable(FCPATH . 'config.local.php'))
{
	include_once(FCPATH . 'config.local.php');
	foreach($db['default'] as $key => $val)
		$db['default'][$key] = (isset($config['db'][$key])) ? $config['db'][$key] : $val;
}</code></pre>
	</li>
</ol>

<p>
	There you have it!  From now on, you can use your local configuration file to
	override any configuration setting in CodeIgniter.  This makes distributed
	development much easier.  Your developers can set their own base_path,
	database, index_file and other settings that are likely to change
	from one environment to the next without modifying the main configuration
	files.	
</p>