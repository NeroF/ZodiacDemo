<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\ZodiacService;
use App\Service\CrawlerService;

class CrawlerZodiac extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:zodiac';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '星座排程';

    /**
     * ZodiacService
     *
     * @var ZodiacService 星座Service
     */
    protected $zodiacService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ZodiacService $zodiacService,
        CrawlerService $crawlerService
    ) {
        parent::__construct();

        $this->zodiacService = $zodiacService;
        $this->crawlerService = $crawlerService;
    }

    /**
     * Execute the console command.
     * 與 Zodiac@saveZodiac 相同，提供排程寫入
     *
     * @return void
     */
    public function handle()
    {
        $target = [];
        $url    = 'https://astro.click108.com.tw/';

        $content  = $this->crawlerService->getUrlContent($url);
        $nodeUrls = $this->crawlerService->getNodes($content);
        foreach ($nodeUrls as $url) {
            $redirectUrl    = $this->crawlerService->getRedirectUrl($url);
            $zodiacContents = $this->crawlerService->getUrlContent($redirectUrl);

            $target[] = $this->crawlerService->getZodiacInfo($zodiacContents);
        }

        $this->zodiacService->save($target);
    }
}
