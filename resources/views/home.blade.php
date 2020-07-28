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
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
