# Firecamp php

Small and simple php classes for php project based on silex, to make easier the start of a project.

> DISCLAIMER : at this moment these classes are just a test, consider this project as a pre-alpha, don't use it on production.

## What's inside ?

## Providers

Two providers are available :
 
**Firecamp\Provider\ControllerServiceProvider**

This class register classes in the share of the silex application

**Firecamp\Provider\RepositoryServiceProvider**

This class register repositories class in the share of the silex application, it injects the $app['db'] to each class.

### Utils

The Firecamp\Utils class has two static methods :

**Firecamp\Utils::loadConf($configFile);**

This method load a php file and merge the configuration entries in the app.

**Firecamp\Utils::appShareClassHelper(...);**

This method help to share a class in silex.
With this you can easily for example declare your custom controller in a particular place and use them easily.

## Base classes

**Firecamp\Controller\Controller**

Base class to extend with some predefined helpers

**Firecamp\Repository\AbstractRepository**

Base class to extend with some predefined helpers

## Contributing

You can test that your code looks ok by launching the command

    vendor/bin/coke
    
From the root of the project, it will scan the code in *src* directory with phpcs and symfony 2 coding standard settings

---

## TODO

- Write some tests
- Write more documentation
- Add travis ci
