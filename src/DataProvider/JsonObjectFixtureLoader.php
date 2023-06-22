<?php

/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 01/07/20
 * Time: 12:04
 */

namespace App\DataProvider;

use Exception;
use App\DataProvider\FixtureNotFoundException;

trait JsonObjectFixtureLoader
{
    private static function loadJsonFile($fileName)
    {
        $filePath = realpath('.') . self::BASE_FIXTURES_PATH . $fileName;
        if (file_exists($filePath) === false) {
            throw new FixtureNotFoundException('File ' . $fileName . ' not found.');
        }

        $fixture = file_get_contents($filePath);

        $fixturesObj = json_decode($fixture);
        if ($fixturesObj === null) {
            throw new Exception(
                __METHOD__ . " : Err for file " . $fileName . " json msg " . json_last_error_msg()
            );
        }

        return $fixturesObj;
    }

    private static function loadJsonString($fileName)
    {
        $filePath = realpath('.') . self::BASE_FIXTURES_PATH . $fileName;
        if (file_exists($filePath) === false) {
            return '';
        }

        return file_get_contents($filePath);
    }
}
