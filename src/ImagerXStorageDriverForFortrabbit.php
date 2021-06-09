<?php

/**
 * Imager X Storage Driver for FortRabbit plugin for Craft CMS 3.x
 *
 * External storage driver for Imager X that integrates with fortrabbit's Object Storage
 *
 * @link      https://redeye.dev
 * @copyright Copyright (c) 2021 Red Eye Development
 */

namespace redeye\imagerxstoragedriverforfortrabbit;


use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use redeye\imagerxdospacesdriver\externalstorage\FortrabbitStorage;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    Red Eye Development
 * @package   ImagerXStorageDriverForFortrabbit
 * @since     1.0.0
 *
 */
class ImagerXStorageDriverForFortrabbit extends Plugin
{
    public static $plugin;
    public $schemaVersion = '1.0.0';
    public $hasCpSettings = false;
    public $hasCpSection = false;

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            \spacecatninja\imagerx\ImagerX::class,
            \spacecatninja\imagerx\ImagerX::EVENT_REGISTER_EXTERNAL_STORAGES,
            static function (\spacecatninja\imagerx\events\RegisterExternalStoragesEvent $event) {
                $event->storages['fortrabbit'] = FortrabbitStorage::class;
            }
        );

        Craft::info(
            Craft::t(
                'imager-x-storage-driver-for-fort-rabbit',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }
}
