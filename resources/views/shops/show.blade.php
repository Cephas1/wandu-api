@extends('layouts.index')

@section('content')
<section class="page-section bg-light" id="portfolio">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="portfolio-item">
                            <img class="img-fluid" src="{{ asset('template/assets/img/portfolio/03-thumbnail.jpg') }}" alt="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3 class="my-3">{{ $shop->name  }}</h3>
                        <div class="portfolio-caption-subheading text-muted">Adresse : {{ $shop->location }}</div>
                        @if($shop->email)<div class="portfolio-caption-subheading text-muted">Email : {{ $shop->email }}</div>@endif
                        <div class="portfolio-caption-subheading text-muted">Telephone : {{ $shop->phone }}</div>
                        <hr/>
                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                    </div>
                </div>
            </div>
        </section>
@endsection
