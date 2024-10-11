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

<?php

include_once "inc/constant.php";

if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    $id_del = intval($_GET['del']);

    if (isset($_SESSION['boutique'][$id_del])) {
        unset($_SESSION['boutique'][$id_del]);
    }
}
?>
    <main>
    <section class="panier">
        <ul>
            <li><h1>Panier</h1></li>
        </ul>
        <table>
            <tr>
                <th>Produit(s)</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Supprimer</th>
            </tr>
            <?php
            $total = 0;

            if (isset($_SESSION['boutique']) && is_array($_SESSION['boutique'])) {
                $ids = array_keys($_SESSION['boutique']);

                if (empty($ids)) {
                    echo "<tr><td colspan='5'>Votre panier est vide</td></tr>";
                } else {
                    $productIds = implode(',', array_map('intval', $ids));

                    try {
                        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);


                        $query = "SELECT id, name, price, img FROM panier WHERE id IN ($productIds)";
                        $stmt = $pdo->prepare($query);

                        $stmt->execute();

                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($products as $product){
                            $subtotal = $product['price'] * $_SESSION['boutique'][$product['id']];
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td class="table-content"><img src="assets/IMG/<?=htmlspecialchars($product['img'])?>" alt="<?=htmlspecialchars($product['name'])?>"></td>
                                <td><?=htmlspecialchars($product['name'])?></td>
                                <td><?=$product['price']?>€</td>
                                <td class="table-content"><?=htmlspecialchars($_SESSION['boutique'][$product['id']])?></td>
                                <td class="table-content"><a href="panier.php?del=<?=urlencode($product['id'])?>"><i class="fa-solid fa-trash-can"></i></a></td>
                            </tr>
                        <?php };

                        $pdo = null;
                    } catch (PDOException $e) {
                        echo "Erreur : " . $e->getMessage();
                    }
                }
            }
            ?>
            <tr class="total">
                <th>Total : <?=$total?>€</th>
            </tr>
        </table>
        <button class="payment"><a href="#">Payer</a></button>
    </section>
    </main>

</body>
</html>