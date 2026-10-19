<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class LeverancierController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Story 1 - Overzicht Leveranciers
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            // MySQL Stored Procedure
            $leveranciers = collect(
                DB::select('CALL GetLeveranciersOverzicht()')
            );
        } else {
            // SQLite voor automatische tests
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
                ->selectRaw(
                    'COUNT(DISTINCT ppl.ProductId) as AantalProducten'
                )
                ->groupBy(
                    'l.Id',
                    'l.Naam',
                    'l.ContactPersoon',
                    'l.LeverancierNummer',
                    'l.Mobiel'
                )
                ->orderByDesc('AantalProducten')
                ->get();
        }

        return view('leveranciers.index', compact('leveranciers'));
    }

    /*
    |--------------------------------------------------------------------------
    | User Story 1 - Geleverde producten
    |--------------------------------------------------------------------------
    */

    public function producten($id)
    {
        $leverancier = DB::table('leverancier')
            ->where('Id', $id)
            ->first();

        if (!$leverancier) {
            abort(404);
        }

        if (DB::connection()->getDriverName() === 'mysql') {
            // MySQL Stored Procedure met parameter
            $producten = collect(
                DB::select('CALL GetGeleverdeProducten(?)', [$id])
            );
        } else {
            // SQLite voor automatische tests
            $producten = DB::table('productperleverancier as ppl')
                ->join('product as p', 'ppl.ProductId', '=', 'p.Id')
                ->leftJoin('magazijn as m', 'p.Id', '=', 'm.ProductId')
                ->where('ppl.LeverancierId', $id)
                ->select(
                    'p.Id',
                    'p.Naam',
                    'm.VerpakkingsEenheid',
                    'm.AantalAanwezig'
                )
                ->selectRaw(
                    'MAX(ppl.DatumLevering) as LaatsteLevering'
                )
                ->groupBy(
                    'p.Id',
                    'p.Naam',
                    'm.VerpakkingsEenheid',
                    'm.AantalAanwezig'
                )
                ->orderByDesc('m.AantalAanwezig')
                ->get();
        }

        return view('leveranciers.producten', compact(
            'leverancier',
            'producten'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | User Story 2 - Levering formulier
    |--------------------------------------------------------------------------
    */

    public function createLevering($leverancierId, $productId)
    {
        $leverancier = DB::table('leverancier')
            ->where('Id', $leverancierId)
            ->first();

        if (!$leverancier) {
            abort(404);
        }

        $product = DB::table('productperleverancier as ppl')
            ->join('product as p', 'p.Id', '=', 'ppl.ProductId')
            ->where('ppl.LeverancierId', $leverancierId)
            ->where('ppl.ProductId', $productId)
            ->select('p.Id', 'p.Naam', 'p.IsActief')
            ->first();

        if (!$product) {
            abort(404);
        }

        return view('leveranciers.levering', compact(
            'leverancier',
            'product'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | User Story 2 - Nieuwe levering opslaan
    |--------------------------------------------------------------------------
    */

    public function storeLevering(
        Request $request,
        $leverancierId,
        $productId
    ) {
        // Controle van formuliergegevens
        $gegevens = $request->validate([
            'aantal' => ['required', 'integer', 'min:1'],
            'datum' => ['required', 'date'],
        ]);

        // Controle of product bij deze leverancier hoort
        $product = DB::table('product as p')
            ->join(
                'productperleverancier as ppl',
                'p.Id',
                '=',
                'ppl.ProductId'
            )
            ->where('p.Id', $productId)
            ->where('ppl.LeverancierId', $leverancierId)
            ->select('p.Id', 'p.Naam', 'p.IsActief')
            ->first();

        if (!$product) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | MySQL - Stored Procedure
        |--------------------------------------------------------------------------
        */

        if (DB::connection()->getDriverName() === 'mysql') {
            try {
                // Transactie wordt door de procedure beheerd
                DB::statement(
                    'CALL VoegNieuweLeveringToe(?, ?, ?, ?)',
                    [
                        $leverancierId,
                        $productId,
                        $gegevens['aantal'],
                        $gegevens['datum'],
                    ]
                );
            } catch (QueryException $e) {
                // Ook bij wijzigingen tussen controle en opslaan
                // kan de procedure het product afwijzen.
                if ($e->getCode() === '45000' &&
                    str_contains($e->getMessage(), 'Product is niet actief')) {
                    $product = DB::table('product')
                        ->where('Id', $productId)
                        ->first();

                    return view('leveranciers.inactief', [
                        'product' => $product,
                        'leverancierId' => $leverancierId,
                    ]);
                }

                report($e);

                return back()
                    ->withErrors([
                        'levering' => 'De levering kon niet worden opgeslagen.',
                    ])
                    ->withInput();
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | SQLite - Automatische Laravel tests
            |--------------------------------------------------------------------------
            */

            $resultaat = DB::transaction(function () use (
                $leverancierId,
                $productId,
                $gegevens
            ) {
                $product = DB::table('product')
                    ->where('Id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    abort(404);
                }

                $levering = DB::table('productperleverancier')
                    ->where('LeverancierId', $leverancierId)
                    ->where('ProductId', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$levering) {
                    abort(404);
                }

                if (!(bool) $product->IsActief) {
                    return 'inactief';
                }

                $magazijn = DB::table('magazijn')
                    ->where('ProductId', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$magazijn) {
                    abort(404, 'Geen magazijnrecord gevonden.');
                }

                $nu = now();

                DB::table('magazijn')
                    ->where('Id', $magazijn->Id)
                    ->update([
                        'AantalAanwezig' =>
                            ($magazijn->AantalAanwezig ?? 0)
                            + $gegevens['aantal'],
                        'DatumGewijzigd' => $nu,
                    ]);

                DB::table('productperleverancier')
                    ->where('Id', $levering->Id)
                    ->update([
                        'DatumLevering' => $nu->toDateString(),
                        'DatumEerstVolgendeLevering' => $gegevens['datum'],
                        'DatumGewijzigd' => $nu,
                    ]);

                return 'opgeslagen';
            });

            if ($resultaat === 'inactief') {
                return view('leveranciers.inactief', [
                    'product' => $product,
                    'leverancierId' => $leverancierId,
                ]);
            }
        }

        return redirect()
            ->route('leveranciers.producten', $leverancierId)
            ->with(
                'success',
                'De nieuwe levering is opgeslagen.'
            );
    }
}
