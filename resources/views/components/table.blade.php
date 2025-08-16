<div class="dt-responsive table-responsive">
    <table id="{{ $datatable_class }}" class="table nowrap datatable {{ $datatable_class }}">
        <thead>
            <tr>
                @forelse ($keys as $key)
                    <th>{{ $key}}</th>    
                @empty
                    
                @endforelse
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>