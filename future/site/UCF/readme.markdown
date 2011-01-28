# UCF Mobile

It's a mobile site

# Development (from scratch)

I prefer developing on Mac but this tutorial is written with Windows 7 in mind.

## technologies used
* apache & php
* git 
* github
* Modo Labs framework from the [iMobileU community](http://imobileu.org/)

## local dev environment

### Apache/MySQL/PHP

If only needed for development and not production, the easiest way to get all three is [WAMP](http://www.wampserver.com/en/)

[Download](http://www.wampserver.com/en/download.php) and install WampServer

Once finished, you should see "W" (wampserver) in your systray

Open a browser and you should be able to go to: [http://localhost/](http://localhost/) and see "WampServer" with a list of the config and tools, etc

### set up a local site

Note, the folders and naming convention is simply my personal preference.

Create a "Sites" folder for all local dev:

* Create directory: `C:\Sites\mobile`
* In the mobile dir, create `index.php` and in this file write `Hello World!`

I like to use http://mobile.dev/ for local development.  Add mobile.dev to your hosts file:

* in `C:\Windows\System32\drivers\etc` edit the `hosts` file
* add the line
        127.0.0.1   mobile.dev

Create an apache vhost for mobile.dev:

* depending on where you installed wamp, go to `C:\wamp\bin\apache\Apache2.2.17\conf`
* edit `httpd.conf`, find:
        # Virtual hosts
        #Include conf/extra/httpd-vhosts.conf
    and uncomment second line:
        # Virtual hosts
        Include conf/extra/httpd-vhosts.conf
* now edit this file, open `conf/extra/httpd-vhosts.conf`
* add:
        # copied from httpd.conf for "c:/wamp/www/"
        <Directory "C:/Sites/">
            Options Indexes FollowSymLinks
            AllowOverride all
            Order Deny,Allow
            Deny from all
            Allow from 127.0.0.1
        </Directory>
        
        # UCF Mobile
        <VirtualHost *:80>
            ServerName mobile.dev
            DocumentRoot "C:/Sites/mobile"
        </VirtualHost>
        
        # WAMP start page
        <VirtualHost *:80>
            ServerName localhost
            DocumentRoot "C:/wamp/www"
        </VirtualHost>

### Back to Wamp
Will later need two extensions/modules:

* SSL: "W" (systray) > PHP > PHPextensions > PHP_openssl
* Rewrite: W > Apache > Apache Modules > rewite_module
* W > Restart All Services
* Open browser, go to [http://mobile.dev/](http://mobile.dev/)

### Works?
Hopefully you now see `Hello World!`


## Git and Github

To install git and add your SSH key to Github, follow [these instructions](http://help.github.com/)

Fork the [https://github.com/UCF/Harvard-Mobile-Web](mobile web project)


Delete the mobile directory and grab a copy of the code:

Using the git bash (note, you can right-click top of window to use edit>paste):
    cd \c\Sites
    rm -rf mobile
    git clone git@github.com:USERNAME/Harvard-Mobile-Web.git mobile
    
using cmd:
    cd C:\Sites
    del mobile
    git clone git@github.com:USERNAME/Harvard-Mobile-Web.git mobile

### update our vhost
edit `C:\wamp\bin\apache\Apache2.2.17\conf\extra\httpd-vhosts.conf`

for mobile.dev the vhost documentroot needs to be updated:
    # UCF Mobile
    <VirtualHost *:80>
        ServerName mobile.dev
        DocumentRoot "C:/Sites/mobile/future/web"
    </VirtualHost>

**restart apache/wamp**

View [http://mobile.dev](http://mobile.dev) in a browser, should see an error:
> Missing config file....

So far so good!

Need to create two config files (this process is being fixed/updated as we speak):

* in `mobile/future/config` duplicate the template and save as `config.ini`
* in `mobile/future/site/UCF/config` create blank file `config-development.ini`


Refresh browser, hopefully all good!  

The one snag I ran into is forgetting to turn on the php SSL extension and the Apache rewrite module.

## What's next?

Start developing!

Read through modo's [theming documentation](https://github.com/modolabs/Harvard-Mobile-Web/blob/master/future/doc/Theming.txt)

Once there's code ready to be push upstream, that's when you'll send us a "pull request" or add you to the UCF org on github.