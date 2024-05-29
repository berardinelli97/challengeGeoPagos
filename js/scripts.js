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

        jugadorSelect.addEventListener('change', validateUniqueSelection);
    }
}

async function loadPlayersByGender(quantity, gender) {
    var responseData;
    try {
        const response = await fetch('controllers/playerController.php?action=getPlayersByGender', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cantidadJugadores: quantity, genero: gender })
        });
        if (!response.ok) {
            throw new Error('Error al cargar los jugadores por género');
        }

        responseData = await response.json();

    } catch (error) {
        console.error('Error:', error.message);
    }

    return responseData;
}

async function clearSelects(quantity) {
    for (let i = 1; i <= quantity; i++) {
        const select = document.getElementById(`jugador${i}`);
        if (select) {
            select.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = `Seleccionar Jugador ${i}`;
            defaultOption.disabled = true;
            defaultOption.selected = true;
            select.appendChild(defaultOption);
        }
    }
}

async function loadSelects(players, quantity) {
    for (let i = 1; i <= quantity; i++) {
        const select = document.getElementById(`jugador${i}`);
        if (!select) continue;
        for (const jugador of players) {
            const option = document.createElement('option');
            option.value = jugador.id;
            option.textContent = jugador.name;
            select.appendChild(option);
        }
    }
}

function validateUniqueSelection() {
    const selects = document.querySelectorAll('#selectJugadores select');
    const values = [];

    selects.forEach(select => {
        const selectedValue = select.value;
        if (selectedValue) {
            if (values.includes(selectedValue)) {
                select.setCustomValidity('Este jugador ya ha sido seleccionado');
                select.classList.add('is-invalid');
            } else {
                values.push(selectedValue);
                select.setCustomValidity('');
                select.classList.remove('is-invalid');
            }
        } else {
            select.setCustomValidity('');
            select.classList.remove('is-invalid');
        }
    });
}

function updatePlayersId() {
    const selects = document.querySelectorAll('#selectJugadores select');
    const playersId = Array.from(selects).map(select => select.value).filter(value => value !== '');
    document.getElementById('jugadores').value = JSON.stringify(playersId);
}

