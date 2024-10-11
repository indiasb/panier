<?php
// Inclure la page de connexion
include_once "inc/constant.php";

// Vérifier si une session existe
if (session_status() === PHP_SESSION_NONE) {
    // Si non, démarrer la session
    session_start();
}

// Créer la session panier s'il n'existe pas
if (!isset($_SESSION['boutique'])) {
    $_SESSION['boutique'] = array();
}

// Récupération de l'id dans le lien
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Vérifier si le produit existe dans la base de données en utilisant une requête préparée
    try {
        $query = "SELECT * FROM panier WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT); // Lier l'id en tant que paramètre INT
        $stmt->execute();
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit) {
            // Si le produit n'existe pas
            die("Ce produit n'existe pas");
        }

        // Ajouter le produit dans le panier (le tableau)
        if (isset($_SESSION['boutique'][$id])) {
            $_SESSION['boutique'][$id]++;
        } else {
            $_SESSION['boutique'][$id] = 1;
        }

        // Redirection vers la page index.php
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>