@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <h1>Data Handler Page</h1>

        <!-- Step 1: Load from URL -->
        <form id="loadForm">
            <div class="mb-3">
                <label for="url" class="form-label">Paste URL</label>
                <input type="url" class="form-control" id="url" name="url" value="{{ url('sample.json') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Load into studentData</button>
        </form>
        <div id="loadResult" class="mt-3"></div>

        <!-- Step 2: Select Target Table and Map Columns -->
        <form id="transferForm" class="mt-5" style="display: none;">
            <div class="mb-3">
                <label for="target_table" class="form-label">Select Target Table</label>
                <select id="target_table" name="target_table" class="form-select" required>
                    <option value="">Select...</option>
                </select>
            </div>
            <div id="mappingContainer" class="mb-3"></div>
            <button type="submit" class="btn btn-primary">Transfer Data</button>
        </form>
        <div id="transferResult" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load tables for dropdown
            $.get('{{ route("get-tables") }}', function(tables) {
                $('#target_table').empty().append('<option value="">Select...</option>');
                tables.forEach(table => {
                    $('#target_table').append(`<option value="${table}">${table}</option>`);
                });
            }).fail(function(err) {
                $('#loadResult').html('<div class="alert alert-danger">Error loading tables: ' + (err.responseJSON?.error || 'Unknown error') + '</div>');
            });

            // On table select, load columns and build mapping UI
            $('#target_table').change(function() {
                const table = $(this).val();
                if (!table) {
                    $('#mappingContainer').empty();
                    return;
                }

                $.get('{{ route("get-columns") }}', { table }, function(columns) {
                    $('#mappingContainer').empty();
                    columns.target.forEach(targetCol => {
                        if (targetCol === 'id' || targetCol.includes('at') || targetCol.includes('created') || targetCol.includes('updated')) return; // Skip auto-increment/timestamps
                        const html = `
                            <div class="mb-2">
                                <label>${targetCol}</label>
                                <select name="mapping[${targetCol}][]" multiple class="form-select">
                                    ${columns.source.map(src => `<option value="${src}">${src}</option>`).join('')}
                                </select>
                            </div>
                        `;
                        $('#mappingContainer').append(html);
                    });
                }).fail(function(err) {
                    $('#mappingContainer').html('<div class="alert alert-danger">Error loading columns: ' + (err.responseJSON?.error || 'Unknown error') + '</div>');
                });
            });

            // Submit load form
            $('#loadForm').submit(function(e) {
                e.preventDefault();
                $.post('{{ route("load-from-url") }}', $(this).serialize(), function(response) {
                    $('#loadResult').html(`<div class="alert alert-success">${response.message}</div>`);
                    $('#transferForm').show(); // Show next step
                }).fail(function(err) {
                    console.error('Load error:', err);
                    $('#loadResult').html(`<div class="alert alert-danger">Error: ${err.responseJSON?.error || 'Unknown error'}<br>Details: ${err.responseJSON?.details || err.statusText}</div>`);
                });
            });

            // Submit transfer form
            $('#transferForm').submit(function(e) {
                e.preventDefault();
                $.post('{{ route("transfer-data") }}', $(this).serialize(), function(response) {
                    const report = response.report;
                    $('#transferResult').html(`
                        <div class="alert alert-success">
                            Successful: ${report.successful}<br>
                            Failed: ${report.failed}<br>
                            Errors: ${report.errors.length ? report.errors.join('<br>') : 'None'}
                        </div>
                    `);
                }).fail(function(err) {
                    $('#transferResult').html(`<div class="alert alert-danger">Error: ${err.responseJSON?.error || 'Unknown error'}</div>`);
                });
            });
        });
    </script>
@endsection