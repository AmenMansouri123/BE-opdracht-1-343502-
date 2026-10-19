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
        // MySQL gebruikt de Stored Procedure
        if (DB::connection()->getDriverName() === 'mysql') {

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
        // Leverancier ophalen
        $leverancier = DB::table('leverancier')
            ->where('Id', $id)
            ->first();

        if (!$leverancier) {
            abort(404);
        }

        // MySQL gebruikt de Stored Procedure
        if (DB::connection()->getDriverName() === 'mysql') {

            $producten = collect(
                DB::select('CALL GetGeleverdeProducten(?)', [$id])
            );

        } else {

            // SQLite voor automatische tests
            $producten = DB::table('productperleverancier as ppl')
                ->join(
                    'product as p',
                    'ppl.ProductId',
                    '=',
                    'p.Id'
                )
                ->leftJoin(
                    'magazijn as m',
                    'p.Id',
                    '=',
                    'm.ProductId'
                )
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
        // Leverancier ophalen
        $leverancier = DB::table('leverancier')
            ->where('Id', $leverancierId)
            ->first();

        if (!$leverancier) {
            abort(404);
        }

        // Product ophalen bij de gekozen leverancier
        $product = DB::table('productperleverancier as ppl')
            ->join(
                'product as p',
                'p.Id',
                '=',
                'ppl.ProductId'
            )
            ->where('ppl.LeverancierId', $leverancierId)
            ->where('ppl.ProductId', $productId)
            ->select(
                'p.Id',
                'p.Naam',
                'p.IsActief'
            )
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

        // Wijzigingen uitvoeren binnen één transactie
        $resultaat = DB::transaction(function () use (
            $leverancierId,
            $productId,
            $gegevens
        ) {

            // Product ophalen en vergrendelen
            $product = DB::table('product')
                ->where('Id', $productId)
                ->lockForUpdate()
                ->first();

            if (!$product) {
                abort(404);
            }

            // Controleren of leverancier het product levert
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

            // Magazijnrecord ophalen en vergrendelen
            $magazijn = DB::table('magazijn')
                ->where('ProductId', $productId)
                ->lockForUpdate()
                ->first();

            if (!$magazijn) {
                abort(404, 'Geen magazijnrecord gevonden.');
            }

            $nu = now();

            // Nieuwe levering toevoegen aan voorraad
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

        // Scenario 02: Product wordt niet meer geproduceerd
        if ($resultaat === 'inactief') {

            $product = DB::table('product')
                ->where('Id', $productId)
                ->first();

            return view('leveranciers.inactief', [
                'product' => $product,
                'leverancierId' => $leverancierId,
            ]);
        }

        // Scenario 01: Levering succesvol opgeslagen
        return redirect()
            ->route('leveranciers.producten', $leverancierId)
            ->with(
                'success',
                'De nieuwe levering is opgeslagen.'
            );
    }
}
