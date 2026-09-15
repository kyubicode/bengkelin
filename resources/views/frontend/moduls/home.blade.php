@extends('frontend.layout')

@section('meta_title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('content')
    <div class="w-full">
        @foreach($page->content ?? [] as $block)
            @includeIf('frontend.blocks.'.$block['type'], ['data' => $block['data'] ?? []])
        @endforeach
    </div>
@endsection