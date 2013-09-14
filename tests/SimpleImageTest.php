<?php

class SimpleImageTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        require "libraries/simpleimage/init.php";
        $s =  new SimpleImage;
        $s->load("tests/data/goraster.png");
        ok($s);

        $s->cropOuterAndScale(200,200);

        $newpath = "tests/data/goraster-crop.png";
        $s->save($newpath);
        path_ok($newpath);
        system("open $newpath");
    }
}

