<p>
	It's the perfect time to think about it.  With
	<a href="http://en.wikipedia.org/wiki/2010_Haiti_earthquake" title="&hellip;and subsequent media frenzy">disaster</a>
	on everybody's mind, and this being the season of resolutions, it's time to
	think about your disaster recovery strategy.  You do have one, right?
</p>

<p>
	Recently, a major department right here at FSU suffered catastrophic loss
	to their servers, with scant means of quick recovery.  Their site was down
	literally for weeks as staff from all over the University scrambled to assist
	them in recreating the critical pieces.  They lost of a lot of time and money.
</p>

<p>
	I’m absolutely sure they’ll be super-prepared next time, but <span style="text-decoration: underline"><em>you</em></span>
	don’t have to wait until something tragic happens to be prepared!
</p>

<h3>Disaster Recovery Enterprise Planning = Big Words (Scary!)</h3>

<p>
	When I was an IT Manager n00b, I thought that planning for disasters was
	intimidating and complicated.  I thought it took a team of folks working
	around the clock, military-style, to prepare for incidents.  I was encouraged
	to believe this by colleagues constantly talking in <a href="http://spedr.com/5rduk" title="Yeah.. stuff like this.">business-speak</a>,
	conference sessions talking about <a href="http://spedr.com/2v7ul" title="Exactly like this.">things like “DR evaluation metrics”</a>,
	and a ton of big ugly books.
</p>

<p>
	In the meantime, while I was being intimidated by all this “enterprise” stuff,
	we went without reliable backups.  We would have been up sh--ahem, in a bit of trouble
	if something happened.
</p>

<p>
	Don't be like me!  Here's what to do:
</p>

<h4>1. Embrace Common Sense</h4>

<p>
		Okay, just ignore all that business-speak.  Let’s start with the basics:
		If you’re not backing up your data, put down the big ugly DR books right
		now and go setup a backup.
</p>

<p>
	No, seriously, do it <span style="text-decoration: underline">right now</span>.
</p>

<p>
	This does not have to be complicated.  Go find an external hard drive,
	then find out where your data is on your computers or servers.  Plug the
	hard-drive into the back of the computers and copy the folders with your data.
	Do this every day, and you're already off to a good start (however, this is
	by no means a permanent solution -- just something to get you started!)
</p>

<blockquote>
	Pro Tip: If you’re using Windows, I’m a big fan of using
	<a href="http://codesector.com/teracopy" title="Teracopy - A worthy tool">Teracopy</a> when I have
	to copy lots of data at a time.
</blockquote>

<p>
	You get bonus if you copy the data to two hard-drives and take one off-site
	every day.  Now your data is in two geographic locations on three devices.
</p>

<p>
	See? Easy.  Now that you’re off and running, you can make your life even
	easier by automating your backups.  If you’re not excited about doing a bunch
	of geeky tech stuff to get your automated backups working, go convince your
	boss to spend a few bucks a month on a <a href="http://mozy.com" title="Mozy">cheap</a>,
	<a href="http://www.idrive.com/" title="iDrive">easy-to-setup</a> <a href="http://box.net" title="Another good one">online backup</a> <a href="http://dropbox.com" title="Dropbox Pro!">service</a>.
</p>

<p>
	If he or she doesn’t want to spend the money, you can <del>scare</del> enlighten
	him with
	<a href="http://www.codinghorror.com/blog/2009/12/international-backup-awareness-day.html" title="Jeff Atwood Knows...">many</a>
	<a href="http://blog.jquery.com/2011/12/08/what-is-happening-to-the-jquery-plugins-site/" title="Jquery Knows...">horrifying</a>
	<a href="http://www.usatoday.com/tech/news/computersecurity/2006-06-11-lost-data_x.htm" title="Yep.  Here's an old article">stories</a>
	<a href="#">of</a>
	<a href="#">data</a>
	<a href="#">loss</a>
	on the Internet.
</p>

<h4>2. Be like a Boy Scout; Be Prepared</h4>

<p>
	Next Step: Just sit down in a quiet place for a few minutes and think about
	the things that could go wrong:
</p>

<ol>
	<li>“My server could suffer a catastrophic hardware failure”</li>
	<li>“My backup data could be corrupt”</li>
	<li>“The building could burn down”</li>
	<li>“We could get robbed”</li>
	<li>“An employee could go apeshit and format the C: drive of the server”</li>
	<li>"The servers could get flooded"</li>
	<li><em>keep going&hellip;</em></li>
</ol>

<p>
	If you write all of this stuff down somewhere and tell your boss about it,
	you have a basic DR document!  Go have a beer.
</p>

<h4>3. Build your Kit</h4>

<p>
	Now that you are creating backups and thinking about our risks/responses,
	it’s time to build your <strong>Disaster Recovery Kit</strong>! Put this kit
	somewhere out of harm’s way, and have it handy in case you need it.
	Here’s mine:
</p>

<ol>
	<li><strong>A camcorder bag</strong> — I like this, because it’s easy to carry and has a lot of compartments for all the stuff below:</li>
	<li><strong>Operating System Boot discs</strong> – In my case, the latest Ubuntu LTS Server</li>
	<li><strong>GParted Boot disc</strong> — For formatting and partitioning systems on-the-fly.</li>
	<li><strong>Cables</strong> – Standard power cable and USB cable.</li>
	<li><strong>Backup hard drives</strong> — Two.  They are my offsite copies of all our data.  I rotate them and keep them encrypted.</li>
	<li><strong>Flash light</strong> – Small and compact, for poking around inside computers or navigating server rooms in the dark</li>
	<li><strong>Thumb drive</strong> — Includes a backup of my server setup procedures/changelogs, systems passwords (in KeePass), and a bunch of portable apps.  Updated weekly.</li>
	<li><strong>Printed copy of contact information</strong> for key stakeholders — My IT colleagues, clients, and my boss, so I can keep them updated.</li>
	<li><strong>Geek tool kit</strong> –  Includes screwdrivers, etc.</li>
	<li><strong>External SATA Hard Disk Reader</strong> – Something small and compact.  In case I need to rip a hard drive from a computer and get the data off quickly.</li>
	<li><strong>A big sheet of paper</strong> that says "Don’t Panic"</li>
	<li><strong>Music CD</strong> with high-energy power music on it.</li>
	<li><strong>Energy supplement</strong> — Or a flask of whiskey, depending on how you roll.</li>
	<li><strong>"Getting started" guide</strong> – A piece of paper, right on top, that explains briefly how to use the kit.</li>
	<li><strong>Checklist</strong> — With all of these items on it, and with weekly DR Kit maintenance procedures.</li>
</ol>

<h4>4. Know What's Important</h4>

<p>
	Now that you have a few things in-place, you can start to think about things
	a bit more strategically.  What's most important to your organization?  How
	long can you be without your precious computers?  If extra-terrestrials from
	the planet Xorgon blasted your server room with their photon torpedoes, what
	steps would you take to recover?
</p>

<p>
	Disaster Recovery is just a means to keeping what's important in your organization
	up and running.  It doesn't have to be any more complicated than you make it.  In fact,
	as with any system the simpler, the better.
</p>

<p>
	There, now I can sleep at night much better.  How about you?
</p>