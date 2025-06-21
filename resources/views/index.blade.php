<!doctype html>
<html lang="en">

    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    </head>

    <body>
        <div class="main m-5">
            <div class="row">
                <div class="d-flex justify-content-between align-items-center mb-5 border-bottom">
                    <h1>All Posts</h1>
                    <a href="{{ route('create.post') }}" class="btn btn-dark">Create Post</a>
                </div>
                @foreach ($posts as $post)
                    <div class="col-lg-12">
                        <div class="card p-4 mb-4">
                            <h1>{{ $post->title }}</h1>
                            <hr>
                            <p><strong>Meta Keywords</strong> : {!! $post->meta_keywords !!}</p>
                            <hr>
                            <p><strong>Meta Description</strong> : {!! $post->meta_description !!}</p>
                            <hr>
                            <p>{!! $post->description !!}</p>
                            <hr>
                            <img src="{{ asset('storage/' . $post->image) }}" width="100px" height="100px"
                                class="object-fit-cover rounded" alt="{{ $post->title }}">
                            <a href="{{ route('edit.post', $post->id) }}" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
        </script>
    </body>

</html>
