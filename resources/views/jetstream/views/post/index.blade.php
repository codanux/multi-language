<div>
    @include('multi-language::links')

    @foreach($posts as $post)
        <a href="{{ routeLocalized('post.show', $post) }}">
            {{ $post }}
        </a>
    @endforeach
</div>
