<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\CrawlerService;
use App\Service\ZodiacService;

class ZodiacController extends Controller
{
    /**
     * CrawlerService
     * 
     * @var CrawlerService 爬蟲Service
     */ 
    private $crawlerService;

    /**
     * ZodiacService
     *
     * @var ZodiacService 星座Service
     */
    private $zodiacService;

    /**
     * Construct
     *
     * @param CrawlerService $crawlerService
     * @param ZodiacService $zodiacService
     */
    public function __construct(
        CrawlerService $crawlerService,
        ZodiacService $zodiacService
    ) {
        $this->crawlerService = $crawlerService;
        $this->zodiacService  = $zodiacService;
    }

    /**
     * Save Zodiac Data
     *
     * @return void
     */
    public function saveZodiac()
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

    /**
     * Get Latest Zodiac Info
     */
    public function latestZodiacInfo()
    {
        $filter = [
            'started_at' => now()->format('Y-m-d H:00:00'),
            'ended_at'   => now()->addHours(1)->format('Y-m-d H:00:00')
        ];

        $result = $this->zodiacService->getListByFilter($filter);

        return $result;
    }
}
