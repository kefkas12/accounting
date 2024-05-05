<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IOT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-secondary">
    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-sm-3">
                <div class="card mt-5">
                    <h5 class="card-header text-center">Jarak</h5>
                    <div class="card-body text-center">
                        <h5 class="card-title" id="jarak">0 CM</h5>
                        <p id="message">Loading . . .</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        $( document ).ready(function() {
            setInterval(jarak, 1000);
        });

        function jarak(){
            $.ajax({
                url: "{{ url('api/iot/jarak') }}",
                type: 'GET',
                success: function(res) {
                    console.log(res.jarak[0].jarak)
                    $('#jarak').html(res.jarak[0].jarak+" CM")
                    if(res.jarak[0].jarak < 10){
                        $('#message').addClass('text-danger');
                        $('#message').html('Jarak Terlalu Dekat')
                    }else{
                        $('#message').removeClass('text-danger');
                        $('#message').html('Jarak Optimal')
                    }
                }
            });
        }
    </script>
</body>

</html>
