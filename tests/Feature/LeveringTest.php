<?php

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    // Testtabellen maken in SQLite
    Schema::create('product', function (Blueprint $table) {
        $table->id('Id');
        $table->string('Naam');
        $table->boolean('IsActief')->default(true);
    });

    Schema::create('leverancier', function (Blueprint $table) {
        $table->id('Id');
        $table->string('Naam');
        $table->string('ContactPersoon')->nullable();
        $table->string('Mobiel')->nullable();
    });

    Schema::create('magazijn', function (Blueprint $table) {
        $table->id('Id');
        $table->unsignedBigInteger('ProductId');
        $table->integer('AantalAanwezig')->default(0);
        $table->dateTime('DatumGewijzigd')->nullable();
    });

    Schema::create('productperleverancier', function (Blueprint $table) {
        $table->id('Id');
        $table->unsignedBigInteger('LeverancierId');
        $table->unsignedBigInteger('ProductId');
        $table->date('DatumLevering')->nullable();
        $table->date('DatumEerstVolgendeLevering')->nullable();
        $table->dateTime('DatumGewijzigd')->nullable();
    });

    // Testgegevens toevoegen
    DB::table('product')->insert([
        ['Id' => 1, 'Naam' => 'Mintnopjes', 'IsActief' => 1],
        ['Id' => 2, 'Naam' => 'Winegums', 'IsActief' => 0],
    ]);

    DB::table('leverancier')->insert([
        'Id' => 1,
        'Naam' => 'Test Leverancier',
        'ContactPersoon' => 'Test Persoon',
        'Mobiel' => '0612345678',
    ]);

    DB::table('magazijn')->insert([
        ['ProductId' => 1, 'AantalAanwezig' => 453],
        ['ProductId' => 2, 'AantalAanwezig' => 100],
    ]);

    DB::table('productperleverancier')->insert([
        ['LeverancierId' => 1, 'ProductId' => 1],
        ['LeverancierId' => 1, 'ProductId' => 2],
    ]);
});

// Scenario 01: Actief product
test('nieuwe levering verhoogt de voorraad', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(
        route('leveranciers.levering.store', [
            'leverancierId' => 1,
            'productId' => 1,
        ]),
        [
            'aantal' => 25,
            'datum' => '2026-10-29',
        ]
    );

    $response->assertRedirect(
        route('leveranciers.producten', 1)
    );

    $this->assertDatabaseHas('magazijn', [
        'ProductId' => 1,
        'AantalAanwezig' => 478,
    ]);

    $this->assertDatabaseHas('productperleverancier', [
        'LeverancierId' => 1,
        'ProductId' => 1,
        'DatumEerstVolgendeLevering' => '2026-10-29',
    ]);
});

// Scenario 02: Inactief product
test('inactief product kan geen levering ontvangen', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(
        route('leveranciers.levering.store', [
            'leverancierId' => 1,
            'productId' => 2,
        ]),
        [
            'aantal' => 30,
            'datum' => '2026-10-29',
        ]
    );

    $response->assertOk();
    $response->assertViewIs('leveranciers.inactief');

    // Voorraad mag niet veranderen
    $this->assertDatabaseHas('magazijn', [
        'ProductId' => 2,
        'AantalAanwezig' => 100,
    ]);
});
