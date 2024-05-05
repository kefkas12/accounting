<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IOT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Suhu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ url('cloud/suhu') }}">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="card text-bg-dark">
                    <img src="" class="card-img" id="image">
                    <div class="card-img-overlay">
                        <h5 class="card-title text-center"><b>Suhu dan Kelembaban</b></h5>
                        <p class="card-text mt-4">Suhu : <span id="suhu">0</span> C</p>
                        <p class="card-text">Kelembaban : <span id="kelembaban">0</span> RH</p>
                        <p class="card-text"><small>Last updated <span id="updated_at"></span></small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            setInterval(suhu, 2000);
        });

        function suhu() {
            $.ajax({
                url: "{{ url('api/iot/suhu') }}",
                type: 'GET',
                success: function(res) {
                    console.log(res.suhu[0].suhu)
                    $('#suhu').html(res.suhu[0].suhu)
                    $('#kelembaban').html(res.suhu[0].kelembaban)

                    const updated_at = res.suhu[0].updated_at;
                    const date_obj = new Date(updated_at);

                    $('#updated_at').html(date_obj)
                    if (res.suhu[0].suhu < 20) {
                        $('#image').attr('src', '{{ asset('image/ice.jpg') }}');
                    } else if (res.suhu[0].suhu < 30) {
                        $('#image').attr('src', '{{ asset('image/warm.jpg') }}');
                    } else {
                        $('#image').attr('src', '{{ asset('image/hot.jpg') }}');
                    }
                }
            });
        }
    </script>
</body>

</html>
