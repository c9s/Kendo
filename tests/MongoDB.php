<?php
/*
 * This file is part of the {{ }} package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
class MongoDBTest extends PHPUnit_Framework_TestCase 
{
	function test() 
	{
		$m = new Mongo();
		ok( $m );

		// select a database
		$db = $m->mongo_testing;
		ok( $db );

		// select a collection (analogous to a relational database's table)
		$collection = $db->cartoons;
		ok( $collection );

		$collection->remove();

		// add a record
		$obj = array( 'title' => 'Calvin and Hobbes', 'author' => 'Bill Watterson' );
		$c = $collection->insert($obj,true);
		ok( $c );
		ok( $obj['_id'] );

		// add another record, with a different "shape"
		$obj = array( 'title' => "XKCD", 'online' => true );
		$collection->insert($obj);
		ok( $obj['_id'] );

		$obj = array( 'title' => "XKCD", 'online' => true );
		$collection->insert($obj);
		ok( $obj['_id'] );

		// find everything in the collection
		$cursor = $collection->find();

		// iterate through the results
		$cnt = 0;
		foreach ($cursor as $obj) {
			$cnt++;
			ok( $obj["title"] );
		}

		is( 3 , $cnt );

		$obj = $collection->findOne(array( 'title' => 'Calvin and Hobbes' ));
		ok( $obj );
		ok( $obj['_id'] );
	}
}



