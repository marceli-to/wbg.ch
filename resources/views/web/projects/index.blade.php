@extends('web.layout.app')
@section('seo_title', $pageTitle)
@section('seo_description', 'Signaletik/Orientierungs- und Leitsysteme, Logo- und Markenentwicklung, Editorial-Design und Buchgestaltung, Kommunikationsmittel, Website-Design, Archiv')
@section('content')
@include('web.partials.projects.list', array('projects' => $projects))
@endsection