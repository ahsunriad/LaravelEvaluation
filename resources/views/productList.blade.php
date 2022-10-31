<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-html5-2.2.2/fh-3.2.2/r-2.2.9/datatables.min.css" />

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-html5-2.2.2/fh-3.2.2/r-2.2.9/datatables.min.js"></script>
    
</head>
<body>
    <div class="container">
        <h1 style="text-align:center;">Product List</h1>
        <a class="btn btn-success btn-sm" id="createNewProduct" href="javascript:void(0)" style="float:right;">Add Product</a>
        <table class="table table-bordered data-table table-striped text-start w-100" id="product-table" >
        <thead>
            <tr style="text-align:center;">
                <th>No</th>
                <th>Title</th>
                <th>Thumbnail</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Description</th>>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>

        </table>
    </div>

    <div class = "modal fade" id="modalCreate" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="productForm" name="productForm" class="form-horizontal" action="">
                        <!-- <input type="hidden" name="product_id" id="product_id"> -->
                        <div class="form-group">
                            Title:<br>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="" maxlength="50" >
                        </div>
                        <div class="form-group">
                            Description:<br>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" value="" maxlength="50" >
                        </div>
                        <div class="form-group">
                            Subcategory:<br>
                            <input type="text" class="form-control" id="subcategory_id" name="subcategory_id" placeholder="Enter Subcategory Id" value="" maxlength="50" >
                        </div>
                        <div class="form-group">
                            Thumbnail:<br>
                            <input type="text" class="form-control" id="thumbnail" name="Thumbnail" placeholder="Enter Thumbnail" value="" maxlength="50" >
                        </div>
                        <div class="form-group">
                            Price:<br>
                            <input type="text" class="form-control" id="Price" name="price" placeholder="Enter Price" value="" maxlength="50" >
                        </div>
                        <br/>
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
<script type="text/javascript">


    $(function(){
        $('#createNewProduct').click(function () {
            $('#saveBtn').val("create-product");
            $('#product_id').val('');
            $('#productForm').trigger("reset");
            $('#modelHeading').html("Create New Product");
            $('#modalCreate').modal('show');
        });
        
        $("#saveBtn").click(function(){
            e.preventDefault();
            $(this).html('Sending..');
            $.ajax({
                data: $('#productForm').serialize(),
                url: "{{ route('product.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#productForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save');
                }
            });
        })
    });

    function deleteProudct(id){
        let text = "Are you sure you want to delete this product?";
        if (confirm(text) == true) {
            $.ajax({
                url: "/product/delete/"+ id,
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    if (jqXHR.status == 200) {
                        alert("Product deleted successfully!");
                        $('#product-table').DataTable().ajax.reload();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                    alert("Failed to delete product!");
                }
            });
        }
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#product-table').DataTable({
            processing: false,
            language: {
                loadingRecords: '&nbsp;',
                processing: 'Loading...'
            },
            serverSide: true,
            paging: true,
            lengthChange: true,
            responsive: true,
            dom: 'Bfrtip',
            dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [{
                    extend: 'pageLength',
                    className: 'px-3 btn btn-danger',
                },
            ],
            lengthMenu: [25, 50, 75, 100, 500],
            order: [
                [0, "desc"]
            ],
            ajax: {
                url: "/productList/all",
                type: 'POST',
                dataType: "json",
            },
            columns: [{
                    data: 'product_id',
                    render: function(data, type, row, meta) {
                        return 'P000' + data;
                    }
                },
                {
                    data: 'product_title'
                },
                {
                    data: 'thumbnail'
                },
                {
                    data: 'category_title'
                },
                {
                    data: 'subcategory_title'
                },
                {
                    data: 'description'
                },
                
                {
                    data: 'price',
                },
                {
                    data: 'product_id',
                    render: function(data, type, row, meta) {
                        return '<div class="btn btn-danger btn-sm delete-product-btn" value="'+data+'" id = "delete-btn" onclick="deleteProudct('+data+')">Delete</div>';                 
                    },
                },
            ]
        });
    });
</script>
</body>


</html>