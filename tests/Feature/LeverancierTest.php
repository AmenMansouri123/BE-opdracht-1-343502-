<?php

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Maak de benodigde tabellen voor deze test
beforeEach(function () {

    Schema::create('leverancier', function (Blueprint $table) {
        $table->id('Id');
        $table->string('Naam');
        $table->string('ContactPersoon')->nullable();
        $table->string('LeverancierNummer')->nullable();
        $table->string('Mobiel')->nullable();
    });

    Schema::create('productperleverancier', function (Blueprint $table) {
        $table->id('Id');
        $table->unsignedBigInteger('LeverancierId');
        $table->unsignedBigInteger('ProductId');
    });

});

// Test 1: Ingelogde gebruiker ziet leveranciers
test('ingelogde gebruiker kan leveranciers bekijken', function () {

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('leveranciers.index'));

    $response->assertStatus(200);
    $response->assertViewIs('leveranciers.index');
    $response->assertViewHas('leveranciers');

});

// Test 2: Gast wordt doorgestuurd naar login
test('gast kan leveranciers niet bekijken', function () {

    $response = $this->get(route('leveranciers.index'));

    $response->assertRedirect(route('login'));

});
