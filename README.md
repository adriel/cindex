Background
======
cindex is an abbreviation of Clean Index. 

I created cindex because I wanted a list of files/folders in the current directory with some easy custom styles and details about each file 
along with the full URL path for easy access to copy/past bulk URLs.

None of the web servers I have tried provided a good easy way to do this, short of editing their template files for the index page,
which didn't always exist. 
As well as this I wanted to have the same look and features on multiple web servers (`Apache`, `LightTPD`, `nginx` etc)
this proved rather tricky so I decided to go with something each of them provided (if configured), `PHP`.


Requirements
======
- PHP 5+ (Tested on PHP 5.5.34)

Features
======
- Supports both HTTP and HTTPS
- Shows new files added within the last 12 hours

Install
======
Copy the `index.php` and `style` folder to your server where your web server can access it and link to it from within your configuration as the main index file if non found.

Configuration examples
======
**nginx config (`/etc/nginx/nginx.conf`):** [nginx index module](http://wiki.nginx.org/HttpIndexModule) 

	index index.html index.htm index.php /dir_to_cindex/index.php;

**LightTPD config (`/etc/lighttpd/lighttpd.conf`):**

	index-file.names = ( "index.html", "index.php", "/dir_to_cindex/index.php" )

Known bugs
=====
- None, please report any you find.