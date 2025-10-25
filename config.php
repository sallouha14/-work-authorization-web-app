<?php
session_start();
$motdepasse = "admin123"; // À changer !

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['password'] === $motdepasse) {
        $_SESSION['responsable'] = true;
        header("Location: responsable.php");
        exit();
    } else {
        $erreur = "Mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Connexion Responsable</title>
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
            align-items: center;
            color: #4b3b89;
            padding: 20px;
        }
        form {
            background: rgba(255,255,255,0.95);
            padding: 35px 30px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(107, 90, 195, 0.2);
            width: 320px;
            text-align: center;
        }
        h2 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 25px;
            color: #4b3b89;
        }
        label {
            display: block;
            text-align: left;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 14px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #b9aaff;
            font-size: 14px;
            transition: border-color 0.3s ease;
            margin-bottom: 20px;
        }
        input[type="password"]:focus {
            outline: none;
            border-color: #7a61ff;
            box-shadow: 0 0 8px #a99aff66;
        }
        button[type="submit"] {
            background-color: #7a61ff;
            color: white;
            border: none;
            padding: 14px 0;
            font-size: 16px;
            font-weight: 700;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(122, 97, 255, 0.4);
            width: 100%;
            transition: background-color 0.3s ease;
            user-select: none;
        }
        button[type="submit"]:hover {
            background-color: #5a44d1;
            box-shadow: 0 12px 28px rgba(90, 68, 209, 0.6);
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #842029;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
            user-select: none;
        }
        a button {
            background-color: #b19cd9;
            color: #3b2f7a;
            padding: 10px 25px;
            font-size: 15px;
            border-radius: 40px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(161, 156, 217, 0.5);
            transition: background-color 0.3s ease;
            user-select: none;
            margin-top: 15px;
            width: auto;
        }
        a button:hover {
            background-color: #8c7aff;
            color: white;
        }
    </style>
</head>
<body>
    <form method="post" autocomplete="off" novalidate>
        <h2>Connexion Responsable</h2>
        <?php if (isset($erreur)): ?>
            <div class="error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <label for="password">Mot de passe :</label>
        <input id="password" type="password" name="password" required autofocus />
        <button type="submit">Connexion</button>
        <a href="acceuil.php"><button type="button">Retour</button></a>
    </form>
</body>
</html>
