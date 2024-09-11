<?php
include('includes/header.php');
?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
              <div class="page-header">
              <h3 class="page-title"> Convert JSON to CSV file</h3><br>
                
             
            </div>
              
               <h3 class="alert"></h3> 
              
              
              
              
              
              
              
              <div class="row">
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                    
                   
                    <div class="form-group">
                        <label>File upload</label>
                        <input type="file" name="jsonFile" class="file-upload-default">
                        <div class="input-group col-xs-12">
                          <input type="text" class="form-control file-upload-info" disabled=""  placeholder="Upload Image">
                          <span class="input-group-append">
                            <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                          </span>
                        </div>
                      </div>
                    
                  <input type="submit" value="valider" name="valider"  class="btn btn-primary mr-2 ">
                      
                    </form>
                      
                  </div>
                </div>
              </div> 
                  <div class="col-md-6  text-center">
                      <img src="images/img1.png">
                  </div>
            </div>
               
          </div>
          <!-- content-wrapper ends -->
            
            
            <?php
            include('includes/footer.php');
                        print_r(glob("files/*.xml"));   
            ?>
            
            
 
            
            
            <?php

                 if(isset($_POST['valider'])){
                   
                     if($_FILES['jsonFile']['name']==""){
                          echo '<script>Swal.fire({
                              icon: "error",
                              title: "Oops...",
                              text: "remplir tous les champs!"

                            })</script>';
                         }else{
                          $fileName = $_FILES['jsonFile']['name'];
                          $fileType = $_FILES['jsonFile']['type'];
                          $tmpName = $_FILES['jsonFile']['tmp_name'];
                          $fileSize = $_FILES['jsonFile']['size'];
                          if($fileType != 'application/json'){
                           echo '<script>Swal.fire({
                          icon: "error",
                          title: "Oops...",
                          text: "erreur!"

                        })</script>';
                        }else{
                              //upload file
                                move_uploaded_file($tmpName,"files/".$fileName);
                              
                              //get data from json file
                                $data = file_get_contents("files/".$fileName);
                                //convert json to array 
                                $data = json_decode($data, true);
                                $fp = fopen('files/csvFile.csv', 'w');
                                $header = false;
                              foreach ($data as $row)
                                    {
                                        if (empty($header))
                                        {
                                            $header = array_keys($row);
                                            fputcsv($fp, $header);
                                            $header = array_flip($header);
                                        }
                                        fputcsv($fp, array_merge($header, $row));
                                    }
                                    fclose($fp);
                  echo "
                      <script>
                      $('.alert').html('Successfully converted json to csv file. <a href=\"files/csvFile.csv\" target=\"_blank\">Click here to open it.</a>');
                      </script>";
                          }
                        }
                     }
                 
            
            ?>