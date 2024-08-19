<table>
    <thead>
        <tr>
            <th>NIS</th>
            <th>Nama</th>
            <th>Kelas</th>

            @forelse (json_decode($jenjang->column, true) ?? [] as $key => $value)
                <th>{{ $value['key'] }}</th>
            @empty

            @endforelse
        </tr>
    </thead>
</table>
