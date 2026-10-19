<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LeverancierController extends Controller
{
    public function index()
    {
        $leveranciers = DB::table('leverancier as l')
            ->leftJoin(
                'productperleverancier as ppl',
                'l.Id',
                '=',
                'ppl.LeverancierId'
            )
            ->select(
                'l.Id',
                'l.Naam',
                'l.ContactPersoon',
                'l.LeverancierNummer',
                'l.Mobiel'
            )
            ->selectRaw('COUNT(DISTINCT ppl.ProductId) as AantalProducten')
            ->groupBy(
                'l.Id',
                'l.Naam',
                'l.ContactPersoon',
                'l.LeverancierNummer',
                'l.Mobiel'
            )
            ->orderByDesc('AantalProducten')
            ->get();

        return view('leveranciers.index', compact('leveranciers'));
    }
}