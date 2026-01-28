<!DOCTYPE html>
<html lang="en">
<style>
    p { color: red; font-size: 50pt; }
    .blok1   { background-color: yellowgreen; height: 200px; width: 200px;
    display: block; }
    .blok2  {background-color: aqua; height: 300px; width: 300px; 
    display: block;}
    #kuning {color: yellow;}
    #hijau {color: green;}
    #ungu {color: purple;}

</style>
<head>
    <meta charset="UTF-8">
    <title>coba minggu 2</title>

</head>
<body>
    <div class="container">
        <h1>Hello, World</h1>
        <p>MERAH</b> <b id="kuning">KUNING</b> <b id="hijau">HIJAU</b></p> 
        
    <?php
        $randomNumber = mt_rand(1, 1000);
    ?>

    <p> angka random: <?php echo $randomNumber; ?>  </p>
        <div class="blok1"></div>
        <div class="blok2"></div>
    </div>
</body>
</html>