Networking init CMS sandbox based on the networking init CMS and the Symfony Standard Edition
=============================================================================================

[![Build Status](https://travis-ci.org/networking/init-cms-sandbox.png?branch=master)](https://travis-ci.org/networking/init-cms-sandbox)



Welcome to the init CMS Sandbox. This will get you started with a working CMS based
on the [InitCmsBundle](https://github.com/networking/init-cms-bundle) and Symfony 2.

The InitCmsBundle is a small flexible cms core based on symfony 2 which can be used as a standalone CMS or integrated into
any existing symfony 2 project.

The main features are:
- Page manager with draft and published states, as well as public or protected (login only) pages, and customised URLs
- Menu manager to organise multiple menu bars
- Media manager and gallery manager
- User manager with ACL access control
- Help page manager

Other Features:
- Integrate your own twig templates
- Create your own content types
- Based on the SonataAdminBundle so you can easily create your own admin modules


Find more information about the init CMS on [www.initcms.com](http://www.initcms.com).

A demo of the CMS can be found at demo.initcms.com The Demo is reset every 24 hours

A demo of the CMS can be found at [demo.initcms.com](http://demo.initcms.com). The Demo is reset every 24 hours.

The installation of the sandbox is pretty much the same as a normal installation of Symfony project.

This document contains information on how to download, install, and start
using the networking init CMS sandbox. For a more detailed explanation on install Symfony, see the [Installation][17]
chapter of the Symfony Documentation.

The project is being developed by the
small hard working team at [net working AG][1] in Zürich.

1) Installing the networking init CMS sandbox
---------------------------------------------

For the moment you will need to download an archive, then run composer to install the
dependencies. We will soon have the project on packagist, after that you will be able to use
the create-project command.

### Download an Archive File

First [download][2] and unpack the archive of the sandbox in your preferred location

	https://github.com/networking/init-cms-sandbox/archive/master.zip

Or use composer to create the project
    
    composer create-project	networking/init-cms-sandbox project_folder 3.4.*

Then change into the project directory

	cd path/to/install

Create a parameters.yml file:

	cp app/config/parameters.yml.dist app/config/parameters.yml

### Use Composer (*recommended*)

As Symfony uses [Composer][3] to manage its dependencies, which is why we also use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Composer will install the networking init CMS and all its dependencies under the
`path/to/install` directory.

Now you will need to install the dependencies, the following command will fill the vendors
folder with all the working guts in accordance with the versions defined in the composer.lock
file:

    php composer.phar install


Now we just need to create some folders for our media in the web root directory and make it RW+

	mkdir web/uploads web/uploads/media
	chmod -R 777 web/uploads

Make cache and logs writeable

	chmod -R 777 var/cache var/logs


2) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the `symfony_requirements` script from the command line:

    php bin/symfony_requirements

Access the `config.php` script from a browser:

    http://localhost/config.php

If you get any warnings or recommendations, fix them before moving on.

If all is good, you can move on to configuring the DB set up by clicking the
"Configure your Symfony Application online" link, or by editing the paramters.yml
file directly


3) Run the networking init CMS installation
-------------------------------------------

Now that the symfony application is more or less setup, it is time to load the CMS DBs and
fixtures, as well as create an admin user.

You can run the install process on the command line,
you will be prompted to enter a username, email address and password, these will get you into the backend.

	php bin/console networking:initcms:install
	
	
Alternatively there is an install wizard which will get this done for you, just go to the following URL and follow the instructions:

    http://localhost/app_dev.php/cms_install
    
Now you should be up and running.


The installer also executes assetic, which gets your assetic assets organised by doing an assetic dump (we use less so please check you have it setup already)

    bin/console assetic:dump
    
Maybe you have to install less, if you do not have it already. On OS X get homebrew, get node, get less

    brew install npm
    sudo npm install less --global
	


4) Login to the admin area
--------------------------

It should now be possible to login to the backend admin interface of the project. Just
navigate to:

	http://localhost/app_dev.php/admin

Enter your username and password as entered in step 3 and you should be directed to the
admin dashboard.


Further documentation about the initcms
---------------------------------------

You can find more information about configuring and extend the initcms online, just follow the links

- [InitCmsBundle installation](https://github.com/networking/init-cms-bundle/blob/master/Resources/doc/installation.md)
- [Configuring your cms](https://github.com/networking/init-cms-bundle/blob/master/Resources/doc/configuration.md)
- [Creating templates](https://github.com/networking/init-cms-bundle/blob/master/Resources/doc/templates.md)
- [Creating custom content types](https://github.com/networking/init-cms-bundle/blob/master/Resources/doc/content_types.md)
- [Creating an Admin user interface](https://github.com/networking/init-cms-bundle/blob/master/Resources/doc/admin_ui.md)
- [Creating custom admin settings](https://github.com/networking/init-cms-bundle/blob/master/Resources/doc/custom_admin_settings.md)


What's inside?
---------------

The networking init CMS is based on a Symfony Standard Edition base plus a bit more

  * The sonata-admin bundle is the basis for the admin area

  * The routing of dynamic content is based on the Symfony CMF dynamic routing component

  * The Mopa bootstrap bundle for some twitter bootstrap goodness in the front end.

  * Twig is the only configured template engine;

  * Doctrine ORM/DBAL is configured;

  * Swiftmailer is configured;

  * Annotations for everything are enabled.

It comes pre-configured with the following bundles:

  * [**SonataAdminBundle**][4] The missing Symfony2 Admin Generator

  * [**SymfonyCmfRoutingExtraBundle**][5]  Symfony CMF Routing Extra Bundle
        capabilities

  * [**MopaBootstrapBundle**][6] MopaBootstrapBundle is a collection of code to
    integrate twitter's bootstrap into your symfony2 project


  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][7] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][8] - Adds support for the Doctrine ORM

  * [**TwigBundle**][9] - Adds support for the Twig templating engine

  * [**SecurityBundle**][10] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][11] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][12] - Adds support for Monolog, a logging library

  * [**AsseticBundle**][13] - Adds support for Assetic, an asset processing
    library

  * [**JMSSecurityExtraBundle**][14] - Allows security to be added via
    annotations

  * [**JMSDiExtraBundle**][15] - Adds more powerful dependency injection
    features

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][16] (in dev/test env) - Adds code generation
    capabilities

  * [**IbrowsSonataTranslationBundle**][18] - Adds a DB based UI for working with translations,
    integrated with in a SonataAdmin setup.

  * [**IbrowsSonataAdminAnnotationBundle**][19] - Adds the ability to defined form fields via annotations
    to be used in conjuction with the SonataAdminBundle


[1]:  http://web.networking.ch
[2]:  https://github.com/networking/init-cms-sandbox/archive/master.zip
[3]:  http://getcomposer.org/
[4]:  http://sonata-project.org/bundles/admin
[5]:  http://symfony.com/doc/master/cmf/bundles/routing-extra.html
[6]:  http://symfony.com/doc/master/cmf/bundles/routing-extra.html
[7]:  http://symfony.com/doc/2.5/bundles/SensioFrameworkExtraBundle/index.html
[8]:  http://symfony.com/doc/2.5/book/doctrine.html
[9]:  http://symfony.com/doc/2.5/book/templating.html
[10]:  http://symfony.com/doc/2.5/book/security.html
[11]: http://symfony.com/doc/2.5/cookbook/email.html
[12]: http://symfony.com/doc/2.5/cookbook/logging/monolog.html
[13]: http://symfony.com/doc/2.5/cookbook/assetic/asset_management.html
[14]: http://jmsyst.com/bundles/JMSSecurityExtraBundle/1.1
[15]: http://jmsyst.com/bundles/JMSDiExtraBundle/1.0
[16]: http://symfony.com/doc/2.5/bundles/SensioGeneratorBundle/index.html
[17]: https://github.com/symfony/symfony#installation
[18]: https://github.com/ibrows/IbrowsSonataTranslationBundle
[19]: https://github.com/ibrows/IbrowsSonataAdminAnnotationBundle
