<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeverancierController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Story 1 - Overzicht Leveranciers
    |--------------------------------------------------------------------------
    */

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
            ->selectRaw('MAX(ppl.DatumLevering) as LaatsteLevering')
            ->groupBy(
                'p.Id',
                'p.Naam',
                'm.VerpakkingsEenheid',
                'm.AantalAanwezig'
            )
            ->orderByDesc('m.AantalAanwezig')
            ->get();

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
        // Ingevoerde gegevens controleren
        $gegevens = $request->validate([
            'aantal' => ['required', 'integer', 'min:1'],
            'datum' => ['required', 'date'],
        ]);

        // Alle wijzigingen binnen één transactie
        $resultaat = DB::transaction(function () use (
            $leverancierId,
            $productId,
            $gegevens
        ) {
            // Product controleren en vergrendelen
            $product = DB::table('product')
                ->where('Id', $productId)
                ->lockForUpdate()
                ->first();

            if (!$product) {
                abort(404);
            }

            // Controleren of leverancier dit product levert
            $levering = DB::table('productperleverancier')
                ->where('LeverancierId', $leverancierId)
                ->where('ProductId', $productId)
                ->lockForUpdate()
                ->first();

            if (!$levering) {
                abort(404);
            }

            // Scenario 02: Inactief product
            if (!(bool) $product->IsActief) {
                return 'inactief';
            }

            // Magazijnrecord ophalen
            $magazijn = DB::table('magazijn')
                ->where('ProductId', $productId)
                ->lockForUpdate()
                ->first();

            if (!$magazijn) {
                abort(404, 'Geen magazijnrecord gevonden.');
            }

            $nu = now();

            // Voorraad verhogen
            DB::table('magazijn')
                ->where('Id', $magazijn->Id)
                ->update([
                    'AantalAanwezig' =>
                        ($magazijn->AantalAanwezig ?? 0)
                        + $gegevens['aantal'],
                    'DatumGewijzigd' => $nu,
                ]);

            // Leveringsdatums bijwerken
            DB::table('productperleverancier')
                ->where('Id', $levering->Id)
                ->update([
                    'DatumLevering' => $nu->toDateString(),
                    'DatumEerstVolgendeLevering' => $gegevens['datum'],
                    'DatumGewijzigd' => $nu,
                ]);

            return 'opgeslagen';
        });

        // Inactief product: foutmelding tonen
        if ($resultaat === 'inactief') {
            $product = DB::table('product')
                ->where('Id', $productId)
                ->first();

            return view('leveranciers.inactief', [
                'product' => $product,
                'leverancierId' => $leverancierId,
            ]);
        }

        // Terug naar geleverde producten
        return redirect()
            ->route('leveranciers.producten', $leverancierId)
            ->with('success', 'De nieuwe levering is opgeslagen.');
    }
}
