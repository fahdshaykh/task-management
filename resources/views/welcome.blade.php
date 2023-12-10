<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 9 Drag and Drop Reorder tasks with jQuery</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="text-right mt-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal" data-whatever="@mdo">Add Task</button>
        </div>

        <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">New Task</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Project</label>
                        <select class="form-control" name="project_id" aria-label="Default select example" required>
                            @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Task Name</label>
                      <input type="text" class="form-control" name="task_name" id="recipient-name" required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Priority</label>
                        <input type="number" class="form-control" name="priority" id="recipient-name" required>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
                </form>
              </div>
            </div>
        </div>

        <a href="{{ url('/') }}">
            <h4 style="text-align: center;">Task Management</h4>
        </a>

        <div class="col-md-12 ml-4">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
        </div>

        <div class="col-md-6 form-group ml-4">
            <label for="recipient-name" class="col-form-label">Project</label>
            <select class="form-control" id="project_filter" aria-label="Default select example" required>
                @foreach ($projects as $project)
                <option value="{{ $project->id }}" @if($project->id == $project_id) selected @endif>{{ $project->project_name }}</option>
                @endforeach
            </select>
        </div>
    
        <ul id="sortable">
            @if (count($tasks) > 0)
                @foreach ($tasks as $row)
                <li class="ui-state-default mt-2" id="<?php echo $row->id; ?>" style="height: 40px;"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    {{  $row->task_name }}
                    <span class="float-right">
                        <button type="button" class="btn edit_task_btn" data-toggle="modal" data-target="#taskEditModal" data-task_id="{{ $row->id }}" data-url="{{ route('tasks.edit', $row->id) }}"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn delete_task_btn" data-task_id="{{ $row->id }}"><i class="fa fa-trash"></i></button>
                        {{-- <a href="{{ route('tasks.edit', $row->id) }}">Edit</a> --}}
                    </span>
                </li>
                @endforeach
            @endif
        </ul>
    </div>

    <div class="modal fade" id="taskEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Task</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="update_task_form" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Project</label>
                    <select class="form-control" name="project_id" id="edit_project_id" aria-label="Default select example" required>
                        @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Task Name</label>
                  <input type="text" class="form-control" name="task_name" id="edit_task_name" required>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Priority</label>
                    <input type="number" class="form-control" name="priority" id="edit_task_priority" required>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">update Task</button>
            </div>
            </form>
          </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script
    src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"
    integrity="sha256-xH4q8N0pEzrZMaRmd7gQVcTZiFei+HfRTBPJ1OGXC0k="
    crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#sortable").sortable({
                update: function(event, ui) {
                    updateOrder();
                }
            });

            $(".edit_task_btn").click(function () {
                var task_id = $(this).data("task_id");
                var userURL = $(this).data('url');

                $('#edit_task_name').val('');
                $('#edit_task_priority').val();

                $('#update_task_form').attr('action', "{{ url('tasks/update') }}"+'/'+task_id);

                $.ajax({
                    url: userURL,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#taskEditModal').modal('show');
                        // $('#user-id').text(data.id);
                        $('#edit_project_id').val(data.project_id);
                        $('#edit_task_name').val(data.task_name);
                        $('#edit_task_priority').val(data.priority);
                        
                    }
                });

                // $("#taskEditModal").modal("show");
            });

            $('body').on('click', '.delete_task_btn', function () {
     
                var task_id = $(this).data("task_id");
                confirm("Are You sure want to delete !");
                
                $.ajax({
                    type: "post",
                    url: "{{ url('tasks/delete') }}"+'/'+task_id,
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });

        function updateOrder() {
            var item_order = new Array();
            $('#sortable li').each(function() {
                item_order.push($(this).attr("id"));
            });
            var order_string = 'order=' + item_order;
            $.ajax({
                type: "POST",
                url: "{{ route('update-order') }}",
                data: order_string,
                cache: false,
                success: function(data) {}
            });
        }
    </script>

<script>
    document.getElementById('project_filter').onchange = function () {
        window.location = "/?project_id=" + this.value;
    };    
</script>
</body>

</html>