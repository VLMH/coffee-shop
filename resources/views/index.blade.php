@extends('layout')

@section('title', 'Coffee Shop')

@section('content')
  <payment-form></payment-form>
@endsection

<script>
  window.Laravel = { 'braintreeAuth': '{{ $braintreeAuth }}' }
</script>
