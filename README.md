Background
======
cindex is an abbreviation of Clean Index. 

I created cindex because I wanted a list of files/folders in the current directory with some easy custom styles and details about each file 
along with the full URL path for easy access to copy/past bulk URLS.

None of the web servers I had tried provided a good easy way to do this, short of editing their template files for the index page,
which didn't always exist. 
As well as this I wanted to have the same look and features on multiple web servers (`Apache`, `LightTPD`, `nginx` etc)
this proved rather tricky so I decided to go with something each of them provided (if configured), `PHP`.


I also wanted somewhere to try out some of the new `HTML5`/`CSS3` features.

Requirements
======
- PHP 5 +

Features
======
- CSS3 animations (Think I'll get rid of these soon, they are more of a hindrance then a help)
- CSS3 in general (I recommend you use a webkit based browser, but works ok on others)

Install
======
Copy the `cindex.php` and `style` folder to your server where your web server can access it and link to it from within your configuration as the main index file if non found.

Configuration examples
======
**nginx config (`/etc/nginx/nginx.conf`):**

	location / {
		root   /path/www/;
		index  index.html index.htm, index.php, /dir_to_cindex/cindex.php;
		autoindex  off;
	}

**LightTPD config (`/etc/lighttpd/lighttpd.conf`):**

	index-file.names = ( "index.html", "index.php", "/dir_to_cindex/cindex.php" )

Known bugs
=====
Some file/folder names won't be encoded proper.