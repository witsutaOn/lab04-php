<!doctype html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://fonts.googleapis.com/css?family=Signika+Negative" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Concert+One|Patua+One" rel="stylesheet">
    <link rel="stylesheet" type = "text/css" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Lab04 - Witsuta Onampai</title>
</head>
<body id="index-bg">
<div id="form">
    <form  method="post" enctype="multipart/form-data">
        <h1 id="header-form">SALARY INFORMATION</h1>
        <div class="form-group">
            <label>Company's Name</label>
            <input type="text" class="form-control" name="companyName" placeholder="" required>
            <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
        </div>

        <div class="form-group">
            <label >Company's Logo</label>
            <div class="input-group mb-3 form-group">

                <div class="input-group-prepend">
                    <span class="input-group-text">Upload</span>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="fileToUpload" name="fileToUpload" onchange="getImage(this)" required>
                    <label class="custom-file-label" for="fileToUpload" id="imageName">Choose file</label>

                </div>
                <label style="font-size: 15px;color: #343a40;padding-top: 5px;">
                    <?php
                    $target_dir = "";
                    $DisplayLogo = FALSE;
                    $DisplayData = TRUE;
                    if(isset($_FILES['fileToUpload'])){
                        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        // Check file size
                        if ($_FILES["fileToUpload"]["size"] > 500000) {
                            echo "Sorry, your file is too large.";
                            $uploadOk = 0;
                        }
                        // Allow certain file formats
                        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                            echo "Sorry, only JPG, JPEG, PNG files are allowed.";
                            $uploadOk = 0;
                        }
                        // Check if $uploadOk is set to 0 by an error
                        if ($uploadOk == 0) {
                            echo "Sorry, your file was not uploaded.";
                            // if everything is ok, try to upload file
                        } else {
                            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                $DisplayLogo = TRUE;
                            } else {
                                echo "Sorry, there was an error uploading your file.";
                            }
                        }
                    }
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group" style="padding-top: 10px">
            <label >Form CSV File</label><br>
            <a class="btn btn-outline-dark" href="Form-CSV.csv">Download Salary form</a>
        </div>

        <div class="form-group">
            <label >Upload Data CSV File</label>
            <div class="input-group mb-3 form-group">

                <div class="input-group-prepend">
                    <span class="input-group-text" >Upload CSV File</span>

                </div>

                <div class="custom-file" >
                    <input type="file" class="custom-file-input" id="fileToUploadCSV" name="fileToUploadCSV" onchange="getFile(this)">
                    <label class="custom-file-label" for="fileToUploadCSV" id="fileName">Choose file</label>
                </div>


            </div>
        </div>
        <label style="font-size: 15px;color: #343a40;">

            <?php
            if(isset($_FILES['fileToUploadCSV'])){
                $target_CSV_file = $target_dir . basename($_FILES["fileToUploadCSV"]["name"]);
                $uploadCSVOk = 1;
                $csvFileType = strtolower(pathinfo($target_CSV_file,PATHINFO_EXTENSION));

                if($csvFileType != "csv") {
                    echo  "Sorry, only CSV file are allowed.     ";
                    $uploadCSVOk = 0;

                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadCSVOk == 0) {
                    echo  "Sorry, your file was not uploaded.";

                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["fileToUploadCSV"]["tmp_name"], $target_CSV_file)) {
                        $csvFile = fopen($target_CSV_file,"r");
                        $word = fread($csvFile,filesize($target_CSV_file));
                        fclose($csvFile);
                        $array = preg_split("/[\s,]+/",$word);
                        $avgSalary = 0;
                        $totalSalary = 0;
                        $countAmount = 0;
                        $minSalary = 5000000000;
                        $maxSalary = 0;
                        for($x = 3;$x < count($array);$x=$x+2){

                            $salary = $array[$x ];
                            $countAmount++;
                            $totalSalary = $totalSalary + $salary;
                            if($salary >= $maxSalary){
                                $maxSalary=$salary;
                            }
                            if ($salary<=$minSalary){
                                $minSalary=$salary;
                            }
                        }
                        $avgSalary = $totalSalary/$countAmount;
                        $DisplayCSV = TRUE;
                        $DisplayData = TRUE;
                    }
                }
            }
            ?>
        </label>
        <br>

        <div class="form-group" style="text-align: center;padding-top: 10px">
            <button id="submitBtn" type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="form-group">
            <?php
            if($DisplayLogo && $DisplayData && $uploadCSVOk){
            ?>
            <div class="form-group" style="text-align: center">
                <img style="height: 200px;padding-top: 15px;padding-bottom: 20px" src="<?php echo $_FILES["fileToUpload"]["name"];?>">
                <p>Company's Name : <?php echo $_POST["companyName"] ?></p>
                <p>Total Employee: <?php echo $countAmount; ?></p>
                <p>Minimum Salary: <?php echo $minSalary; ?></p>
                <p>Maximum Salary: <?php echo $maxSalary ?></p>
                <p>Average Salary : <?php echo $avgSalary ?></p>

            </div>
                <?php
            }
            ?>

        </div>
    </form>
</div>

<script>
    // $(document).ready(function() {
    //     $("#submitBtn").click(function(){
    //         $("#test").slideToggle("speed");
    //     });
    // });
    function getImage(object){
        var file = object.files[0];
        var name = file.name;
        document.getElementById('imageName').innerHTML=name;
    }
    function getFile(object){
        var file = object.files[0];
        var name = file.name;
        document.getElementById('fileName').innerHTML=name;
    }

</script>
</body>
</html>
