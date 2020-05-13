<?php
define('RSS_URLS', [
    'https://dumskaya.net/rssnews/' => 'getDumskayaDescription',
    'https://www.obozrevatel.com/rss.xml' => 'getDescription',
]);
function loadRss($url)
{
    $rssFile = 'tmp/' . parse_url($url, PHP_URL_HOST) . '.xml';
    //$rssFile = 'tmp/rss.xml';

    if (!file_exists($rssFile)) {
        $page = file_get_contents($url);
        file_put_contents($rssFile, $page);
    }
    return simplexml_load_file($rssFile);
}

function loadAll()
{
    $result = [];
    $total = 0;
    $limit = INF;
    if (!empty($_GET['limit'])) {
        $limit = floor($_GET['limit'] / count(RSS_URLS));

    }
    foreach (RSS_URLS as $url => $function) {
        $xml = loadRss($url);
        $items = $xml->channel->item;
        $key = 0;
        foreach ($items as $item) {
            if (++$key > $limit) {
                break;
            }
            $item->description = $function($item);
            $result[] = $item;
        }
    }
    shuffle($result);
    return $result;
}

function getDumskayaDescription(object $item, $limit = 2000): string
{
    $url = $item->link;
    $content = file_get_contents($url);
    $content = mb_convert_encoding($content, "UTF-8", "Windows-1251");
    $pos = mb_strpos($content, '<td class=newscol');
    $description = mb_substr($content, $pos);
    $description = strip_tags($description);

    $description = html_entity_decode($description);
    $description = preg_replace('/\s{2,}/', ' ', $description);
    if (!empty($_GET['word']) && stristr($description, $_GET['word']) == TRUE ){
        $word = $_GET['word'];
        $description =str_ireplace($word, "<span style =\"background-color:yellow;\">$word</span>", $description);
    }

    return mb_substr($description, 0, $limit);
}


function getDescription(object $item)
{
    return $item->description;
}
//function searchWord ($items)
//{
//if (!empty($_GET['word'])){
//    $word = $_GET['word'];
//    $str = str_replace($word, "<span style =\"color:yellow;\">$word</span>", $items);
//}
//return $str;
//}