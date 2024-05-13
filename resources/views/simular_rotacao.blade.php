<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <title>Rotação</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        h4 {
            font-size: 1.3rem;
        }

        body {
            position: relative;
            background-image: url('../img/pasto.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        #intern-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .titulo {
            margin-top: 4rem;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, .10);
            display: flex;
            justify-content: center;
            width: 21rem;
        }

        .titulo_menor {
            margin-top: 4rem;
            background-color: #004719;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            width: 25rem;
            color: white;
        }

        table {
            margin: 0 auto;
            width: 80%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #009437;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #009437;
        }

        tr:hover {
            background-color: #bcbcbc;
        }

        .return {
            padding: 0.6rem 2rem;
            background-color: #d2d2d2;
            border-radius: 5px;
            font-weight: 600;
            border-color: #d2d2d2;
            border-width: 3px;
            border-style: solid;
            text-decoration: none;
            color: black;
            transition: background-color 0.5s ease-in-out;
        }

        .return:hover {
            background-color: gray;
            border-color: gray;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 2rem;
            border: 2px solid #000;
            width: 30%;
            border-radius: 15px;
        }

        .close {
            color: #aaa;
            display: flex;
            font-size: 28px;
            margin-right: 1rem;
            font-weight: bold;
            flex-direction: row-reverse;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #save {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div id="intern-content">
            <div class="col-md-12 mb-4 titulo">
                <h3 style="margin-top: 0.8rem;">Simulação de Rotação</h3>
            </div>
            <div style="margin-bottom: 2rem; margin-top:2rem;">
                <a href="{{ route('home') }}" class="return">Voltar</a>
            </div>

            @if (!empty($errorMessages))
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errorMessages as $errorMessage)
                    <li>{{ $errorMessage }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($planoRotacao->isEmpty())
            <p style="color: red; font-weight: 500; background-color: #2a322ad6; padding: 0.3rem;">Nenhuma informação encontrada.</p>
            @else
            @php
            $ultimoDia = null; // Inicializa a variável para armazenar o último dia
            $disposicaoInicialExibida = false; // Controla se a disposição inicial já foi exibida
            @endphp

            @foreach($planoRotacao as $rotacao)
            @if($rotacao->id == 1 && !$disposicaoInicialExibida)
            <div class="titulo_menor">
                <h4 style="margin-top: 0.8rem;">Disposição inicial dos animais</h4>
            </div>
            <table class="table">
                <thead>
                    <tr style="background-color: #004719; color: #fefefe">
                        <th>ID Pastagem</th>
                        <th>Forragem disponível</th>
                        <th>Quantidade de animais</th>
                        <th>ID Animal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $disposicaoInicialExibida = true; // Marca que a disposição inicial foi exibida
                    @endphp
                    @endif

                    @if($rotacao->dia != $ultimoDia)
                </tbody>
            </table>
            <br>
            <div class="titulo_menor">
                <h4 style="margin-top: 0.8rem;">Disposição dos animais - Dia {{ $rotacao->dia }}</h4>
            </div>
            <table class="table">
                <thead>
                    <tr style="background-color: #004719; color: #fefefe">
                        <th>ID Pastagem</th>
                        <th>Forragem disponível</th>
                        <th>Quantidade de animais</th>
                        <th>ID Animal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $ultimoDia = $rotacao->dia;
                    @endphp
                    @endif

                    <tr style="background-color: #fefefe;">
                        <td style="position: relative;">
                            <div style="position: relative; display: inline-block;">
                                <img src="{{ asset('../img/grama.png') }}" alt="Imagem" style="width: 50px; height: auto; margin: .5rem; position: relative; opacity: 0.5;">
                                <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: black; font-weight: bold;     font-size: x-large;">{{ $rotacao->pastagem_id }}</span>
                            </div>
                        </td>

                        <td style="color: black; font-weight: bold;font-size: x-large; padding-top: 1.5rem;">{{ $rotacao->forragem_disponivel }} %</td>

                        <td style="position: relative;">
                            <div style="position: relative; display: inline-block;">
                                <img src="{{ asset('../img/silhueta-de-vaca.png') }}" alt="Imagem" style="width: 50px; height: auto; margin: .5rem; position: relative;opacity: 0.5;">
                                <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: black; font-weight: bold;     font-size: x-large;">{{ $rotacao->qtd_animal }}</span>
                            </div>
                        </td>

                        <td style="font-weight: bold; font-size: x-large; padding-top: 1.5rem; color: {{ ($rotacao->animais === '[]' || $rotacao->animais === '') ? 'red' : 'black' }}">
                            @if ($rotacao->animais === '[]')
                            Pastagem em recuperação
                            @else
                            {{ str_replace(['[', ']'], '', $rotacao->animais) }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</body>

<!-- Bootstrap Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</html>