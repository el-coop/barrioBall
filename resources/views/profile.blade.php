


<?php
use Illuminate\Support\Facades\Auth;

// Get the currently authenticated user...
$user = Auth::user();
$lang = Auth::user()->language;

$email = Auth::user()->email;
$password = Auth::user()->password;
// Get the currently authenticated user's ID...
$id = Auth::id();


if(isset($_POST['update_user'])){

    $lang1 =$_POST['language'];

    $email1 = $_POST['email'];
    $password1 = $_POST['password'];

        DB::update('update users set language = {$lang1}, password = {$password1}, email = {$email} where id = $id');



}

?>


@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">

                        <label for="language">language</label>
                        <select name="language" id="">
                            <option value="<?php echo $lang;?>"><?php echo $lang;?></option>

                            <?php

                            if($lang == 'en'){
                                echo  "<option value='es'>es</option>";
                            }else{
                                echo "<option value='en'>en</option>";
                            }

                            ?>
                        </select>

                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $email;?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" value="<?php echo $email;?>">
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="update_user" value="Update user">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

