<?php

if ( filemtime(__DIR__.'/sitemap.xml') > time() - 60*5 ) { echo 'Computer says no.'; exit; }



/**
* Returns the last modified time of the directory
* @param string $directory
* @return int
*/
function dirmtime($directory) {
    $last_modified_time = 0;
    $handler = opendir($directory);
    while ($file = readdir($handler)) {
        if(is_file($directory.DIRECTORY_SEPARATOR.$file)){
            $files[] = $directory.DIRECTORY_SEPARATOR.$file;
            $filemtime = filemtime($directory.DIRECTORY_SEPARATOR.$file);
            if($filemtime>$last_modified_time) {
                $last_modified_time = $filemtime;
            }
        }
    }
    closedir($handler);
    return $last_modified_time;
}

// GTag
$gtag = <<<HTML
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-164640273-2"></script>
<script>
document.addEventListener('DOMContentLoaded', function(event) {
    if ( location.host == 'tools.bitfertig.de' ) {
        // <base href="http://tools.bitfertig.de/webpack-configurator/">
        /* var base = document.createElement('base');
        base.href = 'http://tools.bitfertig.de/webpack-configurator/';
        document.querySelector('head').appendChild(base); */
        // Google Tag Manager
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-164640273-2', { 'anonymize_ip': true });
    }
});
</script>
HTML;

$file_content = file_get_contents(__DIR__.'/tools.json');
$tools = json_decode($file_content);

#echo '<pre>' . var_export($json, true) . '</pre>';


// Create Sitemap
$items = [];
$lastmod_toolsjson = filemtime(__DIR__.'/tools.json');
$lastmod_indexphp = filemtime(__DIR__.'/index.php');
$lastmod = $lastmod_toolsjson > $lastmod_indexphp ? $lastmod_toolsjson : $lastmod_indexphp;
$items[] = '<url><loc>http://tools.bitfertig.de/</loc><lastmod>'. date('Y-m-d', $lastmod) .'</lastmod></url>'.PHP_EOL;
foreach ($tools as $tool) {
    $tool = (object) $tool;
    if ( $tool->sitemap && file_exists(__DIR__.'/'.$tool->path) ) {
        $lastmod = date('Y-m-d', dirmtime(__DIR__.'/'.$tool->path));
        $items[] = '<url><loc>'. $tool->domain . $tool->path .'</loc><lastmod>'. $lastmod .'</lastmod></url>'.PHP_EOL;
    }
}
$sitemap = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'.PHP_EOL;
$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
$sitemap .= implode('', $items);
$sitemap .= '</urlset>';

file_put_contents(__DIR__.'/sitemap.xml', $sitemap);

echo 'Done.';



// TODO: Inject Ads if not existing

