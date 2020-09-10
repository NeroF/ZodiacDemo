<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Psr7\Request;

/**
 * 爬蟲Service
 */
class CrawlerService
{
    /**
     * 取得連結內容
     *
     * @param string $url 連結
     *
     * @return string
     */
    public function getUrlContent(string $url): string
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $url, [
            'verify' => false
        ]);
        $contents = $response->getBody()->getContents();

        return $contents;
    }

    /**
     * Get Nodes
     *
     * @param string $contents 網頁Body
     *
     * @return array
     */
    public function getNodes(string $contents): array
    {
        $targetZodiacNodes = [];

        $crawler = new Crawler();
        $crawler->addHtmlContent($contents);

        $target = $crawler->filterXpath('//div[contains(@class, "STAR12_BOX")]')->filter('li');
        $target->each(
            function ($node) use (&$targetZodiacNodes) {
                $targetUrls          = $node->filter('a')->attr('href');
                $targetZodiacNodes[] = $targetUrls;
            }
        );

        return $targetZodiacNodes;
    }

    /**
     * 取得網頁跳轉後的網頁內容
     *
     * @param string $url URL
     *
     * @return string
     */
    public function getRedirectUrl(string $url): string
    {
        $preg     = '/http:\/\/([a-z]|[A-Z]|[0-9]|.)*/';
        $client   = new \GuzzleHttp\Client();
        $crawler  = new Crawler();
        $response = $client->request('GET', $url);

        $homePageBodyContents = $response->getBody()->getContents();
        $crawler->addHtmlContent($homePageBodyContents);

        $target = $crawler->filter('script')->html();
        preg_match($preg, $target, $url);
        return $url[0];
    }

    /**
     * 取得星座資訊
     *
     * @param string $contents 網頁內容
     *
     * @return array
     */
    public function getZodiacInfo(string $contents): array
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($contents);

        $target = $crawler->filterXPath(('//div[contains(@class, "FORTUNE_BG")]'));

        return $this->paraseZodiacInfoDetail($target);
    }

    /**
     * 解析星座資訊細節
     *
     * @param Crawler $crawler 爬蟲物件
     *
     * @return array
     */
    private function paraseZodiacInfoDetail(Crawler $crawler): array
    {
        $parsers       = [];
        $certainTarget = [];
        $content       = $crawler->filterXPath(('//div[contains(@class, "TODAY_CONTENT")]'));
        $zodiac        = preg_replace('/(今日|解析)/', '', $content->filter('h3')->html());

        $content->filter('p')->each(
            function ($node) use (&$parsers) {
                $span = $node->filter('span');
                if ($span->getNode(0)) {
                    $target = preg_split('/★/', $span->html());

                    $parsers[] = $target[0];
                    $parsers[] = count($target) - 1;
                }

                if (!$span->getNode(0)) {
                    $parsers[] = $node->html();
                }
            }
        );

        $certainTarget = $this->prepareZodoiacInfoDetail($parsers);

        $certainTarget['zodiac'] = $zodiac;

        return $certainTarget;
    }

    /**
     * 準備星座回傳資料
     *
     * @param array $zodiacInfoDetail 星座資訊Detail
     *
     * @return array
     */
    private function prepareZodoiacInfoDetail(array $zodiacInfoDetail):array
    {
        // init parameter
        $title  = null;
        $result = [];
        for ($i = 0; $i < count($zodiacInfoDetail); $i += 3) {
            switch ($zodiacInfoDetail[$i]) {
                case "整體運勢":
                    $title = 'total';
                    break;
                case "愛情運勢":
                    $title = 'love';
                    break;
                case "事業運勢":
                    $title = 'business';
                    break;
                case "財運運勢";
                    $title = 'fortune';
                    break;
                default:
                    break;
            }

            if ($title) {
                $result[$title.'_score']   = $zodiacInfoDetail[$i+1];
                $result[$title.'_comment'] = $zodiacInfoDetail[$i+2];
            }
        }//end for
        return $result;
    }
}
