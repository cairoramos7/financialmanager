<?php
declare(strict_types=1);
namespace CROFin\Repository;

use CROFin\Models\BillPay;
use CROFin\Models\BillReceive;
use Illuminate\Support\Collection;

class StatementRepository implements StatementRepositoryInterface
{
    /**
     * @param string $dateStart
     * @param string $dateEnd
     * @param int $userId
     * @return mixed
     */
    public function all(string $dateStart, string $dateEnd, int $userId): array
    {
        $billPay = BillPay::query()
            ->selectRaw('bill_pays.*, category_costs.name as category_name')
            ->leftJoin('category_costs', 'category_costs.id', '=', 'bill_pays.category_cost_id')
            ->whereBetween('date_launch', [
                $dateStart,
                $dateEnd
            ])
            ->where('bill_pays.user_id', $userId)
            ->get();

        $billReceives = BillReceive::query()
            ->whereBetween('date_launch', [
                $dateStart,
                $dateEnd
            ])
            ->where('user_id', $userId)
            ->get();

        $collection = new Collection(array_merge_recursive($billPay->toArray(), $billReceives->toArray()));
        $statements = $collection->sortByDesc('date_launch');

        return [
            'statements' => $statements,
            'total_pays' => $billPay->sum('value'),
            'total_receives' => $billReceives->sum('value')
        ];
    }
}