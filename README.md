ClarifaiBundle
==============

This bundle provides integration with [Clarifai](https://www.clarifai.com) with Symfony2.

Installation
------------

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

    $ composer require daviddlv/clarifai-bundle

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the ``app/AppKernel.php`` file of your project:

``` php
    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...
                new ClarifaiBundle\ClarifaiBundle(),
            );

            // ...
        }

        // ...
    }
```
### Configuration

``` yaml
# app/config/config.yml

clarifai:
    auth:
        client_id: %client_id%
        client_secret: %client_secret%
```
## Basic Usage

``` php
    $client = $this->conrainer->get('clarifai.api.client');
    
    TODO
```