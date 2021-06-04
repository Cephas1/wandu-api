@extends('layouts.index')

@section('content')
<section class="page-section bg-light" id="portfolio">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="portfolio-item">
                            <img class="img-fluid" src="{{ asset('template/assets/img/portfolio/04-thumbnail.jpg') }}" alt="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3 class="my-3">{{ $article->name .'  _  '. $article->price_4 . ' FCFA'  }}</h3>
                        <div class="portfolio-caption-subheading text-muted">{{ $article->category->name }}</div>
                        <hr/>
                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                        @if($in_basket == 0)
                            <form method="POST" action="{{ route('basket.store') }}">
                                @csrf
                                <input type="hidden" name="article_id" value="{{ $article->id }}">
                                <button class="btn btn-primary" type="submit"> + Ajouter dans le panier</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>
@endsection
