<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title>Browse</title>
</head>

<body class="container-fluid">
    <div class="row justify-content-end mb-2">
        <div class="col-sm-6">
            <form action="{{ Request::url() }}">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2" name="search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="button-addon2">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Telepon</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Email</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($konsumen as $v)
                <tr id="{{ $v->id }}">
                    <td class="konsumen">{{ $v->nama }}</td>
                    <td>{{ $v->telepon_1 }} / {{ $v->telepon_2 }}</td>
                    <td>{{ $v->alamat }}</td>
                    <td>{{ $v->email }}</td>
                    <td id="button_{{ $v->id }}"><button type="button" onclick="post_value({{ $v->id }})">Choose</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end">
        {{ $konsumen->withQueryString()->links() }}
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <script>
        function post_value(id) {
            $('#button_' + id).empty();
            $('#button_' + id).append('<button class="btn btn-primary" type="button" disabled> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading... </button>');
            var data = $('#' + id);
            window.opener.$('#id_konsumen').val(id);
            window.opener.$('#konsumen').val(data.find('.konsumen').text());
            window.opener.$('#konsumen').prop('readonly', true);
            self.close();
        }
    </script>
</body>

</html>