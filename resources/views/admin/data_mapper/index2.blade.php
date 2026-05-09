@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>API Data Mapper</h2>

    {{-- Paste API URL --}}
    <form method="POST" action="{{ route('data_mapper.fetch_api') }}">
        @csrf
        <div class="mb-3">
            <label for="api_url" class="form-label">API URL</label>
            <input type="text" class="form-control" name="api_url" placeholder="https://api.example.com/data" required>
        </div>
        <button type="submit" class="btn btn-primary">Fetch & Create Table</button>
    </form>

    <hr>

    {{-- Mapping Form --}}
    <form method="POST" action="{{ route('data_mapper.save_mapping') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="api_table">API Table</label>
                <select name="api_table" id="api_table" class="form-select" required>
                    <option value="">-- Select API Table --</option>
                    @foreach($tables ?? [] as $table)
                        <option value="{{ $table }}">{{ $table }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="target_table">Target Table</label>
                <select name="target_table" id="target_table" class="form-select" required>
                    <option value="">-- Select Target Table --</option>
                    @foreach($tables ?? [] as $table)
                        <option value="{{ $table }}">{{ $table }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4">
            <h5>Map Columns</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>API Column</th>
                        <th>Target Column</th>
                    </tr>
                </thead>
                <tbody id="mapping-body">
                    {{-- Dynamically filled with JS --}}
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-success">Save Mapping</button>
    </form>

    <hr>

    {{-- Delete Data Form --}}
    <form method="POST" action="{{ route('data_mapper.delete_data') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="delete_api_table">Delete API Table Data</label>
                <select name="api_table" class="form-select" required>
                    <option value="">-- Select Table --</option>
                    @foreach($tables ?? [] as $table)
                        <option value="{{ $table }}">{{ $table }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="delete_target_table">Delete Target Table Data</label>
                <select name="target_table" class="form-select" required>
                    <option value="">-- Select Table --</option>
                    @foreach($tables ?? [] as $table)
                        <option value="{{ $table }}">{{ $table }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-danger mt-3">Delete Data</button>
    </form>
</div>
@endsection

@section('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    alert('Mapping');
  
    function loadColumns() {
          let apiTableSelect = document.getElementById("api_table");
    let targetTableSelect = document.getElementById("target_table");
    let mappingBody = document.getElementById("mapping-body");

        let apiTable = apiTableSelect.value;
        let targetTable = targetTableSelect.value;

        if(apiTable && targetTable){
            fetch(`/admin/datamapper/get-columns?api_table=${apiTable}&target_table=${targetTable}`)
                .then(res => res.json())
                .then(data => {
                    mappingBody.innerHTML = "";
                    if(data.api_columns && data.target_columns){
                        data.api_columns.forEach(apiCol => {
                            let row = `
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
                    }
                });
        }
    }
 function fetchColumns(table, targetDiv, fieldName) {
        if (!table) return;

        fetch(`/datamapper/get-columns?table=${table}`)
            .then(res => res.json())
            .then(data => {
                let options = `<option value="">-- Select Column --</option>`;
                data.forEach(col => {
                    options += `<option value="${col}">${col}</option>`;
                });

                document.querySelectorAll(`.${fieldName}`).forEach(select => {
                    select.innerHTML = options;
                });

                // Save the options globally for reuse
                window[fieldName + 'Options'] = options;
            })
            .catch(err => console.error(err));
    }

    document.getElementById('api_table').addEventListener('change', function () {
        fetchColumns(this.value, 'apiCols', 'apiCol');
    });

    document.getElementById('target_table').addEventListener('change', function () {
        fetchColumns(this.value, 'targetCols', 'targetCol');
    });

    document.getElementById('addMapping').addEventListener('click', function () {
        let div = document.createElement('div');
        div.classList.add('row', 'mt-2');

        div.innerHTML = `
            <div class="col-md-5">
                <select name="mapping[api][]" class="form-control apiCol">
                    ${window.apiColOptions || '<option>-- Load API Columns --</option>'}
                </select>
            </div>
            <div class="col-md-2 text-center">→</div>
            <div class="col-md-5">
                <select name="mapping[target][]" class="form-control targetCol">
                    ${window.targetColOptions || '<option>-- Load Target Columns --</option>'}
                </select>
            </div>
        `;

        document.getElementById('mapping-area').appendChild(div);
    });
    apiTableSelect.addEventListener("change", loadColumns);
    targetTableSelect.addEventListener("change", loadColumns);
});
</script>
@endsection
