<?php
declare(strict_types=1);
namespace CROFin\Repository;

interface StatementRepositoryInterface
{
    /**
     * @param  string $dateStart
     * @param  string $dateEnd
     * @param  int    $userId
     * @return mixed
     */
    public function all(string $dateStart, string $dateEnd, int $userId): array;
}