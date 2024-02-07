<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Recherche d'employés</title>
</head>
<body>

<div class="header">
<h2>PAGE ADMIN</h2>
</div>

<div class="form-container1">
    <h1>Recherche d'employés</h1>
    <form action="bienvenu.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required>

        <input type="submit" value="Rechercher">
    </form>
</form>

    </div>
<br>
<br>

<?php

error_reporting(0);
ini_set('display_errors', 0);

//On demare la session sur sur cette page 
session_start() ;

include_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    $query = $bdd->prepare("
        SELECT DISTINCT
            e.Nom AS Employe_Nom,
            e.Prénom AS Employe_Prenom,
            e.Statut AS Employe_Statut,
            b.Numero_du_badge,
            b.Date_Activation AS Badge_Date_Activation,
            b.Statut_Badge,
            p.Type AS Pointage_Type,
            p.DatePointage AS Pointage_Date,
            p.HeurePointage AS Pointage_Heure,
            s.Nom AS Service_Nom,
            s.Description AS Service_Description
        FROM employes e
        INNER JOIN badges b ON e.Id_Employes = b.Id_Employes
        INNER JOIN pointage p ON e.Id_Employes = p.Id_Badges
        INNER JOIN services s ON e.Id_Services = s.Id_Services
        WHERE e.Nom LIKE :nom AND e.Prénom LIKE :prenom
    ");
    
    $nom = '%' . $nom . '%'; // Ajout des % pour utiliser LIKE
    $prenom = '%' . $prenom . '%';

    $query->bindParam(':nom', $nom);
    $query->bindParam(':prenom', $prenom);
    $query->execute();

    // Affichage du tableau
    echo '<table border="1">';
    echo '<tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Statut</th>
            <th>Numéro du badge</th>
            <th>Date d\'activation du badge</th>
            <th>Statut du badge</th>
            <th>Type de pointage</th>
            <th>Date de pointage</th>
            <th>Heure de pointage</th>
            <th>Nom du service</th>
            <th>Description du service</th>
          </tr>';

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $row['Employe_Nom'] . '</td>';
        echo '<td>' . $row['Employe_Prenom'] . '</td>';
        echo '<td>' . $row['Employe_Statut'] . '</td>';
        echo '<td>' . $row['Numero_du_badge'] . '</td>';
        echo '<td>' . $row['Badge_Date_Activation'] . '</td>';
        echo '<td>' . $row['Statut_Badge'] . '</td>';
        echo '<td>' . $row['Pointage_Type'] . '</td>';
        echo '<td>' . $row['Pointage_Date'] . '</td>';
        echo '<td>' . $row['Pointage_Heure'] . '</td>';
        echo '<td>' . $row['Service_Nom'] . '</td>';
        echo '<td>' . $row['Service_Description'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
}

?>


    <div class="form-container2">
    <h1>Ajouter un nouvel employés</h1>
    <!-- Formulaire pour ajouter un nouvel employé -->
    <form action="bienvenu.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required>

        <!-- Champs supplémentaires pour ajouter un nouvel employé -->
        <label for="status">Statut :</label>
        <input type="text" name="status" id="status" required>

        <label for="id_service">ID Service :</label>
        <input type="text" name="id_service" id="id_service" required>

        <input type="submit" value="Ajouter">
    </form>
</div> 
</body>
</html>
<br>
<br>
<br>
<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $status = $_POST['status'];
    $id_service = $_POST['id_service'];

    // Préparation de la requête d'insertion
    $query = $bdd->prepare("
        INSERT INTO employes (Nom, Prénom, Statut, Id_Services)
        VALUES (:nom, :prenom, :status, :id_service)
    ");
    
    // Liaison des paramètres et exécution de la requête
    $query->bindParam(':nom', $nom);
    $query->bindParam(':prenom', $prenom);
    $query->bindParam(':status', $status);
    $query->bindParam(':id_service', $id_service);
    $query->execute();

    // Confirmation de l'ajout de l'employé
    echo "Employé ajouté avec succès.";
}

?>

