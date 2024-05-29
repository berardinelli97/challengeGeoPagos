<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torneo de Tenis</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" src="src/css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/scripts.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Torneo de Tenis</h1>
        <div id="mensajeCampeon" class="alert alert-success" style="display: none;"></div>
        <form id="torneoForm" class="mt-4" method="POST">
            <div class="form-group">
                <label for="cantidadJugadores">Cantidad de Jugadores</label>
                <input type="number" class="form-control" id="cantidadJugadores" name="cantidadJugadores" min="2" required>
            </div>
            <div class="form-group">
                <label for="genero">Género</label>
                <select class="form-control" id="genero" name="genero" required>
                    <option value="" disabled selected>Seleccione género</option>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                </select>
            </div>
            <div class="form-group">
                <label for="selectJugadores">Seleccionar Jugadores</label>
                <div id="selectJugadores">
                    <!-- Campos para seleccionar jugadores se añadirán aquí -->
                </div>
            </div>
            <input type="hidden" name="playersId" id="jugadores" value="">
            <input type="hidden" name="action" value="createTournament">
            <button type="submit" id="crearTorneo" class="btn btn-primary">Crear Torneo</button>
        </form>
    </div>

    <script>
        document.getElementById('cantidadJugadores').addEventListener('change', async function() {
            const quantity = parseInt(this.value);
            await createSelects(quantity);
        });

        document.getElementById('genero').addEventListener('change', async function() {
            var quantityPlayers = parseInt(document.getElementById('cantidadJugadores').value);
            var gender = document.getElementById('genero').value;
            var jugadores = await loadPlayersByGender(quantityPlayers, gender);
            await clearSelects(quantityPlayers);
            await loadSelects(jugadores, quantityPlayers);
        });

        document.getElementById('torneoForm').addEventListener('submit', function(event) {
            event.preventDefault(); 
            updatePlayersId();

            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'controllers/tournamentController.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.message) {
                        var message = 'Campeon del torneo: '+response.message;
                        $('#mensajeCampeon').text(message).show();
                    }
                },
                error: function() {
                    alert('Error al crear el torneo');
                }
            });
        });

        async function createSelects(quantity) {
            const jugadoresDiv = document.getElementById('selectJugadores');
            jugadoresDiv.innerHTML = '';
            for (let i = 1; i <= quantity; i++) {
                const jugadorSelect = document.createElement('select');
                jugadorSelect.className = 'form-control mb-2';
                jugadorSelect.name = `jugador${i}`;
                jugadorSelect.id = `jugador${i}`;
                jugadorSelect.required = true;

                const optionPlaceholder = document.createElement('option');
                optionPlaceholder.value = '';
                optionPlaceholder.textContent = `Seleccionar Jugador ${i}`;
                optionPlaceholder.disabled = true;
                optionPlaceholder.selected = true;
                jugadorSelect.appendChild(optionPlaceholder);

                jugadoresDiv.appendChild(jugadorSelect);

                jugadorSelect.addEventListener('change', () => {
                    validateUniqueSelection();
                    updatePlayersId();
                });
            }
        }

    </script>
</body>
</html>
