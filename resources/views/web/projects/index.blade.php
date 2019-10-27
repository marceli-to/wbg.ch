@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', '')
@section('content')
@include('web.partials.projects.list', array('projects' => $projects))
@endsection