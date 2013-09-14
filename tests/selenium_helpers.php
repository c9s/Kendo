<?php
/*
 * This file is part of the Corneltek package.
 *
 * @category   Corneltek
 * @copyright  Copyright (c) 2012 Corneltek Inc. (http://corneltek.com)
 * @author     EragonJ <eragonj@eragonj.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * This helper is mostly used by other helpers to pass PHPUnit_Extensions_Selenium2TestCase object
 * that we can use some related Selenium method with.
 *
 * @return PHPUnit_Extensions_Selenium2TestCase|null
 */
function get_test_obj()
{
    $objs = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT );

    foreach ($objs as $o) {
        if ( array_key_exists('class', $o) && $o['class'] === 'PHPUnit_Extensions_Selenium2TestCase') {
            return $o['object'];
        }
    }

    return NULL;
}

/**
 * Get the correct element and retrive wanted info from that
 *
 * @param string $sel You can pass CSS Selector or XPath Selector here
 * @return PHPUnit_Extensions_Selenium2TestCase_Element
 */
function find_element( $sel )
{
    $o = get_test_obj();
    if ( preg_match( '#^/#', $sel, $match ) ) {
        return $o->byXPath( $sel );
    }
    return $o->byCssSelector( $sel );
}

/**
 * Find multiple elements
 *
 * @param string $sel selector.
 */
function find_elements($sel)
{
    $o = get_test_obj();
    return $o->elements($o->using('css selector')->value($sel));
}

function accept_alert()
{
    return get_test_obj()->acceptAlert();
}

/**
 * Check the existence of selector ($sel)
 *
 * @param string $sel You can pass CSS Selector or XPath Selector here
 */
function find_element_ok($sel)
{
    $o = get_test_obj();
    $el = find_element($sel);
    $o->assertNotNull($el,"Find Element: $sel");
    return $el;
}


/**
 * Assert multiple elements exists.
 *
 * @param array $sels Selectors
 */
function find_elements_ok($sels)
{
    $o = get_test_obj();
    $els = array();
    foreach( (array) $sels as $sel ) {
        $el = find_element($sel);
        $o->assertNotNull($el,"Find Element: $sel");
        $els[] = $el;
    }
    return $els;
}


function assert_element_values($data)
{
    foreach( $data as $selector => $val ) 
    {
        $el = find_element($selector);
        $oldval = $el->value();
        is($val,$oldval);
    }
}


/**
 * Simply wait for $miliseconds
 *
 * @param integer $miliseconds how many miliseconds should we wait
 */
function wait( $miliseconds = 30000 )
{
    usleep(300*1000);
#      get_test_obj()
#          ->timeouts()
#          ->implicitWait( $miliseconds );
}

/**
 * Wait for specific stuff for $retry * 500 miliseconds and get that
 *
 * @param string $sel You can pass CSS Selector or XPath Selector here
 * @param itneger $interval microsecond interval for checking element.
 * @param integer $timeout seconds for timeout.
 * @return PHPUnit_Extensions_Selenium2TestCase_Element
 */
function wait_for($sel, $timeout = 1)
{
    $o = get_test_obj();
    $o->timeouts()->implicitWait( $timeout * 1000 ); // 1 second
    return find_element($sel);
}

/**
 * This helper can parse the image src from HTML and extract the path for later operation
 *
 * @param string $sel You can pass CSS Selector or XPath Selector here
 * @return string Relative Path of $imgSrc
 */
function get_image_source_ok( $sel )
{
    $o = get_test_obj();
    $el = find_element_ok($sel);
    $src = $el->attribute('src');
    $info = parse_url($src);
    $o->assertNotEmpty($info,"parse_url from $src is not empty.");
    $o->assertNotNull($info['path'],"Find parse_url path from $src.");

    return $info['path'];
}

/**
 * Set absolute file path to the file input element by selector
 *
 * @param string $sel selector
 * @param string $filepath
 */
function set_input_file( $sel, $filepath )
{
    $el = find_element_ok($sel);
    $el->value( realpath( $filepath ));
    return $el;
}


/**
 * Fill form data with selectors => values
 *
 * @param array $data
 */
function fill_form($data) 
{
    $o = get_test_obj();
    foreach( $data as $sel => $val ) {
        // $el = find_element_ok($sel);
        // $type = $el->attribute('type');
        $el = find_element_ok($sel);
        $o->assertNotNull($el);
        if( $el->attribute('type') == 'text' ) {
            $el->clear();
        }

        try {
            if( $el->attribute('type') == 'file' ) {
                $el->value( realpath($val) );
            } else {
                $el->value($val);
            }
        } catch(Exception $e) {
            $success = write_value_by_js($sel,$val);
            $o->assertTrue($success);
        }
    }
}

/*
 * This helper can help us quick set $value to $selInFrame in frame $frameSel
 *
 * @param string $frameSel You can pass CSS Selector or XPath Selector here
 * @param string $selInFrame You can pass CSS Selector or XPath Selector here
 * @param mixed $value value
 */
function write_to_frame( $frameSel, $selInFrame, $value )
{
    $frameSel = preprocess_id_sel( $frameSel );
    $selInFrame = preprocess_id_sel( $selInFrame );

    $o = get_test_obj();
    $o->frame($frameSel);
    $o->execute(array(
        'script' => "document.getElementById['$selInFrame'].innerHTML = '$value';",
        'args' => array(),
    ));

    $o->frame(null);
}

