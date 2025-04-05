<x-app-layout>
    

<div class="table-container">
    <table class="table is-fullwidth is-bordered">
        <tbody>
            <!-- Información del perro principal -->
            <tr>
                <td style="width: 30%; height: auto;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/000_American_Pit_Bull_Terrier.jpg/1024px-000_American_Pit_Bull_Terrier.jpg" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                </td>
                <td colspan="6">
                    <h1 class="title">Pedigree de {{ $dog->name }}</h1>
                    <div><strong>Reg.No:</strong> {{ $dog->reg_no }}</div>
                    <div><strong>OWNER:</strong></div>
                    <div><strong>Sex:</strong> {{ $dog->sex }}</div>
                    <div><strong>Date of Birth:</strong> {{ $dog->birthdate }}</div>
                    <div><strong>Generation Pedigree:</strong> First</div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="has-text-centered">Generación Pedigree</div>
                </td>
            </tr>
            <!-- Encabezado de la tabla -->
            <tr>
                <th>Generación</th>
                <th>Nombre</th>
                <th>Raza</th>
                <th>Color</th>
                <th>Sexo</th>
                <th>Padre</th>
                <th>Madre</th>
            </tr>
            <!-- Mostrar ancestros -->
            @foreach ($pedigree as $index => $ancestor)
                @if( $index > 0 ) <!-- Excluir al perro principal de la lista -->
                    <tr>
                        <td>{{ $ancestor->generation }}</td>
                        <td><strong>{{ $ancestor->name }}</strong></td>
                        <td>{{ $ancestor->breed }}</td>
                        <td>{{ $ancestor->color }}</td>
                        <td>{{ $ancestor->sex }}</td>
                        <!-- Padre -->
                        <td>
                            @if ($ancestor->sire_id)
                                {{ optional(collect($pedigree)->firstWhere('dog_id', $ancestor->sire_id))->name ?? 'Desconocido' }}
                            @else
                                Desconocido
                            @endif
                        </td>
                        <!-- Madre -->
                        <td>
                            @if ($ancestor->dam_id)
                                {{ optional(collect($pedigree)->firstWhere('dog_id', $ancestor->dam_id))->name ?? 'Desconocido' }}
                            @else
                                Desconocido
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>


</x-app-layout>
