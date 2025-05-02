<x-app-layout>

<div class="container">
    <h2 class="title">Registrar Cachorros de </h2>

    <div class="field">
        <label class="label">Cantidad de Cachorros</label>
        <div class="control">
            <input id="puppiesQuantity" class="input" type="number" min="1" placeholder="¿Cuántos cachorros nacieron?" oninput="generatePuppyInputs()">
        </div>
    </div>

    <div id="puppyNamesContainer" class="mt-4"></div>

    <div class="field mt-4">
        <p><strong>Precio por cachorro:</strong> $100 pesos</p>
        <p><strong>Total a pagar:</strong> $<span id="totalPrice">0</span> pesos</p>
    </div>

    <div class="field mt-4">
        <button id="payAndRegisterButton" class="button is-success" onclick="payAndRegister()" disabled>Pagar y Registrar Cachorros</button>
    </div>
</div>



@push('scripts')
<script>
    function generatePuppyInputs() {
        const quantity = parseInt(document.getElementById('puppiesQuantity').value);
        const container = document.getElementById('puppyNamesContainer');
        container.innerHTML = '';
        document.getElementById('totalPrice').innerText = quantity * 100;

        if (quantity > 0) {
            for (let i = 0; i < quantity; i++) {
                const field = document.createElement('div');
                field.className = 'field';
                field.innerHTML = `
                    <label class="label">Nombre del Cachorro ${i + 1}</label>
                    <div class="control">
                        <input type="text" class="input puppy-name" placeholder="Nombre del cachorro ${i + 1}" required>
                    </div>
                `;
                container.appendChild(field);
            }

            document.getElementById('payAndRegisterButton').disabled = false;
        } else {
            document.getElementById('payAndRegisterButton').disabled = true;
        }
    }


</script>
@endpush
</x-app-layout>