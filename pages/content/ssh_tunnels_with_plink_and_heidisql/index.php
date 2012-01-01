<div class="two-thirds column">

		<h1>How to use SSH Tunnels with HeidiSQL and Plink</h1>
		<p>
			I wrote this on
			<span>November 1, 2010</span> and last updated on
			<span>September 15, 2011</span>
		</p>

		<div class="article_content">

			<p>
				Do you love HeidiSQL as much as I do?  It’s really the best MySQL front-end I’ve found so far for Windows.  Sure, MySQL Workbench has all kinds of super-fancy features and GUI tools and what-not.  But, in Workbench, I find that it takes sixteen mouse clicks to perform the same task that you can do in five with HeidiSQL.
			</p>

			<p>
				I use HeidiSQL for nearly everything, and only have had one major beef with it.  I could never get SSH Tunnels to work with it until today I figured it out! I’m super-stoked.  If you’re having problems with Heidi and SSH too, I’ll provide a step-by-step below (keep reading).
			</p>

			<p>
				If you want to skip the background junk, and go right to the procedure, be my guest!
			</p>

			<h3>First, why SSH Tunnels?</h3>

			<p>
				Okay, if you’re reading this article, I assume you are at-least familiar with MySQL and HeidiSQL.  Chances are, you connect to your database server via the default port 3306.  You open up HeidiSQL, enter your DB Username, password, and host, and away you go.s
			</p>

			<p>
				The problem with this approach is that the MySQL protocol that you are using to transfer your data around is inherently insecure.  Folks sniffing network traffic can intercept your data as it travels over the network.  So, if you care about your data or your database not being hacked, it’s a good idea to encrypt the traffic between your client (HeidiSQL) and your server (MySQL Server).
			</p>

			<p>
				SSH, on the other hand, is a secure protocol.  All traffic between the client and the server is encrypted so that nefarious network sniffer folks can’t decrypt the traffic (easily).
			</p>

			<p>
				What you want to do is to convert the insecure MySQL traffic to secure SSH traffic on your computer before it hits the Internet.  On the server-side, you want to decode the SSH traffic and pass it along the MySQL server.   Something like this:
			</p>

			<h3>How to Do It</h3>

			<p>
				First, make sure you know what your SSH username and password are on the server where your MySQL database lives.  Then, follow along:
			</p>

			<ol>
				<li>Download Plink.exe.  Plink is a nifty little SSH tool for Windows that allows you easily setup a SSH tunnel.</li>
				<li>Place the downloaded file anywhere on your hard-drive you wish.  It’s probably a good idea to put it somewhere inside your home directory.</li>
				<li>Now (this is the step that kept tripping  me up), before HeidiSQL can use Plink to connect to your server, you must download the server’s public key to your computer.</li>
				<li>So, fire up your command-line, and browse to wherever you put the plink.exe file.</li>
				<li>Type: <code>plink.exe -L 3307:localhost:3306 [USERNAME]@[YOURSERVER.COM]</code></li>
				<li>If it worked, you’ll get a big long message that ends with “Store key in Cache? (y/n)”</li>
				<li>Say “yes”, of course.  Where does it put this key?  That was a mystery to me too!  It turns out, when you say yes, Plink will put the key into your Windows Registry (at HKEY_CURRENT_USER\Software\SimonTatham\PuTTY\SshHostKeys).</li>
			</ol>

		</div>
		
</div>



<div class="one-third column post-meta">
	
	<h3 class="post-about">About this Post</h3>
	
	<img src="http://dummyimage.com/250x250/999/fff" class="post-image" alt="Dummy" />
	
	<p class="post-meta-dates">
		I wrote this on <span class="post-original-date">November 1, 2010</span> and
		last updated it on <span class="post-modified-date">October 10, 2011</span>
	</p>
	
	<p>
		It is about <span class="post-category">Web Development</span>
	</p>
	
	<p>
		Tags
	</p>
	
	<ul class="post-tag-list">
		<li>HeidiSQL</li>
		<li>SSH</li>
		<li>Tunnel</li>
		<li>MySQL</li>
	</ul>
	
</div>