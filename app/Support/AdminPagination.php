<?php

namespace App\Support;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/** One page size for every admin table, and a guard for page numbers past the end. */
class AdminPagination
{
    public const PER_PAGE = 8;

    /**
     * A page past the last one (e.g. after deleting the only row on the last page) would show an empty table
     * that reads like "nothing here". Send the user to the last page that has rows instead, keeping the rest
     * of the query (filters, search, tab). Returns null when every list is on a real page.
     */
    public static function pastEnd(Request $request, LengthAwarePaginator ...$lists): ?RedirectResponse
    {
        $fixes = [];

        foreach ($lists as $list) {
            if ($list->isEmpty() && $list->currentPage() > 1) {
                $fixes[$list->getPageName()] = max(1, $list->lastPage());
            }
        }

        return $fixes ? redirect()->to($request->url().'?'.http_build_query([...$request->query(), ...$fixes])) : null;
    }
}
