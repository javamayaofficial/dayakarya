<?php

namespace App\Http\Controllers\Api;

use App\Models\AffiliateLink;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Affiliate: buat link per karya + lihat statistik komisi.
 */
class AffiliateController extends \App\Http\Controllers\Controller
{
    public function createLink(Request $request, Work $work): JsonResponse
    {
        $link = AffiliateLink::firstOrCreate(
            ['affiliate_id' => $request->user()->id, 'work_id' => $work->id],
            ['code' => strtoupper(Str::random(8))]
        );

        return response()->json([
            'code' => $link->code,
            'url'  => url("/r/{$link->code}"),
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'links'        => AffiliateLink::where('affiliate_id', $user->id)->get(),
            'total_clicks' => AffiliateLink::where('affiliate_id', $user->id)->sum('clicks'),
            'total_commission' => $user->commissions()->sum('amount_rupiah'),
        ]);
    }
}
