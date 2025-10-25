<?php
session_start();
if (!isset($_SESSION['responsable'])) {
    header("Location: config.php");
    exit();
}
require 'db.php';

// Traitement validation/refus
if (isset($_POST['action'], $_POST['id'])) {
    $id = intval($_POST['id']);
    $statut = ($_POST['action'] == 'valider') ? 'acceptée' : 'refusée';
    $stmt = $pdo->prepare("UPDATE demandes SET statut=? WHERE id=?");
    $stmt->execute([$statut, $id]);
}

// Récupérer les demandes
$stmt = $pdo->query("SELECT * FROM demandes ORDER BY date_heure DESC");
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Espace Responsable</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0; padding: 0;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #f8e1f4, #d7c0ff);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
            color: #4b3b89;
        }
        .container {
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            padding: 30px 40px;
            width: 100%;
            max-width: 1100px;
            box-shadow: 0 20px 40px rgba(107, 90, 195, 0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2.5rem;
            color: #4b3b89;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }
        th, td {
            padding: 16px 20px;
            text-align: center;
            vertical-align: middle;
            font-size: 15px;
        }
        th {
            background-color: #a98eff;
            color: white;
            font-weight: 700;
            border-radius: 12px 12px 0 0;
            user-select: none;
        }
        tr {
            background-color: #f0eaff;
            box-shadow: 0 3px 8px rgba(161, 144, 246, 0.25);
            border-radius: 12px;
            transition: background-color 0.3s ease;
        }
        tr:hover {
            background-color: #d6ccff;
        }
        td {
            border-radius: 12px;
        }
        form {
            display: inline-block;
            margin: 0 5px;
        }
        button {
            font-weight: 600;
            border: none;
            padding: 10px 22px;
            font-size: 14px;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(107, 90, 195, 0.3);
            transition: all 0.3s ease;
            user-select: none;
        }
        button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(169, 142, 255, 0.7);
        }
        button[name="action"][value="valider"] {
            background-color: #7aef95;
            color: #2b5f32;
            box-shadow: 0 6px 16px rgba(58, 172, 80, 0.5);
        }
        button[name="action"][value="valider"]:hover {
            background-color: #4ec64e;
            color: white;
            box-shadow: 0 8px 20px rgba(44, 137, 44, 0.7);
        }
        button[name="action"][value="refuser"] {
            background-color: #f28c8c;
            color: #7d2929;
            box-shadow: 0 6px 16px rgba(242, 75, 75, 0.5);
        }
        button[name="action"][value="refuser"]:hover {
            background-color: #d94d4d;
            color: white;
            box-shadow: 0 8px 20px rgba(217, 34, 34, 0.7);
        }
        a button {
            background-color: #b19cd9;
            color: #3b2f7a;
            padding: 12px 30px;
            font-size: 16px;
            margin: 30px auto 0;
            border-radius: 40px;
            display: block;
            width: 160px;
            box-shadow: 0 10px 24px rgba(161, 144, 246, 0.5);
            transition: background-color 0.3s ease, color 0.3s ease;
            text-align: center;
            user-select: none;
        }
        a button:hover {
            background-color: #8c7aff;
            color: white;
        }
        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 20px 10px;
                align-items: center;
            }
            .container {
                padding: 20px;
            }
            table, th, td {
                font-size: 13px;
            }
            button, a button {
                padding: 8px 15px;
                font-size: 14px;
                width: 100%;
            }
            form {
                margin: 8px 0;
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Liste des demandes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Nom</th><th>Prénom</th><th>Matricule</th><th>Type</th><th>Date/Heure</th><th>Raison</th><th>Statut</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $d): ?>
                <tr>
                    <td><?= $d['id'] ?></td>
                    <td><?= htmlspecialchars($d['nom']) ?></td>
                    <td><?= htmlspecialchars($d['prenom']) ?></td>
                    <td><?= htmlspecialchars($d['matricule']) ?></td>
                    <td><?= htmlspecialchars($d['type']) ?></td>
                    <td><?= htmlspecialchars($d['date_heure']) ?></td>
                    <td><?= htmlspecialchars($d['raison']) ?></td>
                    <td><?= htmlspecialchars($d['statut']) ?></td>
                    <td>
                        <?php if ($d['statut'] == 'en attente'): ?>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                <button type="submit" name="action" value="valider">Valider</button>
                                <button type="submit" name="action" value="refuser">Refuser</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="acceuil.php"><button>Déconnexion</button></a>
    </div>
</body>
</html>
