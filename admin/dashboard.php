<?php include("navbar.php");?>

<div class="container mt-5">
    <div class="row">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>DASHBOARD</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card text-center text-white p-3 bg-secondary">
                        <h6>TOTAL INSTRUCTORS</h6>
                        <?php
                            $sql = "SELECT * FROM `user` WHERE `user_type` = 2";
                            $sql_run = mysqli_query($conn,$sql);
                            if($result = mysqli_num_rows($sql_run)){
                                echo '<h1 class="mt-2 mb-0">'.$result.'</h1>';
                            }else{
                                echo '<h1 class="mt-2 mb-0">0</h1>';
                            }   
                        ?>
                        <!-- <h1 class="mt-2 mb-0">5</h1> -->
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card text-center text-white p-3 bg-primary">
                        <h6>TOTAL STUDENTS</h6>
                        <?php
                            $sql = "SELECT * FROM `user` WHERE `user_type`= 1";
                            $sql_run = mysqli_query($conn,$sql);
                            if($result = mysqli_num_rows($sql_run)){
                                echo '<h1 class="mt-2 mb-0">'.$result.'</h1>';
                            }else{
                                echo '<h1 class="mt-2 mb-0">0</h1>';
                            }   
                        ?>
                        <!-- <h1 class="mt-2 mb-0">5</h1> -->
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card text-center text-white p-3 bg-warning">
                        <h6>TOTAL SUBJECTS</h6>
                        <?php
                            $sql = "SELECT * FROM `subject`";
                            $sql_run = mysqli_query($conn,$sql);
                            if($result = mysqli_num_rows($sql_run)){
                                echo '<h1 class="mt-2 mb-0">'.$result.'</h1>';
                            }else{
                                echo '<h1 class="mt-2 mb-0">0</h1>';
                            }   
                        ?>
                        <!-- <h1 class="mt-2 mb-0">5</h1> -->
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card text-center text-white p-3 bg-danger">
                        <h6>TOTAL ROOMS</h6>
                        <?php
                            $sql = "SELECT * FROM `room`";
                            $sql_run = mysqli_query($conn,$sql);
                            if($result = mysqli_num_rows($sql_run)){
                                echo '<h1 class="mt-2 mb-0">'.$result.'</h1>';
                            }else{
                                echo '<h1 class="mt-2 mb-0">0</h1>';
                            }   
                        ?>
                        <!-- <h1 class="mt-2 mb-0">5</h1> -->
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
