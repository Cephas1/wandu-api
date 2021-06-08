@extends('layouts.index')

@section('content')
<section class="page-section bg-light" id="team">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Mon pannier</h2>
                </div>
                <div class="row">
                    <basket-component :baskets="{{ $baskets }}"></basket-component>
                </div>
            </div>
        </section>
@endsection
