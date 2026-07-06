@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order #' . $id)

@section('content')
    @livewire('admin.order-details', ['orderId' => $id])
@endsection 