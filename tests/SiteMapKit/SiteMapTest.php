<?php

class SiteMapTest extends PHPUnit_Framework_TestCase
{
    public function testSiteMap()
    {
        $sitemap = new SiteMapKit\SiteMap;
        ok($sitemap);

        $sitemap->useImage();
        $sitemap->useVideo();
        $sitemap->useMobile();
        $sitemap->useGeo();
        $sitemap->useNews();
        $sitemap->useAll();

        $url = $sitemap->addUrl('http://phifty.corneltek.com');
        $url->changefreq('yearly')
            ->changeYearly()
            ->lastmod( new DateTime )
            ->priority(1.0)
            ->mobile(1.0);
        ok($url);

        $url->addImage('http://example.com/image.jpg')
            ->caption('Example')
            ->geoLocation('Taiwan,Taipei')
            ->license('http://opensource.org/licenses/mit-license.php')
            ;
        $url->addImage('http://example.com/image2.jpg');

        ok($sitemap->__toString());
        // echo $sitemap->__toString();
        select_ok('urlset url',1,$sitemap->dom);
        select_ok('urlset url loc',1,$sitemap->dom);
        select_ok('urlset url changefreq',1,$sitemap->dom);
        select_ok('urlset url mobile:mobile',1,$sitemap->dom);
        select_ok('image:image',2,$sitemap->dom);
        select_ok('image:image image:loc',2,$sitemap->dom);
        select_ok('image:image image:geo_location',1,$sitemap->dom);
    }
}

