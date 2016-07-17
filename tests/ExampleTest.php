<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\Library\Stock;
use \App\Library\Crawl;
use \App\Code;
use \App\DayData;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    // public function testBasicExample()
    // {
    //     $this->visit('/')
    //          ->see('Laravel 5');
    // }
    public $code;
    public $stock;
    public $crawl;

    public function test1(){
        Code::firstOrCreate([
            "cdNumber"=>'123456'
        ]);
        $list = Code::get()->lists('cdNumber')->toArray();
        $this->assertEquals(['123456'],$list);
        
        $list = Code::all()->toArray();
        $key = $list[0]['id'];
        Code::where('id','=',$key)->delete();
        $list = Code::all()->toArray();
        $this->assertEquals([],$list);

        Code::add('456789');
        $list = Code::get()->lists('cdNumber')->toArray();
        $this->assertEquals(['456789'],$list);

        Code::remove('456789');
        $list = Code::all()->toArray();
        $this->assertEquals([],$list);

        Code::add('123');
        Code::add('456');
        Code::add('789');
        $list = array_values(Code::lists());
        Code::remove('123');
        Code::remove('456');
        Code::remove('789');
        $this->assertEquals(3,count($list));
    }

    
    // public function test1(){
    //     // $stock = new Stock();
    //     // $crawl = new Crawl();
    //     // echo $crawl->getHtml(null);
    // }
}
