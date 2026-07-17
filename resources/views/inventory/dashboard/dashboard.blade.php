@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if($role === 'Manajer Gudang')
        @include('inventory.dashboard.manager')
    @elseif($role === 'Staff Gudang')
        @include('inventory.dashboard.staff')
    @else
        @include('inventory.dashboard.admin')
    @endif
@endsection