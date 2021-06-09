<?php

/**
 * Imager X Storage Driver for FortRabbit plugin for Craft CMS 3.x
 *
 * External storage driver for Imager X that integrates with fortrabbit's Object Storage
 *
 * @link      https://redeye.dev
 * @copyright Copyright (c) 2021 Red Eye Development
 */

namespace redeye\imagerxstoragedriverforfortrabbit\externalstorage;

use Craft;
use craft\helpers\FileHelper;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

use spacecatninja\imagerx\models\ConfigModel;
use spacecatninja\imagerx\services\ImagerService;
use spacecatninja\imagerx\externalstorage\ImagerStorageInterface;

class FortrabbitStorage implements ImagerStorageInterface
{
  /**
   * @param string $file
   * @param string $uri
   * @param bool $isFinal
   * @param array $settings
   * @return bool
   */
  public static function upload(string $file, string $uri, bool $isFinal, array $settings)
  {
    /** @var ConfigModel $settings */
    $config = ImagerService::getConfig();

    $clientConfig = [
      'version' => 'latest',
      'region' => Craft::parseEnv($settings['region']),
      'endpoint' => Craft::parseEnv($settings['endpoint']),
      'credentials' => [
        'key' => Craft::parseEnv($settings['accessKey']),
        'secret' => Craft::parseEnv($settings['secretAccessKey']),
      ],
    ];

    try {
      $s3 = new S3Client($clientConfig);
    } catch (\InvalidArgumentException $e) {
      Craft::error('Invalid configuration of S3 Client: ' . $e->getMessage(), __METHOD__);
      return false;
    }

    if (isset($settings['folder']) && $settings['folder'] !== '') {
      $uri = ltrim(FileHelper::normalizePath(Craft::parseEnv($settings['folder']) . '/' . $uri), '/');
    }

    // Always use forward slashes for S3
    $uri = str_replace('\\', '/', $uri);

    $opts = $settings['requestHeaders'];
    $cacheDuration = $isFinal ? $config->cacheDurationExternalStorage : $config->cacheDurationNonOptimized;

    if (!isset($opts['Cache-Control'])) {
      $opts['CacheControl'] = 'max-age=' . $cacheDuration . ', must-revalidate';
    }

    $opts = array_merge($opts, [
      'Bucket' => Craft::parseEnv($settings['bucket']),
      'Key' => $uri,
      'Body' => fopen($file, 'r'),
      'ACL' => 'public-read',
    ]);

    try {
      $s3->putObject($opts);
    } catch (S3Exception $e) {
      Craft::error('An error occured while uploading to fortrabbit Object Storage: ' . $e->getMessage(), __METHOD__);
      return false;
    }

    return true;
  }
}
