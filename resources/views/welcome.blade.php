<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CRUD</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>

</head>

<body class="bg-light">
    <section class="container">
        <section class="col-md-12 text-center my-5">
            <h1 class="header">CRUD TASK</h1>
        </section>
        @if (count($errors))

            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <br />
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <section class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form class='taskForm' action="{{ route('store.data') }}" method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @csrf
                                            <input type="hidden" name="task_id" id="task_id">
                                            <label for="task">Task</label>
                                            <textarea name="task" id="task" class="form-control" placeholder="Task"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="task_date">Task-Date</label>
                                            <input type="date" name="task_date" id="task_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <button type="submit" class="btn btn-outline-success">Submit</button>
                                                <button type="reset" class="btn btn-outline-warning">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="text" name="search" id="search" placeholder="Search"
                                class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-inverse table-responsive text-center">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Task</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="gridData">
                        @if (empty($data))
                            <tr>
                                <td colspan="3">No Task found</td>
                            </tr>
                        @else
                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>
                                        {{ $value['task'] }}
                                    </td>
                                    <td>
                                        {{ $value['date'] }}
                                    </td>
                                    <td>
                                        <button type="button" id="edit" data-id="{{ $value['id'] }}"
                                            class="btn btn-outline-info">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" id="delete" data-id="{{ $value['id'] }}"
                                            class="btn btn-outline-danger">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </section>

    </section>
</body>
<script>
    $(document).ready(function() {
        $(document).on('click', '#edit', function() {
            let id = $(this).data('id');
            let uri = "{{ url('edit') }}/" + id;
            $.ajax({
                type: "GET",
                url: uri,
                success: function(response) {
                    if (Object.keys(response).length > 0) {
                        $('#task_id').val(response.id);
                        $('#task').val(response.task);
                        $('#task_date').val(response.date);
                    }
                }
            });
        });
        $(document).on('click', '#delete', function() {
            let id = $(this).data('id');
            let uri = "{{ url('delete') }}/" + id;
            $.ajax({
                type: "GET",
                url: uri,
                success: function(response) {
                    location.reload();
                }
            });
        });
        $(document).on('keyup', '#search', function() {
            let uri = "{{ url('search') }}/" + $(this).val();
            let data = '';
            $.ajax({
                type: "GET",
                url: uri,
                success: function(response) {
                    if (response.length === 0) {
                        data += `<tr><td colspan='3'>No Task found</td></tr>`;
                    } else {
                        response.forEach(element => {
                            data += `<tr>
                            <td>${element.task}</td>
                            <td>${element.date}</td>
                            <td>
                                <button type="button" id="edit" data-id="${element.id}"
                                            class="btn btn-outline-info">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" id="delete" data-id="${element.id}"
                                            class="btn btn-outline-danger">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                </td>
                            </tr>`;
                        });
                    }
                    $('#gridData').html(data);
                }
            });
        });
    });
</script>

</html>
