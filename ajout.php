<?php
include_once "inc/constant.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['boutique'])) {
    $_SESSION['boutique'] = array();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $query = "SELECT * FROM panier WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit) {
            die("Ce produit n'existe pas");
        }

        if (isset($_SESSION['boutique'][$id])) {
            $_SESSION['boutique'][$id]++;
        } else {
            $_SESSION['boutique'][$id] = 1;
        }

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>