<?php
include('includes/header.php');
?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
              <div class="page-header">
              <h3 class="page-title"> Convert JSON to MYSQL</h3>
                  <h3 class="alert"></h3>
             
            </div>
              
              
              
              
              
              
              
              
              
              <div class="row">
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="dbName">Database Name</label>
                        <input type="text" class="form-control" id="dbName" name="dbName" placeholder="Database Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Table Name</label>
                        <input type="email" class="form-control" id="tableName" name="tableName" placeholder="table Name">
                      </div>
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
                  <div class="col-md-6 text-center">
                  <img src="images/img2.png">
                  </div>
            </div>
               
          </div>
          <!-- content-wrapper ends -->
            
            
            <?php
            include('includes/footer.php');
            ?>
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
<?php
    if(isset($_POST['valider'])){
         if(!isset($_POST['tableName']) || !isset($_POST['dbName']) || $_FILES['jsonFile']['name']=="" ){
             
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
                  
                  echo "<script>
                  $('.alert').append('Wait please...')
                  </script>";
                  
                  //upload file
                  move_uploaded_file($tmpName,"files/".$fileName);
                  
                    //get table and database name
                    $table = $_POST['tableName'];
                    $dbName = $_POST['dbName'];
   
                    //get data from json file
                    $data = file_get_contents('files/'.$fileName);
                    //convert json to array 
                    $data = json_decode($data, true); 
                  
                    //get keys of array
                    $implodeColumns = array_keys($data[0]);
                  
                  
 /**************************************** number of character of data *********************************************************/
     $req = "";    
    for($i= 0; $i<count($data);$i++){   
        //verify type
         $type ="";
        if($i<count($implodeColumns)){
               if(gettype($data[0][$implodeColumns[$i]]) == 'string')$type="VARCHAR";
        elseif(gettype($data[0][$implodeColumns[$i]]) == 'integer')$type="INT";
        elseif(gettype($data[0][$implodeColumns[$i]]) == 'boolean')$type="VARCHAR"; $max = 1;
        
 
        
        //max of char 
        for($j= 0; $j<count($implodeColumns);$j++){
          $var = $data[$j][$implodeColumns[$i]];
            $length =  strlen($var);
            $max = 0;
            if($j==0){
                $length = 0;
            }
            else{
                if($length>$max){
                    $max=$length;
                }
            }
        }
     $req  .= ' _'.$implodeColumns[$i].' '.$type.'('.$max.') NOT NULL,';
        }
    }//end for
                  
                  
    // **> remove the last comma         
    $req = 'cpt INT PRIMARY KEY NOT NULL AUTO_INCREMENT,'.$req;
    $req = substr($req,0,-1); 
                  
                  
                  
/******************************************* creation de la base de données ******************************************************/
   $serverName = 'localhost';
    $userName="root";
    $password='phpmyadmin';
    
    try
{
    $conn = new PDO("mysql:host=".$serverName."; charset=utf8", $userName, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $sql = "CREATE DATABASE IF NOT EXISTS ".$dbName."";
    $conn->exec($sql);
    $sql = "use ".$dbName."";
    $conn->exec($sql);
        }
catch(PDOException $e)
{
    echo "Error".$e->getMessage();
}
/******************************************** creation de  la table ****************************************************************/
$sql = $conn->prepare('CREATE TABLE '.$table.' ('.$req.') ENGINE = InnoDB  ');
$sql->execute();

/******************************************** insertion de données ****************************************************************/
   for($i = 0;$i<count($data);$i++ ){
       echo $i.'<br>';
        $values="";    
        foreach($data[$i] as $key => $value){
            $values .='"'.$value.'"'.',';
        }
        $values = '"" ,'.$values; 
        $values = substr($values,0,-1);
       //*********** requete sql 
        $req = 'INSERT INTO '.$table.' VALUES ('.$values.')';
        $sql = $conn->prepare($req);
       $sql->execute();

    }//for
                   echo "<script>
                   
                   $('.alert').text('');
           Swal.fire({
              
             icon: 'success',
              title: 'Exécution effectué avec succes',
              showConfirmButton: false,
              timer: 1500
            })
           </script>";
              }
         }//else
        
    }//if        
?>