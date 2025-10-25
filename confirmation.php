<?php
session_start();

if (!isset($_SESSION['confirmation'])) {
    header("Location: demande.php");
    exit();
}

$data = $_SESSION['confirmation'];
unset($_SESSION['confirmation']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Confirmation de la demande</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0; padding: 0;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8e1f4, #d7c0ff);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #4b3b89;
        }
        .container {
            background: rgba(255,255,255,0.95);
            max-width: 480px;
            width: 100%;
            border-radius: 16px;
            padding: 35px 30px;
            box-shadow: 0 20px 40px rgba(107, 90, 195, 0.2);
        }
        h2 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 30px;
            color: #4b3b89;
            text-align: center;
        }
        .field {
            margin-bottom: 20px;
        }
        .label {
            font-weight: 600;
            font-size: 14px;
            color: #6b599c;
            margin-bottom: 6px;
            user-select: none;
        }
        .value {
            background-color: #e9e4ff;
            padding: 12px 15px;
            border-radius: 10px;
            font-size: 15px;
            color: #3e3475;
            user-select: text;
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.05);
        }
        a button {
            display: block;
            margin: 30px auto 0;
            background-color: #7a61ff;
            color: white;
            border: none;
            padding: 14px 0;
            font-size: 16px;
            font-weight: 700;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(122, 97, 255, 0.4);
            width: 200px;
            transition: background-color 0.3s ease;
            user-select: none;
            text-align: center;
        }
        a button:hover {
            background-color: #5a44d1;
            box-shadow: 0 12px 28px rgba(90, 68, 209, 0.6);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Récapitulatif de votre demande</h2>

        <div class="field">
            <div class="label">Nom :</div>
            <div class="value"><?= htmlspecialchars($data['nom']) ?></div>
        </div>

        <div class="field">
            <div class="label">Prénom :</div>
            <div class="value"><?= htmlspecialchars($data['prenom']) ?></div>
        </div>

        <div class="field">
            <div class="label">Matricule :</div>
            <div class="value"><?= htmlspecialchars($data['matricule']) ?></div>
        </div>

        <div class="field">
            <div class="label">Type de demande :</div>
            <div class="value"><?= htmlspecialchars($data['type']) ?></div>
        </div>

        <div class="field">
            <div class="label">Date et heure souhaitée :</div>
            <div class="value"><?= htmlspecialchars($data['date_heure']) ?></div>
        </div>

        <div class="field">
            <div class="label">Raison :</div>
            <div class="value"><?= htmlspecialchars($data['raison']) ?></div>
        </div>

        <a href="acceuil.php"><button>Retour à l'accueil</button></a>
    </div>
</body>
</html>
