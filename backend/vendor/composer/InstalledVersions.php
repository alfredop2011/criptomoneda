<?php











namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => '1.0.0+no-version-set',
    'version' => '1.0.0.0',
    'aliases' => 
    array (
    ),
    'reference' => NULL,
    'name' => 'idavinci/ccanalyzer',
  ),
  'versions' => 
  array (
    'graham-campbell/result-type' => 
    array (
      'pretty_version' => '1.0.x-dev',
      'version' => '1.0.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => '74acfe25f0019fb8af8b5b1eda0681e8e253fee2',
    ),
    'idavinci/ccanalyzer' => 
    array (
      'pretty_version' => '1.0.0+no-version-set',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'phpoption/phpoption' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '1.7.x-dev',
      ),
      'reference' => '994ecccd8f3283ecf5ac33254543eb0ac946d525',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '1.21.x-dev',
      ),
      'reference' => 'fade6deebd931cfd7a544f68479405a6a08979a3',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '1.21.x-dev',
      ),
      'reference' => '401c9d9d3400c53a8f1a39425f0543406c137a43',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'dev-main',
      'version' => 'dev-main',
      'aliases' => 
      array (
        0 => '1.21.x-dev',
      ),
      'reference' => '3a11f3dfb34ad50f978cb2b8cf936933b87739aa',
    ),
    'vlucas/phpdotenv' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '5.2.x-dev',
      ),
      'reference' => 'a9d2cd2fa893e90dc6ab6545255712a46d0a57fc',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
