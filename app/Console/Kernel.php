<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        # 평일 pm 7:10분에 크롤링
        $schedule->call(function(){
            $k = new \App\Library\KospiCrawl;
            $list = $k->getTodayData();

            foreach($list as $item){
                $codeIdx = \App\Code::addIfNotExist($item['ksCode'],$item['ksTempCompanyName'],$item['ksRank']);
                \App\Kospi::create(array(
                    'ksDate' => $item['ksDate']
                    ,'stCodeIdx' => $codeIdx
                    ,'ksCode' => $item['ksCode']
                    ,'ksTempCompanyName' => $item['ksTempCompanyName']
                    ,'ksRank' => $item['ksRank']
                    ,'ksHyunJaeGa' => $item['ksHyunJaeGa']
                    ,'ksJulIlBi' => $item['ksJulIlBi']
                    ,'ksDeunRakPok' => $item['ksDeunRakPok']
                    ,'ksAekMyunGa' => $item['ksAekMyunGa']
                    ,'ksSiGaChongAek' => $item['ksSiGaChongAek']
                    ,'ksSangJangJuSu' => $item['ksSangJangJuSu']
                    ,'ksForBiYul' => $item['ksForBiYul']
                    ,'ksGaeRaeRyang' => $item['ksGaeRaeRyang']
                    ,'ksPER' => $item['ksPER']
                    ,'ksROE' => $item['ksROE']
                    ,'ksGaeRaeDaeGueam' => $item['ksGaeRaeDaeGueam']
                    ,'ksJunIlGaeRaeRyang' => $item['ksJunIlGaeRaeRyang']
                    ,'ksSiGa' => $item['ksSiGa']
                    ,'ksGoGa' => $item['ksGoGa']
                    ,'ksJeoGa' => $item['ksJeoGa']
                    ,'ksMaeSuHoGa' => $item['ksMaeSuHoGa']
                    ,'ksMaeDoHoGa' => $item['ksMaeDoHoGa']
                    ,'ksMaeSuChongJanRyang' => $item['ksMaeSuChongJanRyang']
                    ,'ksMaeDoChongJanRyang' => $item['ksMaeDoChongJanRyang']
                    ,'ksMaeChulAek' => $item['ksMaeChulAek']
                    ,'ksJaSanChongGae' => $item['ksJaSanChongGae']
                    ,'ksBuChaeChongGae' => $item['ksBuChaeChongGae']
                    ,'ksYungUpEaIk' => $item['ksYungUpEaIk']
                    ,'ksDangGiSunEaIk' => $item['ksDangGiSunEaIk']
                    ,'ksJuDangSunEaIk' => $item['ksJuDangSunEaIk']
                    ,'ksBoTongJuBaeDangGuem' => $item['ksBoTongJuBaeDangGuem']
                    ,'ksMaeCulAekJungGaYul' => $item['ksMaeCulAekJungGaYul']
                    ,'ksYoungUpEaIkJungGaYul' => $item['ksYoungUpEaIkJungGaYul']
                    ,'ksROA' => $item['ksROA']
                    ,'ksPBR' => $item['ksPBR']
                    ,'ksYuBoYul' => $item['ksYuBoYul']
                ));
            }
            unset($k, $list);


            $crawl = new \App\Library\Crawl();
            foreach(Code::all() as $code){
                $crawl->reset();
                $result = $crawl->getCodeData($code);
                $code->saveUpdateDate($crawl->oldestDate, $crawl->lastestDate);

                foreach ($result as $key => $value) {
                    $exist = \App\DayData::whereRaw('stCodeIdx = ? and ddDate = ?',array($value['codeIdx'],$value['ddDate']))->count();
                    if($exist < 1){
                        \App\DayData::create(array(
                            'stCodeIdx'=>$value['codeIdx']
                            ,'ddDate'=>$value['ddDate']
                            ,'ddJongGa'=>$value['ddJongGa']
                            ,'ddJulIlBi'=>$value['ddJulIlBi']
                            ,'ddDeunRakPok'=>$value['ddDeunRakPok']
                            ,'ddGeRaeRyang'=>$value['ddGeRaeRyang']
                            ,'ddSunMaeMae'=>$value['ddSunMaeMae']
                            ,'ddForSunMaeMae'=>$value['ddForSunMaeMae']
                            ,'ddForBoYuJuSu'=>$value['ddForBoYuJuSu']
                            ,'ddforBoYuYul'=>$value['ddforBoYuYul']
                        )); 
                    }
                }
            }
            unset($crawl, $result);


            $crawl = new \App\Library\SiseCrawl();
            $list = \App\Code::all();
            foreach($list as $code){
                $crawl->reset();
                $result = $crawl->getCodeData($code);
                $code->saveUpdateDateSise($crawl->oldestDate, $crawl->lastestDate);

                foreach ($result as $key => $value) {
                    $exist = \App\Sise::whereRaw('stCodeIdx = ? and ssDate = ?',array($value['codeIdx'],$value['ssDate']))->count();
                    if($exist < 1){
                        \App\Sise::create(array(
                            'stCodeIdx'=>$value['codeIdx']
                            ,'ssDate'=>$value['ssDate']
                            ,'ssJongGa'=>$value['ssJongGa']
                            ,'ssJulIlBi'=>$value['ssJulIlBi']
                            ,'ssSiGa'=>$value['ssSiGa']
                            ,'ssGoGa'=>$value['ssGoGa']
                            ,'ssJeoGa'=>$value['ssJeoGa']
                            ,'ssGeRaeRyang'=>$value['ssGeRaeRyang']
                        )); 
                    }
                }
            }
            unset($crawl, $list);

        })->weekdays()->dailyAt('19:10'); 
    }
}
