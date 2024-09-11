@extends('frontend.frontend-page-master')
@section('content')
    @include('frontend.partials.pages-portion.dynamic-page-builder-part', ['page_post' => $page_details])
@endsection
@section('left_side_content')
    {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_left_sidebar', $page_details->id) !!}
@endsection
@section('right_side_content')
    {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_right_sidebar', $page_details->id) !!}
@endsection


<h2>This is dummy folder</h2>
<h3>Now succesfully push</h3>