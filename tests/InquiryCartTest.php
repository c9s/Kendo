<?php
/*
 * This file is part of the Phifty package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
use InquiryCart\InquiryCartCore;
use InquiryCart\InquiryItemInterface;
use InquiryCart\Model\InquiryItem;
use InquiryCart\ProductInquiryItem;
use Product\Model\Product;
use Product\Model\ProductCollection;
use Phifty\Testing\TestCase;

class InquiryCartTest extends PHPUnit_Framework_TestCase 
{
    function test()
    {
        $record = new Product;
        ok( $record );

        $record->create(array( 'name' => 'product name' , 'subtitle' => 'subtitle' ));
        ok( $record->id );

        $item = InquiryCartCore::create( 'InquiryCart\ProductInquiryItem' , $record );
        ok( $item );


		$cart = InquiryCartCore::getInstance();
		ok( $cart );

		$cart->addItem( $item );

		$items = $cart->getItems();
		ok( $items );
		count_ok( 1 , $items );


		// sync items to session
		$cart->updateSession();

		// reload
		$cart->load();


		$items = $cart->getItems();
		ok( $items );
		count_ok( 1 , $items );

		$item = $items[0];
		ok( $item );

		ok( $item->getTitle() );
		ok( $item->getDesc() );

		$cart->removeItem( $item );

		$items = $cart->getItems();
		count_ok( 0 , $items );
    }
}
