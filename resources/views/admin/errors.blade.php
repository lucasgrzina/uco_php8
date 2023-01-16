@extends('layouts.app')

@section('content')
      <div class="error-page">

        <div class="error-content text-center" style="margin-left: 0px;margin-top: 50px;">
          <h3><i class="fa fa-exclamation-triangle text-red"></i> Lo sentimos...</h3>
          <p>{{ $error['message'] }}</p>
        </div>
      </div>
@endsection
