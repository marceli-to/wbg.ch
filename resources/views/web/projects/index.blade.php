@extends('web.layout.app')
@section('content')
@include('web.partials.projects.list', array('projects' => $projects))
@endsection