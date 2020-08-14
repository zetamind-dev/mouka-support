<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mouka Support</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @yield('external-css')
</head>

<body>
    <div id="app">

        @yield('navigation')

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('article-ckeditor');
    </script>
    <script>
        $('#deptCategory').on('change', function (e) {
            console.log(e);

            var dept_id = e.target.value;

            //ajax
            $.get('/ajax-category?dept_id=' + dept_id, function (data) {
                //success
                // console.log(data);
                $('#category').empty();
                $.each(data, function (index, categoryObj) {
                    if (categoryObj.name != 'All') {
                        $('#category').append('<option value="' + categoryObj.id + '">' +
                            categoryObj.name + '</option>');

                    }
                });

            });
        });


        $('#deptReport').on('change', function (e) {
            console.log(e);

            var dept_id = e.target.value;

            //ajax
            $.get('/ajax-category?dept_id=' + dept_id, function (data) {
                //success
                // console.log(data);
                $('#report').empty();
                $.each(data, function (index, categoryObj) {
                    if (categoryObj.name === 'All') {
                        var value = 'all';
                        $('#report').append('<option value="' + value + '">' +
                            categoryObj.name + '</option>');

                    } else {
                         $('#report').append('<option value="' + categoryObj.id + '">' +
                             categoryObj.name + '</option>');
                    }
                });

            });
        });


        $('#deptModerator').on('change', function (e) {
            console.log(e);

            var dept_id = e.target.value;

            //ajax
            $.get('/ajax-moderator?dept_id=' + dept_id, function (data) {
                //success
                // console.log(data);
                $('#moderator').empty();
                $.each(data, function (index, userObj) {
                    $('#moderator').append('<option value="' + userObj.email + '">' +
                        userObj.name + '</option>');

                });

            });
        });
    </script>

</body>

</html>