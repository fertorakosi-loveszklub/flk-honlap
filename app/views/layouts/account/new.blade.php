@extends('layouts.master')

@section('content')
<div class="content text-justify">
    <h2>Új felhasználó</h2>
    <div id="join">
        <p>
            Úgy tűnik, először szeretnél belépni az oldalra. Ez a funkció azonban csak a klub tagjai számára van fenntartva,
            ezért előbb meg kell erősítened a tagságodat a csoporthoz való csatlakozással. Ehhez kattints az alábbi gombra:
        </p>
        <p><button class="btn btn-primary" id="joinGroup"><i class="fa fa-facebook fa-fw"></i> Belépés a tagok csoportjába</button></p>
    </div>
    <div id="login" style="display: none;">
        <div class="alert alert-success" role="alert">
            Beléptél a csoportba, most már bejelentkezhetsz.
        </div>
    </div>
</div>
@overwrite

@section('scripts')
    <script type="text/javascript" src="{{ secure_asset('/js/newuser.js') }}"></script>
@overwrite