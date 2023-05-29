<?php
  // INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, 'Buy Books', 'Buy my college books from lucky book store', current_timestamp());
  $insert = false;
  $update = false;
  $delete = false;
  //Database Connection
  
  $servername = 'localhost';
  $username = 'root';
  $pass = '';
  $db = 'notes';

  $conn = mysqli_connect($servername,$username,$pass,$db);

  if(!$conn){
    die("Sorry we can't connect due to an error");
  }

  if(isset($_GET['delete'])){
    $sno = $_GET['delete'];
    $delete = true;
    
    $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
    $result = mysqli_query($conn,$sql); 
  }

  
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset( $_POST['snoEdit'])){
      // Update the record
        $sno = $_POST["snoEdit"];
        $title = $_POST["titleEdit"];
        $description = $_POST["descriptionEdit"];
    
      // Sql query to be executed
      $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno";
      $result = mysqli_query($conn, $sql);
      
      if($result){
        $update = true;
    }
    else{
        $err = mysqli_error($conn);
        echo "Query was not executed because --> $err <br>";
    }
    }
  else{
    $title = $_POST["title"];
    $description = $_POST["description"];
    
    $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
    $result = mysqli_query($conn,$sql);

    if($result){
      $insert = true;
  }
  else{
      $err = mysqli_error($conn);
      echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
      <strong>Erorr !</strong> Your note has not been inserted due to an error.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
  }
  }
  }
  
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

  <title>MyNotes</title>
</head>

<body>

  <!-- Button trigger modal -->
  <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">
  Edit Modal
</button> -->

  <!-- Modal -->


  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">PHP CRUD</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>

  <?php
    if($insert){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your Note has been inserted successfully.
      <a href='#' class='close' data-dismiss='alert' aria-label='close'>
          <button type='button' class='close' data-bs-dismiss='alert' aria-label='Close'>&times;
          </button>
      </a>
  </div>";
    }
    if($update){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your Note has been successfully updated.
      <a href='#' class='close' data-dismiss='alert' aria-label='close'>
          <button type='button' class='close' data-bs-dismiss='alert' aria-label='Close'>&times;
          </button>
      </a>
  </div>";
    }
    if($delete){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your Note has been deleted successfully.
      <a href='#' class='close' data-dismiss='alert' aria-label='close'>
          <button type='button' class='close' data-bs-dismiss='alert' aria-label='Close'>&times;
          </button>
      </a>
  </div>";
    }
  ?>

  <div class="container mt-5">
    <h2>Add Note</h2>
    <form action="/crud/index.php?" method="post">
      <div class="form-group">
        <label for="title">Note Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
        <div class="form-group mt-3">
          <label for="description">Note Description</label>
          <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
  </div>



  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="/crud/index.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="title">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
              <label for="desc">Note Description</label>
              <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
            </div> 
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="container my-4">
    <table class="table" id="mytable">
      <thead>
        <tr>
          <th scope="col">S.No</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * FROM `notes`";
          $result = mysqli_query($conn , $sql);
          $sno = 1;
          while($row = mysqli_fetch_assoc($result)){
            echo " <tr>
            <th scope='row'>".$sno ." </th>
            <td>".$row['title'] ."</td>
            <td>".$row['description'] ."</td>
            <td>
              <button class='btn btn-sm btn-primary edit' id=".$row['sno'] .">Edit</button> <button class='btn btn-primary btn-sm delete' id=d". $row['sno'].">Delete</button>
            </td>
            </tr>";
            $sno+=1;
          }  
        ?>
      </tbody>
    </table>
  </div>
  <hr>





  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#mytable').DataTable();
    });
  </script>
  <script>
edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName("td")[0].innerText;
        description = tr.getElementsByTagName("td")[1].innerText;
        titleEdit.value = title;
        descriptionEdit.value = description;
        snoEdit.value = e.target.id;
        $('#editModal').modal('toggle');
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        sno = e.target.id.substr(1,);
        if(confirm("Are you sure you want to delete this note")){
          console.log("yes");
          window.location=`/crud/index.php?delete=${sno}`;
        }
        else{
          console.log("no");
        }
      });
    });
  </script>
</body>

</html>