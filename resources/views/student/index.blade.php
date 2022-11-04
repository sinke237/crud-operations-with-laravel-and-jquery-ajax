<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>task9crud-std</title>
</head>
<body>
    <h4 class="text-center my-4">Name Store</h4>
    <div id="success_msg"></div>
    <div class="p-3 text-center">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add Student
        </button>
    </div>
    <!-- add modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="saveform_err"></ul>
                    <div class="d-flex flex-column">
                        <input id="name" class="mb-4" type="text" placeholder="name">
                        <input id="email" class="mb-4" type="email" placeholder="email">
                        <input id="phone" class="mb-4" type="text" placeholder="phone">
                        <input id="course" type="text" placeholder="course">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="add_student btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- edit form -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="editform_err"></ul>
                    <input type="hidden" id="edit_stud_id">
                    <div class="d-flex flex-column">
                        <input id="edname" class="mb-2" type="text" placeholder="name">
                        <input id="edemail" class="mb-2" type="email" placeholder="email">
                        <input id="edphone" class="mb-2" type="text" placeholder="phone">
                        <input id="edcourse" type="text" placeholder="course">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="update_btn btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- delete modal -->
    <div class="modal fade" id="delModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="delete_stud_id">
                    <h5>Are you sure you want to delete this student ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="del_student btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="No">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- table -->
    <div id="table" class="flex-fill p-3 mx-auto w-50 d-flex justify-content-center">
        <table class="table table-striped table-dark">
            <thead class="table-head">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Course</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <!-- <table class="table table-striped table-dark"></table> -->
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    
    $(document).ready(function(){

        // delete data from database
        $(document).on('click', '.del_btn', function(e){
            e.preventDefault();
            var stud_id = $(this).val();
            $('#delete_stud_id').val(stud_id);
            $('#delModal').modal('show');
        });
        $(document).on('click', '.del_student', function(e){
            e.preventDefault();
            $(this).text('Deleting');
            var stud_id = $('#delete_stud_id').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "delete",
                url : "delete-student/"+stud_id,
                success : function(response){
                    // console.log(response); 
                    $('#success_msg').addClass('alert alert-success');
                    $('#success_msg').text(response.message);
                    $('#delModal').modal('hide');
                    $('#del_student').text('Yes Delete');

                    fetchStudent();
                }
            });
        });

        // fetch data from database
        fetchStudent();
        function fetchStudent(){
            $.ajax({
                type: "GET",
                url : "get-students",
                dataType: "json",
                success: function (response) {
                    // console.log(response.students);
                    $('tbody').html("");
                    $.each(response.students, function(key, item){
                        $('tbody').append(
                        '<tr>\
                            <td>'+item.id+'</td>\
                            <td>'+item.name+'</td>\
                            <td>'+item.email+'</td>\
                            <td>'+item.phone+'</td>\
                            <td>'+item.course+'</td>\
                            <td><button type="button" value="'+item.id+'" class="edit_btn btn btn-primary btn-small">Edit</button></td>\
                            <td><button type="button" value="'+item.id+'" class="del_btn btn btn-danger btn-small">Delete</button></td>\
                        </tr>'
                        );
                    });
                }
            });
        }
        // edit data
        $(document).on('click', '.edit_btn', function(e){
            e.preventDefault();
            var stud_id = $(this).val();
            console.log(stud_id);
            $.ajax({
                type: "GET",
                url: "edit-student/"+stud_id,
                success: function(response){
                    console.log(response);
                    if(response.status == 404){
                        $('#success_msg').html("");
                        $('#success_msg').addClass('alert alert-danger');
                        $('#success_msg').text(response.message);
                    }else {
                        
                        $('#editModal').modal("show");
                        $('#edname').val(response.student.name);
                        $('#edemail').val(response.student.name);
                        $('#edphone').val(response.student.phone);
                        $('#edcourse').val(response.student.course);
                        $('#edit_stud_id').val(stud_id);
                    }
                }
            });
        });

        // to update data
        $(document).on('click', '.update_btn', function(e){
            e.preventDefault();
            $(this).text("Updating");
            // console.log('sinke');
            var stud_id = $('#edit_stud_id').val();
            // console.log(stud_id);
            var data = {
                'name' : $('#edname').val(),
                'email' : $('#edemail').val(),
                'phone' : $('#edphone').val(),
                'course' : $('#edcourse').val(),
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "PUT",
                url : "update-student/"+stud_id,
                data: data,
                dataType: "json",
                success : function(response){
                    console.log(response);
                    if(response.status == 400){
                        $('.editform_err').html("");
                        $('.editform_err').addClass('alert alert-danger');
                        $.each(response.errors, function(key, err_values){
                            $('.editform_err').append('<li>'+err_values+'</li>');
                        });
                        $('.update_btn').text("Update");

                    } else if (response.status == 404){
                        $('.editform_err').html('');
                        $('#success_msg').addClass('alert alert-danger');
                        $('#success_msg').text(response.message);
                        $('.update_btn').text("Update");
                    } else {
                        $('.editform_err').html('');
                        $('#success_msg').html('');
                        $('#success_msg').addClass('alert alert-success');
                        $('#success_msg').text(response.message);
                        $('#editModal').modal("hide");
                        $('.update_btn').text("Update");
                        fetchStudent();
                    }
                }
            });
        });


        // add data to database
        $(document).on('click', '.add_student', function(e){
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = {
                'name' : $('#name').val(),
                'email' : $('#email').val(),
                'phone' : $('#phone').val(),
                'course' : $('#course').val(),
            }

            $.ajax({
                method: "POST",
                url: "students",
                data: data,
                dataType: "json",
                success: function(response){
                    console.log(response);
                    if(response.status == 400){
                        $('.saveform_err').html("");
                        $('.saveform_err').addClass('alert alert-danger');
                        $.each(response.errors, function(key, err_values){
                            $('.saveform_err').append('<li>'+err_values+'</li>');
                        });
                    } else {
                        $('#success_msg').html('');
                        $('#success_msg').addClass('alert alert-success');
                        $('#success_msg').text(response.message);
                        $('#model').find('input').val('');
                        $('#exampleModal').modal('hide');
                        fetchStudent();

                    }
                }
            });
        });
    });
</script>
</body>
</html>