@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
    @livewire('admin.user-details', ['userId' => $id])
@endsection 