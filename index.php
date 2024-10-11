<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./assets/CSS/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>Boutique</title>
    </head>

<body>

    <header class="header">
        <nav>
            <ul class="ul_header">
                <li><a href="index.php"><h1>Boutique</h1></a></li>
                <li><a href="panier.php" ><i class="fa-solid fa-cart-shopping"><?= isset($_SESSION['boutique']) ? array_sum($_SESSION['boutique']) : 0 ?></i></a></li></ul>
            </ul>
        </nav>
    </header>

    <main>
        <ul>
            <li><h1>Tee-shirt</h1></li>
        </ul>

    <section class="products_list"> 
        <?php
        include_once("inc/constant.php");

        try{
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);

            $sql = "SELECT * FROM panier";
            $query = $pdo->prepare($sql);
            $query->execute();
            $products = $query->fetchALL(PDO ::FETCH_ASSOC);
            foreach($products as $product){
        ?>

        <article class="product">
            <div class="image_product">
                <img src="assets/IMG/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>

            <div class="content">
                <h4 class="name"><?= htmlspecialchars($product['name']) ?></h4>
                <h2 class="price"><?= number_format($product['price'], 2) ?>€</h2>
                <a href="ajout.php?id=<?= intval($product['id']) ?>" class="id_product">Ajouter</a>
            </div>
        </article>

        <?php
            } 
        }catch(PDOException $err){
            echo"Erreur :".err->getMessage();
        }
        $pdo = null;
        ?>
        
    </section>
    </main>

</body>
</html>