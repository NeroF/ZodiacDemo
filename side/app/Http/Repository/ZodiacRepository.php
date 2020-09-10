<?php

namespace App\Repository;

use App\Model\Zodiac;

/**
 * ZodiacRepository
 */
class ZodiacRepository
{
    /**
     * 星座Model
     *
     * @var Zodiac $zodiac 星座Model
     */
    private $zodiac;

    /**
     * Construct
     *
     * @param Zodiac $zodiac 星座Model
     */
    public function __construct(
        Zodiac $zodiac
    ) {
        $this->zodiac = $zodiac;
    }

    /**
     * Create
     *
     * @param array $paramAry
     *
     * @return void
     */
    public function save(array $paramAry)
    {
        return $this->zodiac->insert($paramAry);
    }

    /**
     * GetListByFilter
     *
     * @param array $filter
     *
     * @return Collection
     */
    public function getListByFilter(array $filter)
    {
        $query = $this->zodiac->query();

        $query->when(
            isset($filter['started_at']), function ($query) use ($filter) {
                $query->where('created_at', '>', $filter['started_at']);
            }
        );

        $query->when(
            isset($filter['ended_at']), function ($query) use ($filter) {
                $query->where('created_at', '<', $filter['ended_at']);
            }
        );

        $query->orderBy('created_at', 'desc')->take(12);

        return $query->get();
    }
}
