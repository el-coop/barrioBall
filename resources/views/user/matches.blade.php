
<?php


use App\Models\User;


echo "koko";
$user =Auth::user();

foreach ($user->match as $match){
echo $match->name;
}

