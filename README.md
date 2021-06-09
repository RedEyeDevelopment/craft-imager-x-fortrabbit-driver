# Imager X Storage Driver for FortRabbit plugin for Craft CMS 3.x

External storage driver for Imager X that integrates with fortrabbit's Object Storage

<!-- ![Screenshot](resources/img/plugin-logo.png) -->

## Requirements

This plugin requires Craft CMS 3.3.0 or later, and Imager X 3.0 or later. External storages are only available in the Pro edition of Imager. 

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require RedEyeDevelopment/imager-x-storage-driver-for-fort-rabbit

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Imager X Storage Driver for FortRabbit.

## Configuration

Configure the storage driver by adding new key named `fortrabbit` to the `storagesConfig` config setting in your **imager-x.php config file**, with the following configuration:

    'storageConfig' => [
        'fortrabbit' => [
            'endpoint' => '',
            'accessKey' => '',
            'secretAccessKey' => '',
            'region' => '',
            'bucket' => '',
            'folder' => '',
            'requestHeaders' => array(),
        ]
    ],

Enable the storage driver by adding the key `fortrabbit` to Imager's `storages` config setting:

    'storages' => ['fortrabbit'],

Here's an example config, note that the endpoint has to be a complete URL with scheme, and as always you need to make sure that `imagerUrl` is pointed to the right location:

    'imagerUrl' => 'https://SUBDOMAIN.objects.frb.io/transforms/',
    'storages' => ['fortrabbit'],
    'storageConfig' => [
        'fortrabbit' => [
            'endpoint' => 'https://objects.us1.frbit.com',
            'accessKey' => 'MYACCESSKEY',
            'secretAccessKey' => 'MYSECRETKEY',
            'region' => 'us-east-1',
            'bucket' => 'MYBUCKET',
            'folder' => 'transforms',
            'requestHeaders' => array(),
        ]
    ],
    
Also remember to always empty your Imager transforms cache when adding or removing external storages, as the transforms won't be uploaded if the transform already exists in the cache.
 
Price, license and support
---
The plugin is released under the MIT license. It requires Imager X Pro, which is a commercial plugin [available in the Craft plugin store](https://plugins.craftcms.com/imager-x). 


Brought to you by [Red Eye Development](https://redeye.dev)
