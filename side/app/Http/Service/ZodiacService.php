<?php

namespace App\Service;

use App\Repository\ZodiacRepository;

/**
 * ZodiacService
 */
class ZodiacService
{
    /**
     * ZodiacRepository
     *
     * @var ZodiacRepository $zodiacRepo
     */
    protected $zodiacRepo;

    /**
     * Undocumented function
     *
     * @param ZodiacRepository $zodiacRepo 星座Repo
     */
    public function __construct(
        ZodiacRepository $zodiacRepo
    ) {
        $this->zodiacRepo = $zodiacRepo;
    }

    /**
     * Save
     *
     * @param array $paramAry 傳入參數
     *
     * @return void
     */
    public function save(array $paramAry)
    {
        $this->zodiacRepo->save($paramAry);
    }

    /**
     * GetListByFilter
     *
     * @param array $filter 過濾器
     *
     * @return array
     */
    public function getListByFilter($filter)
    {
        $result = $this->zodiacRepo->getListByFilter($filter)->toArray();

        dd($result);
    }
}