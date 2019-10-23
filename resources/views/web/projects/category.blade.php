@extends('web.layout.app')
@section('content')
@include('web.partials.projects.project-list', array('projects' => $projects))
@endsection