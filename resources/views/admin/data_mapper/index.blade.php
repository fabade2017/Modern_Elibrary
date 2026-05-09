
@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>API Data Mapping </h2>
    <h3>{{$school}} </h3>
</h4>{{ $school_domain }}</h4>
<hr/>
    {{-- Form to paste API URL --}}
    <form action="{{ route('data_mapper.fetch_api') }}" method="POST" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="api_url">API URL</label>


            <input  type="text" name="api_url" id="api_url" class="form-control" placeholder="Paste API URL here" value="{{$superdomain}}" required>
         
        </div>
        <button type="submit" class="btn btn-primary mt-2">Fetch & Create Table</button>
    </form>

@if(Auth::user()->role_id === 1) <!-- -->
    {{-- Mapping form --}}
    <form action="{{ route('data_mapper.save_mapping') }}" method="POST" id="mappingForm">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="api_table">Select API Table</label>
                <select name="api_table" id="api_table" class="form-control">
                    <option value="">-- Select --</option>
                    @foreach($tables as $table)
                        <option value="{{ $table }}">{{ $table }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="target_table">Select Target Table</label>
                <select name="target_table" id="target_table" class="form-control">
                    <option value="">-- Select --</option>
                    @foreach($tables as $table)
                        <option value="{{ $table }}">{{ $table }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr>

        <h4>Field Mapping</h4>
        <div id="mapping-area" class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>API Column</th>
                        <th>Target Column</th>
                    </tr>
                </thead>
                <tbody id="mapping-body">
                    <tr><td colspan="2">Select API and Target tables to load columns</td></tr>
                </tbody>
            </table>
        </div>

        <button type="button" id="addMapping" class="btn btn-secondary mt-2">Add Mapping</button>
        <button type="submit" class="btn btn-success mt-2">Save Mapping</button>
    </form>

    <form action="{{ route('data_mapper.delete_data') }}" method="POST" class="mt-4">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="delete_api_table">API Table</label>
                <select name="api_table" class="form-control">
                    @foreach($tables as $table)
                        <option value="{{ $table }}">{{ $table }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="delete_target_table">Target Table</label>
                <select name="target_table" class="form-control">
                    @foreach($tables as $table)
                        <option value="{{ $table }}">{{ $table }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-danger mt-3">Delete Data</button>
    </form>
     @endif <!---->
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const apiTableSelect = document.getElementById("api_table");
    const targetTableSelect = document.getElementById("target_table");
    const mappingBody = document.getElementById("mapping-body");
    const addMappingBtn = document.getElementById("addMapping");

    function loadColumns() {
        const apiTable = apiTableSelect.value;
        const targetTable = targetTableSelect.value;

        if (!apiTable || !targetTable) {
            mappingBody.innerHTML = '<tr><td colspan="2">Please select both API and Target tables</td></tr>';
            return;
        }

        mappingBody.innerHTML = '<tr><td colspan="2">Loading columns...</td></tr>';

        const getColumnsUrl = "{{ route('data_mapper.getColumns') }}";

    fetch(`${getColumnsUrl}?api_table=${encodeURIComponent(apiTable)}&target_table=${encodeURIComponent(targetTable)}`, {

            headers: { 'Accept': 'application/json' }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}, StatusText: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Columns fetched:', data);
                mappingBody.innerHTML = "";
                if (data.api_columns && data.target_columns && data.api_columns.length && data.target_columns.length) {
                    data.api_columns.forEach(apiCol => {
                        const row = `
                            <tr>
                                <td>${apiCol}</td>
                                <td>
                                    <select name="mappings[${apiCol}]" class="form-select">
                                        <option value="">-- Select Target Column --</option>
                                        ${data.target_columns.map(tc => `<option value="${tc}">${tc}</option>`).join('')}
                                    </select>
                                </td>
                            </tr>
                        `;
                        mappingBody.innerHTML += row;
                    });
                    // Store options for addMapping
                    window.apiColOptions = `<option value="">-- Select API Column --</option>` + data.api_columns.map(col => `<option value="${col}">${col}</option>`).join('');
                    window.targetColOptions = `<option value="">-- Select Target Column --</option>` + data.target_columns.map(col => `<option value="${col}">${col}</option>`).join('');
                } else {
                    mappingBody.innerHTML = '<tr><td colspan="2">No columns available for selected tables</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching columns:', error.message);
                mappingBody.innerHTML = `<tr><td colspan="2">Error loading columns: ${error.message}</td></tr>`;
            });
    }

    apiTableSelect.addEventListener('change', loadColumns);
    targetTableSelect.addEventListener('change', loadColumns);

    addMappingBtn.addEventListener('click', function () {
        if (!window.apiColOptions || !window.targetColOptions) {
            mappingBody.innerHTML = '<tr><td colspan="2">Please select tables to load columns first</td></tr>';
            return;
        }
        const div = document.createElement('tr');
        div.innerHTML = `
            <td>
                <select name="mapping[api][]" class="form-control apiCol">
                    ${window.apiColOptions}
                </select>
            </td>
            <td>
                <select name="mapping[target][]" class="form-control targetCol">
                    ${window.targetColOptions}
                </select>
            </td>
        `;
        mappingBody.appendChild(div);
    });

    // Initial load
    loadColumns();
});
</script>
