@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <passport-clients></passport-clients>
					<passport-authorized-clients></passport-authorized-clients>
					<passport-personal-access-tokens></passport-personal-access-tokens>	
                </div>
            </div>
            <div class="card">
                <form method="post" action="{{ route('passport.clients.store') }}">
                    @csrf
                    <input type="text" name="name"/>
                    <input type="text" name="redirect"/>
                    <input type="hidden" name="confidential" value="1"/>
                    <button type="submit">Send</button>
                    <div>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias animi asperiores atque aut beatae consequatur cumque, dicta distinctio dolorum ea enim error expedita id illo impedit iure labore libero nesciunt numquam quas quisquam quo rem repellendus reprehenderit sed velit voluptate! Accusamus animi commodi cupiditate eligendi error facilis fugiat illum inventore, magni maxime nam neque officia quibusdam, quisquam vero? Alias consectetur cupiditate dolorum earum, est explicabo, facilis fuga inventore ipsa labore maiores minus modi mollitia natus necessitatibus nulla officia porro praesentium quam qui recusandae repudiandae tenetur ut voluptas voluptatibus. Ab aut exercitationem facilis id iste laudantium magni molestias, non officiis praesentium!
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
