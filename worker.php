<?php

#if ( filemtime(__DIR__.'/sitemap.xml') > time() - 60*5 ) { echo 'Computer says no.'; exit; }



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



/**
 * Inject Ads if not existing
 */

// Google Ads
$google_ads_start = '<!-- |inject_ads -->';
$google_ads_end = '<!-- inject_ads| -->';
$google_ads = <<<HTML
<script data-ad-client="ca-pub-3809977409157715" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
HTML;

// Google Analytics
$google_analytics_start = '<!-- |inject_analytics -->';
$google_analytics_end = '<!-- inject_analytics| -->';
$google_analytics = <<<HTML
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-164640273-2"></script>
<script>
document.addEventListener('DOMContentLoaded', function(event) {
    if ( location.host == 'tools.bitfertig.de' ) {
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-164640273-2', { 'anonymize_ip': true });
    }
});
</script>
HTML;

foreach ($tools as $tool) {
    $tool = (object) $tool;
    // Google Ads
    if ( $tool->inject_ads ) {
        foreach ($tool->inject_ads->files as $file) {
            $injectfile = __DIR__.'/'.$tool->path.$file;
            $content = file_get_contents($injectfile);
            if ( stristr($content, $google_ads_start) && stristr($content, $google_ads_end) ) {
                $content = preg_replace('/'.preg_quote($google_ads_start).'.*?'.preg_quote($google_ads_end).'/m', $google_ads_start.$google_ads.$google_ads_end, $content);
            } else {
                $content = str_replace('</body>', $google_ads_start.$google_ads.$google_ads_end.PHP_EOL.'</body>', $content);
            }
            file_put_contents($injectfile, $content);
        }
    }
    // Google Analytics
    if ( $tool->inject_analytics ) {
        foreach ($tool->inject_analytics->files as $file) {
            $injectfile = __DIR__.'/'.$tool->path.$file;
            $content = file_get_contents($injectfile);
            if ( stristr($content, $google_analytics_start) && stristr($content, $google_analytics_end) ) {
                $content = preg_replace('/'.preg_quote($google_analytics_start).'.*?'.preg_quote($google_analytics_end).'/m', $google_analytics_start.$google_analytics.$google_analytics_end, $content);
            } else {
                $content = str_replace('</body>', $google_analytics_start.$google_analytics.$google_analytics_end.PHP_EOL.'</body>', $content);
            }
            file_put_contents($injectfile, $content);
        }
    }
}







echo 'Done.';