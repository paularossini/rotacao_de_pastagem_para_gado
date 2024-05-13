<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/home.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <title>Animais</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
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
        #intern-content{
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .titulo{
            margin-top: 4rem;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, .10);
            display: flex;
            justify-content: center;
            width: 19rem;
        }
        table {
            margin: 0 auto; 
            width: 80%;
            border-collapse: collapse;
        }
        th, td {
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
        .verify {
            padding: 0.6rem 2rem;
            background-color: #00ff89;
            border-radius: 5px;
            font-weight: 600;
            border-color: #00ff89;
            border-width: 3px;
            border-style: solid;
            text-decoration: none;
            color: black;
            transition: background-color 0.5s ease-in-out;
        }
        .verify:hover {
            background-color: greenyellow;
            border-color: greenyellow;
            color: black;
        }
        .return {
            margin-right: 3rem;
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
        #save{
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
                <img src="{{ asset('../img/silhueta-de-vaca.png') }}" alt="Imagem" style="width: 30px; height: auto; margin: .5rem;">
                <h3 style="margin-top: 0.8rem;">Lista de Animais</h3>
                <img src="{{ asset('../img/silhueta-de-vaca.png') }}" alt="Imagem" style="width: 30px; height: auto; margin:.5rem;">
            </div>
            <div style="margin-bottom: 2rem; margin-top:2rem;">
                <a href="{{ route('home') }}" class="return">Voltar</a>
                <a href="#" class="verify" id="openModal">Incluir</a>
            </div>

            @if($animais->isEmpty())
            <p style="color: red;font-weight: 500;background-color: #2a322ad6;padding: 0.3rem;">Nenhuma informação encontrada.</p>
            @else
            <table class="table">
                <thead>
                    <tr style="background-color: #004719; color:#fefefe">
                        <th >ID</th>
                        <th>Peso(Kg)</th>
                        <th>Idade(mês)</th>
                        <th>Necessidade nutricional(Kg)</th>
                        <th>Pastagem atual</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($animais as $animal)
                    <tr style="background-color: #fefefe;">
                        <td>{{ $animal->id }}</td>
                        <td>{{ $animal->peso }}</td>
                        <td>{{ $animal->idade }}</td>
                        <td>{{ $animal->necessidade_nutricional }}</td>
                        <td>{{ $animal->pastagem_atual }}</td>
                        <td>
                            <a href="#" class="btn btn-secondary edit-btn" data-id="{{ $animal->id }}" onclick="openEditModal({{ $animal }})"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('delete_animal', $animal->id) }}" method="post" style="display: inline;">                        
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este animal?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form  id="formSalvarAnimal" action="{{ route('store_animal') }}"  method="POST">
                @csrf
                <div class="form-group">
                    <label for="peso">Peso(Kg):</label>
                    <input type="number" class="form-control" id="peso" name="peso" min="1" step="0.01" required>
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="idade">Idade(mês):</label>
                    <input type="number"  min="1"  class="form-control" id="idade" name="idade" required>
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="necessidade_nutricional">Nessecidade nutricional(Kg):</label>
                    <input type="number" class="form-control" id="necessidade_nutricional" name="necessidade_nutricional" min="1" step="0.01" required>
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="pastagem_atual">Pastagem atual:</label>
                    <select class="form-control" id="pastagem_atual" name="pastagem_atual" required>
                        <option value="">Selecione a pastagem</option>
                        @foreach($pastagens as $pastagem)
                            <option value="{{ $pastagem->id }}">{{ $pastagem->id }} - ({{ $pastagem->quantidade_forragem }} kg de forragem)</option>
                        @endforeach
                    </select>
                </div>

                <div id="save"> 
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="myModalEdit" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form action="{{ isset($animal) ? route('update_animal', $animal->id) : '#' }}" method="POST" id="editAnimalForm">
                @csrf
                @method('PUT')
                @isset($animal)
                <div class="form-group">
                    <label for="peso">Peso(Kg):</label>
                    <input type="number" class="form-control" id="peso" name="peso" min="1" step="0.01" value="{{ $animal->peso }}" required>
                </div>
                <div class="form-group">
                    <label for="idade">Idade(mês):</label>
                    <input type="number" min="1" class="form-control" id="idade" name="idade" value="{{ $animal->idade }}" required>
                </div>
                <div class="form-group">
                    <label for="edit_necessidade_nutricional">Nessecidade nutricional(Kg):</label>
                    <input type="number" class="form-control" id="edit_necessidade_nutricional" name="necessidade_nutricional" min="1" step="1" value="{{ $animal->necessidade_nutricional }}" required>
                </div>
                <div class="form-group">
                    <label for="pastagem_atual">Pastagem atual:</label>
                    <select class="form-control" id="pastagem_atual" name="pastagem_atual" required>
                        <option value="">Selecione a pastagem</option>
                        @foreach($pastagens as $pastagem)
                            <option value="{{ $pastagem->id }}" @if($animal->pastagem_atual == $pastagem->id) selected @endif>{{ $pastagem->id }} - ({{ $pastagem->quantidade_forragem }} kg de forragem)</option>
                        @endforeach
                    </select>
                </div>

                @endisset
                <div id="save"> 
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>

<script>
    var modal = document.getElementById("myModal");
    var modalEdit = document.getElementById("myModalEdit");
    var link = document.getElementById("openModal");
    var span = document.getElementsByClassName("close")[0];
    var spanEdit = document.querySelector("#myModalEdit .close");

    link.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    spanEdit.onclick = function() {
        modalEdit.style.display = "none";
    }

    function openEditModal(animal) {
        document.getElementById('peso').value = animal.capacidade_suporte;
        document.getElementById('idade').value = animal.quantidade_forragem;
        document.getElementById('edit_necessidade_nutricional').value = animal.necessidade_nutricional;
        var editForm = document.getElementById('editAnimalForm');
        editForm.action = "/animais/" + animal.id; 
        modalEdit.style.display = "block";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        if (event.target == modalEdit) {
            modalEdit.style.display = "none";
        }
    }
</script>
</body>
</html>