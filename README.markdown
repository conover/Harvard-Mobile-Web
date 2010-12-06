# Requirements

* Apache server
* PHP 5.2 or greater
* Separate server running MIT browser detection

# Apache Sample Configuration

	<VirtualHost *:80>
		ServerName mobile.localdomain
		DocumentRoot /Users/jlang/Sites/php/Harvard-Mobile-Web/future/web
		
		<Directory /Users/jlang/Sites/php/Harvard-Mobile-Web/future/web>
		  Options Indexes FollowSymLinks MultiViews
		  Order allow,deny
		  Allow from all
		</Directory>
		
	</VirtualHost>

# Application Configuration

Application wide configuration files:

	/future/config/config.ini                                # Required, copy from template

Theme configuration files:

	/future/site/<ACTIVE_SITE>/config/config.ini             # Always exists
	/future/site/<ACTIVE_SITE>/config/config-<SITE_MODE>.ini # Use to override settings in ./config.ini

The variables ACTIVE\_SITE and SITE\_MODE are set in the Application wide files.

Using SITE\_MODE allows you to create configurations for each stage of
deployment, such as development, stage, or production.  The settings in these
SITE\_MODE specific ini files will override those set in the base config.ini.

So to create a specific configuration for production you would need to set
SITE\_MODE in Application config to 'production' and create a config
file in the Theme config directory called 'config-production.ini'.

