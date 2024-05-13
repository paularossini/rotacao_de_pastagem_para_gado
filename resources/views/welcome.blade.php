<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <title>Home</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            position: relative;
            background-image: url('../img/cowback.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            
        }

        #intern-content {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
        }

        .titulo {
            background-color: white;
            padding: 0.5rem 0.5rem 0.5rem 0.5rem;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, .10);
            display: flex;
            justify-content: center;
        }

        .clear {
            font-weight: 500;
            padding: 0.65rem 1.15rem;
            background-color: #43C989;
            border-radius: 5px;
            color: black;
            text-decoration: none;
            transition: border-color 0.5s ease-in-out;
            border: 3px solid transparent;
        }

        .clear:hover {
            border-color: #236847;
            color: black;
        }

        .dark {
            padding: 0.65rem;
            background-color: #009437;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: border-color 0.5s ease-in-out;
            border: 3px solid transparent;
        }

        .dark:hover {
            border-color: #004719;
            color: white;
        }

        .button-content {
            margin-top: 2rem;
        }

        .verify {
            margin: 1rem 3rem;
            padding: 0.6rem;
            background-color: white;
            border-radius: 5px;
            font-weight: 600;
            border-color: black;
            border-width: 3px;
            border-style: solid;
            transition: background-color 0.5s ease-in-out;
        }

        .verify:hover {
            background-color: black;
            color: white;
        }

        #qttbutton {
            display: flex;
            justify-content: center;
            flex-direction: column;
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div id="intern-content">
            <div class="col-md-12 mb-4" style="margin-top: 4rem;">
                <h3 class="titulo">Rotação de Pastagem para Gado</h3>
            </div>
            <div class="col-md-6 text-center button-content">
                <a href="{{ route('lista_pastagens') }}" class="dark">Lista de Pastagens</a>
            </div>
            <div class="col-md-6 text-center button-content">
                <a href="{{ route('lista_animais') }}" class="clear">Lista de Animais</a>
            </div>
            <div>
                <form id="qttbutton" action="{{ route('simular_rotacao') }}" method="GET">
                    @csrf
                    <input id="dias" type="number" name="dias" class="form-control button-content" placeholder="Digite um numero de dias" min="1" step="1">
                    <button id="submitButton" type="submit" class="verify" disabled>Simular Rotação</button>
                </form>
            </div>
        </div>
    </div>
</body>

<!-- Bootstrap Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<script>
    document.getElementById("dias").addEventListener("input", function() {
        var diasInput = document.getElementById("dias");
        var submitButton = document.getElementById("submitButton");
        submitButton.disabled = diasInput.value.trim() === "" ? true : false;
    });
</script>

</html>