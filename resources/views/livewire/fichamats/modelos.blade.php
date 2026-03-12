<div class="cardSec">
    <div class="cardSec-header">
        Modelos
    </div>
    <div class="cardSec-body" style="height: 45%; max-height: 45vh; overflow-y: auto; padding: 0;">
        <div class="table-responsive">
            <table class="table tabBase">
                <thead>
                    <tr>
                        <th>Linea</th>
                        <th>Modelo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modelos as $row)
                        <tr>
                            <td>{{ $row->Linea->linea }}</td>
                            <td>{{ $row->modelo }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">No se encontraron datos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>        
    </div>
</div>