/*
 * This helper can help us write to TinyMCE $sel with $value by TinyMCE JavaScript API,
 * because there are too many problems when using Selenium API to write values
 *
 * @param string $sel You can pass CSS Selector or XPath Selector here
 * @param mixed $value value
 */
function write_to_tinymce( $sel, $value )
{
    $sel = preprocess_id_sel( $sel );
    $o = get_test_obj();
    $o->execute(array(
        'script' => "tinymce && tinymce.execInstanceCommand('$sel', 'mceInsertContent', false, '$value');",
        'args' => array(),
    ));
}

/*
 * This helper can help us get TinyMCE $sel content out with TinyMCE API
 *
 * @param string $sel You can pass CSS Selector or XPath Selector here
 * @return string content in TinyMCE $sel
 */
function get_content_from_tinymce( $sel )
{
    $sel = preprocess_id_sel( $sel );

    $o = get_test_obj();

    return $o->execute(array(
        'script' => "tinymce && tinymce.get('$sel').getContent();",
        'args' => array(),
    ));
}

/**
 * This helper can help other helpers to change #SEL into SEL by eliminating
 * '#' prefix
 *
 * @param string $sel You can pass CSS Selector or XPath Selector here
 * @return string selector without '#'
 */
function preprocess_id_sel( $sel )
{
    // Automatically truncate '#' from selectors
    // Because this sel is passed into frame method as argument, make a trick here.
    // And also, $sel must be id selector
    return ltrim($sel,'#');
}

function wait_for_tinymce()
{
    wait_for_js_obj('tinymce');
}


function write_value_by_js($selector,$value)
{
    $o = get_test_obj();
    $script = "
        var el = document.querySelector('$selector'); 
        if(el) { 
            // check for tinymce
            if( typeof tinymce !== 'undefined' && el.id ) {
                var editor = tinymce.get(el.id);
                if(editor) {
                    editor.setContent('$value');
                    return true;
                }
            }

            el.setAttribute('value','$value'); 
            if(el.tagName == 'TEXTAREA' ) {
                el.innerHTML = '$value';
            }
            return true;
        }
        return false;
    ";
    return $o->execute(array(
        'script' => $script,
        'args' => array(),
    ));
}


/**
 * Wait for specific stuff (class, function, obj ..) get loaded successfully
 * or timeout when failed to load it.
 *
 * @param string $target Any JavaScript related stuff like class, function , variable .. etc
 * @param integer $timeout wait for $timout seconds
 */
function wait_for_js_obj($target , $timeout = 10)
{
    $o = get_test_obj();
    $isNotLoaded = true;
    $script = "return (typeof window.$target === 'undefined');";
    while ($isNotLoaded && $timeout != 0) {
        $timeout -= 1;
        $o->timeouts()->implicitWait( 1000 ); // 1 second
        $isNotLoaded = $o->execute(array(
            'script' => $script,
            'args' => array(),
        ));
    }
}

/**
 * Calls phpunit testcase assertContains method.
 *
 * TODO: we should move this code to PHPUnit_TestMore
 */
function contains_ok($expected,$container,$msg = null)
{
    return get_test_obj()->assertContains($expected,$container,$msg);
}

/********** CRUD UI related test functions ***********/
function message_contains_ok($text)
{
    $message = wait_for_message();
    $o = get_test_obj();
    $o->assertContains($text,$message);
}

function message_like($pattern)
{
    $o = get_test_obj();
    $message = wait_for_message();
    $o->assertRegExp($pattern,$message);
}

function wait_for_message()
{
    return wait_for('.message')->text();
}

function wait_for_success_message()
{
    return wait_for('.message.success')->text();
}

function wait_for_error_message()
{
    return wait_for('.message.error')->text();
}

function get_alert_text()
{
    return get_test_obj()->alertText();
}

/**
 * Wait for jGrowl element
 */
function wait_for_jgrowl()
{
    return wait_for('.jGrowl-message');
}

function jgrowl_close()
{
    try {
        $el = find_element('.jGrowl-close');
        if($el) 
            $el->click();
    }
    catch (Exception $e) {  }
}

function jgrowl_like($pattern)
{
    $o = get_test_obj();
    $el = wait_for_jgrowl();
    $o->assertNotNull($el,'find jGrowl message element');
    $o->assertRegExp($pattern, $el->text());
    jgrowl_close();
}

/**
 * Make sure that jGrowl message contains expected text.
 *
 * @param string $text
 */
function jgrowl_contains_ok($text)
{
    $o = get_test_obj();
    $el = wait_for_jgrowl();
    $o->assertNotNull($el,'find jGrowl message element');

    // if it looks like a regexp pattern.
    if(    ( $text[0] == '/' && $text[strlen($text)-1] = '/' )
        || ( $text[0] == '#' && $text[strlen($text)-1] = '#' ) )
    {
        $o->assertRegExp($text, $el->text());
    } else {
        $o->assertContains($text, $el->text());
    }
    jgrowl_close();
}


# Sample code of getting subelements from an element
#          $elements = $o->elements($o->using('css selector')->value('.result tbody tr'));
#          foreach( $elements as $element ) {
#              $subelements = $element->elements( $o->using('css selector')->value('input') );
#              var_dump( $subelements ); 
#          }

