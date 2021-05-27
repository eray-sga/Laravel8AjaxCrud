<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Laravel 8 Ajax Crud</title>
    <link rel="stylesheet" href="{{asset('css')}}/app.css">
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/sweetalert.js')}}"></script>
</head>
<body>
    <div style="padding: 30px;"></div>
    <div class="container">
        <h2 style="color: red;">Laravel 8 Ajax Crud</h2>
        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header">
                        All Teacher
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Title</th>
                                <th scope="col">Institute</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              {{-- <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>Udemy Teacher</td>
                                <td>Udemy</td>
                                <td>
                                    <button class="btn btn-sm mr-2 btn-primary">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                              </tr> --}}
                             
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <span id="addT">Add new Teacher</span>
                        <span id="updateT">Update Tacher</span>
                    </div>
                    <div class="card-body">
                        
                            <div class="form-group">
                              <label for="exampleInputEmail1">Name</label>
                              <input type="text" placeholder="Enter name" class="form-control" id="name" aria-describedby="emailHelp">
                              <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Title</label>
                              <input type="text" placeholder="job position" class="form-control" id="title">
                              <span class="text-danger" id="titleError"></span>
                            </div>
                          
                            <div class="form-group">
                                <label for="exampleInputPassword1">Institute</label>
                                <input type="text" placeholder="institute name" class="form-control" id="institute">
                                <span class="text-danger" id="instituteError"></span>
                              </div>
                              <input type="hidden" id="id">
                            <button type="submit" id="addButton" onclick="addData()" class="btn mr-2 btn-primary">Add</button>
                            <button type="submit" id="updateButton" onclick="updateData()" class="btn btn-primary">Update</button>
                         
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#addT").show();
        $("#addButton").show();
        $("#updateT").hide();
        $("#updateButton").hide();

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        })

        function allData(){
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/teacher/all",
                success: function(response){
                    var data = ""
                    $.each(response, function(key, value){
                        data = data + "<tr>"
                        data = data + "<td>"+value.id+"</td>"
                        data = data + "<td>"+value.name+"</td>"
                        data = data + "<td>"+value.title+"</td>"
                        data = data + "<td>"+value.institute+"</td>"
                        data = data + "<td>"
                        data = data + "<button class='btn btn-sm mr-2 btn-primary' onclick='editData("+value.id+")'>Edit</button>"
                        data = data + "<button class='btn btn-sm btn-danger' onclick='deleteData("+value.id+")'>Delete</button>"
                        data = data + "</td>"
                        data = data + "</tr>"
                    })
                    $('tbody').html(data);
                }
            })
        }

        allData();

        function clearData(){
            $("#name").val('');
            $("#title").val('');
            $("#institute").val('');
            $('#nameError').text('');
            $('#titleError').text('');
            $('#instituteError').text('');
        }

        function addData(){
            var name = $("#name").val();
            var title = $("#title").val();
            var institute = $("#institute").val();

            $.ajax({
                type: "POST",
                dataType: "json",
                data: {name:name, title:title, institute:institute},
                url: "/teacher/store/",
                success: function(data){
                    clearData();
                    allData();
                    
                    const MSG = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    MSG.fire({
                        type: 'success',
                        title: 'Data Added Successfully!',
                    })
                },
                error: function(error){
                    $('#nameError').text(error.responseJSON.errors.name);
                    $('#titleError').text(error.responseJSON.errors.title);
                    $('#instituteError').text(error.responseJSON.errors.institute);
                }
            })
        }


        function editData(id){
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/teacher/edit/"+id,
                success: function(data){
                    $("#addT").hide();
                    $("#addButton").hide();
                    $("#updateT").show();
                    $("#updateButton").show();

                    $("#id").val(data.id);
                    $("#name").val(data.name);
                    $("#title").val(data.title);
                    $("#institute").val(data.institute);
                }
            })
        }

        function updateData(){
            var id = $("#id").val();
            var name = $("#name").val();
            var title = $("#title").val();
            var institute = $("#institute").val();

            $.ajax({
                type: "POST",
                dataType: "json",
                data: {name:name, title:title, institute:institute},
                url: "/teacher/update/"+id,
                success:function(response){
                    $("#addT").show();
                    $("#addButton").show();
                    $("#updateT").hide();
                    $("#updateButton").hide();
                    clearData();
                    allData();

                    const MSG = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    MSG.fire({
                        type: 'success',
                        title: 'Data Updated Successfully!',
                    })
                },
                error:function(error){
                    $('#nameError').text(error.responseJSON.errors.name);
                    $('#titleError').text(error.responseJSON.errors.title);
                    $('#instituteError').text(error.responseJSON.errors.institute);
                }
            })
        }

        function deleteData(id){
            $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "/teacher/destroy/"+id,
                    success:function(data){
                        $("#addT").show();
                        $("#addButton").show();
                        $("#updateT").hide();
                        $("#updateButton").hide();
                        clearData();
                        allData();
                    }
                })
            }
    </script>
</body>
</html>