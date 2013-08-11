# DISCONTINUED

This project is no longer maintained by me. 
If you want to take over, please get in touch with me at inoryy@gmail.com

Mupowatch.eu
=================

Setup
----

After pulling down the project, first copy app/config/parameters.ini.dist to app/config/parameters.ini
and customize as necessary:

    cp app/config/parameters.ini.dist app/config/parameters.ini

Next, to download the vendor libraries, run the bin/vendor script:

    php bin/vendor install

Now, setup the permissions for your cache and log directories, however is appropriate for your
machine. See http://symfony.com/doc/current/book/installation.html#configuration-and-setup
