<p>
	Do you love HeidiSQL as much as I do?  It’s really the best MySQL front-end
	I’ve found so far for Windows.  Sure, MySQL Workbench has all kinds of
	super-fancy features and GUI tools and what-not.  But, in Workbench, I find
	that it takes sixteen mouse clicks to perform the same task that you can do
	in five with HeidiSQL.
</p>

<p>
	I use HeidiSQL for nearly everything, and only have had one major beef with
	it.  I could never get SSH Tunnels to work with it until today I figured it
	out! I’m super-stoked.  If you’re having problems with Heidi and SSH too,
	I’ll provide a step-by-step below.
</p>

<p>
	If you want to skip the background junk, and go right to the procedure,
	be my guest!
</p>

<h3>First, why SSH Tunnels?</h3>

<p>
	Okay, if you’re reading this article, I assume you are at-least familiar with
	MySQL and HeidiSQL.  Chances are, you connect to your database server via the
	default port 3306.  You open up HeidiSQL, enter your DB Username, password,
	and host, and away you go.
</p>

<p>
	The problem with this approach is that the MySQL protocol that you are using
	to transfer your data around is inherently insecure.  Folks sniffing network
	traffic can intercept your data as it travels over the network.  So, if you
	care about your data or your database not being hacked, it’s a good idea to
	encrypt the traffic between your client (HeidiSQL) and your server
	(MySQL Server).
</p>

<p>
	<img src="<?php echo$page_url; ?>insecure_mysql.png" alt="Insecure!  Boo!" />
</p>

<p>
	SSH, on the other hand, is a secure protocol.  All traffic between the client 
	and the server is encrypted so that nefarious network sniffer folks can’t
	decrypt the traffic (easily).
</p>

<p>
	What you want to do is to convert the insecure MySQL traffic to secure SSH
	traffic on your computer before it hits the Internet.  On the server-side,
	you want to decode the SSH traffic and pass it along the MySQL server.
	Something like this:
</p>

<p>
	<img src="<?php echo$page_url; ?>secure_ssh_traffic.png" alt="Secure SSH Traffic.  Yay!" />
</p>


<h3>How to Do It</h3>

<p>
	First, make sure you know what your SSH username and password are on the
	server where your MySQL database lives.  Then, follow along:
</p>

<ol>
	<li>Download Plink.exe from the <a href="http://www.chiark.greenend.org.uk/~sgtatham/putty/" title="Go get it!">PuTTY website</a>.  Plink is a nifty little SSH tool for Windows that allows you easily setup a SSH tunnel.</li>
	<li>Place the downloaded file anywhere on your hard-drive you wish.  It’s probably a good idea to put it somewhere inside your home directory.</li>
	<li>Now, this is the magic step!  Before HeidiSQL can use Plink to connect to your server, you must download the server’s public key to your computer.</li>
	<li>So, fire up your command-line, and browse to wherever you put the plink.exe file.</li>
	<li>Type: <pre class="code">plink.exe -L 3307:localhost:3306 [USERNAME]@[YOURSERVER.COM]</pre></li>
	<li>If it worked, you’ll get a big long message that ends with “Store key in Cache? (y/n)”</li>
	<li>Say “yes”, of course.  Where does it put this key?  That was a mystery to me too!  It turns out, when you say yes, Plink will put the key into your Windows Registry (at <code>HKEY_CURRENT_USER\Software\SimonTatham\PuTTY\SshHostKeys</code>).</li>
	<li>Type your password, and then type <code>exit</code> to finish up this step.</li>
	<li>Fire up HeidiSQL!  Now that you’ve downloaded the key, you’re ready to use SSH Tunneling in HeidiSQL!</li>
	<li>Create a new connection, and choose “SSH Tunnel” from the “Network Type” dropdown.</li>
	<li>Inside the “Settings” tab, use <code>127.0.0.1</code> for the hostname.  Then, enter your normal database username and password.  Yep, 127.0.0.1 is correct.  You enter the address as if you were logged-in to the server via SSH.</li>
	<li>Next, flip to the “SSH Tunnel” tab.  Tell HeidiSQL where the plink.exe file is on your hard-drive, then put the actual IP address or DNS name for your database server.  Use 22 (SSH) for the port.</li>
	<li>Enter your SSH username and password, and then choose “3307″ (or some other unused port) on your computer for the “Local Port”.</li>
	<li>Let ‘er rip!</li>
</ol>
