<?php
session_start();
require 'db.php'; 

$type = isset($_GET['type']) ? $_GET['type'] : '';

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $matricule = trim($_POST['matricule']);
    $date_heure = $_POST['date_heure'];
    $raison = trim($_POST['raison']);
    $type = $_POST['type'];

    
    if ($nom && $prenom && $matricule && $date_heure && $raison && $type) {
        try {
            
            $stmt = $pdo->prepare("INSERT INTO demandes (nom, prenom, matricule, date_heure, raison, type, statut) VALUES (?, ?, ?, ?, ?, ?, 'en attente')");
            $stmt->execute([$nom, $prenom, $matricule, $date_heure, $raison, $type]);

            
            $_SESSION['confirmation'] = [
                'nom' => $nom,
                'prenom' => $prenom,
                'matricule' => $matricule,
                'date_heure' => $date_heure,
                'raison' => $raison,
                'type' => $type
            ];

            
            header("Location: confirmation.php");
            exit();

        } catch (Exception $e) {
            $message = "Erreur lors de l'enregistrement de la demande : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Faire une demande</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }
        html, body {
            height: 100%;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f8e1f4, #d7c0ff);
            color: #4b3b72;
            overflow: hidden;
            position: relative;
        }
        canvas#bgCanvas {
            position: fixed;
            top: 0; left: 0;
            width: 110%; height: 110%;
            display: block;
            z-index: 0;
            will-change: transform;
            pointer-events: none;
        }
        .container {
            position: relative;
            z-index: 10;
            max-width: 420px;
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255 255 255 / 0.9);
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 20px 40px rgba(107, 90, 195, 0.2);
            user-select: none;
        }
        h2 {
            text-align: center;
            color: #4b3b72;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 36px;
            letter-spacing: 0.02em;
        }
        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #3a2f6e;
            font-size: 15px;
        }
        input[type="text"],
        input[type="datetime-local"] {
            width: 100%;
            padding: 10px 14px;
            margin-top: 6px;
            margin-bottom: 20px;
            border: 1.5px solid #b2a0ff;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
            color: #4b3b72;
            background-color: #faf8ff;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="datetime-local"]:focus {
            outline: none;
            border-color: #9c84ff;
            background-color: #f0eaff;
            box-shadow: 0 0 8px rgba(156, 132, 255, 0.4);
        }
        button, a button {
            display: inline-block;
            padding: 12px 24px;
            font-weight: 700;
            font-size: 17px;
            border-radius: 16px;
            border: none;
            cursor: pointer;
            user-select: none;
            transition: box-shadow 0.3s ease, transform 0.25s ease, background-color 0.3s ease;
        }
        button[type="submit"] {
            background: linear-gradient(90deg, #a98eff, #f29fe1);
            color: #fff;
            box-shadow: 0 0 20px rgba(242, 159, 225, 0.7);
            margin-right: 15px;
            width: calc(50% - 10px);
        }
        button[type="submit"]:hover, button[type="submit"]:focus {
            box-shadow: 0 0 40px rgba(242, 159, 225, 1);
            transform: translateY(-4px) scale(1.06);
            outline: none;
        }
        a button {
            background-color: #c5c4d6;
            color: #4b3b72;
            width: calc(50% - 10px);
        }
        a button:hover, a button:focus {
            background-color: #a59fe1;
            box-shadow: 0 0 20px rgba(169, 142, 255, 0.8);
            transform: translateY(-4px) scale(1.06);
            outline: none;
        }
        .message {
            max-width: 420px;
            margin: 0 auto 20px;
            padding: 12px;
            background-color: #f9d8de;
            border: 1.5px solid #e0939b;
            color: #a94442;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            user-select: text;
            box-shadow: 0 2px 10px rgba(233, 147, 155, 0.3);
        }
        @media (max-width: 480px) {
            .container {
                padding: 30px 25px;
                max-width: 90%;
            }
            h2 {
                font-size: 24px;
                margin-bottom: 28px;
            }
            input[type="text"],
            input[type="datetime-local"] {
                font-size: 14px;
            }
            button, a button {
                width: 100%;
                margin-bottom: 12px;
            }
        }
    </style>
</head>
<body>

<canvas id="bgCanvas"></canvas>

<div class="container">
    <h2>Formulaire de demande (<?php echo htmlspecialchars($type); ?>)</h2>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
        <label>Nom :
            <input type="text" name="nom" required value="<?php echo isset($nom) ? htmlspecialchars($nom) : ''; ?>">
        </label>
        <label>Prénom :
            <input type="text" name="prenom" required value="<?php echo isset($prenom) ? htmlspecialchars($prenom) : ''; ?>">
        </label>
        <label>Matricule :
            <input type="text" name="matricule" required value="<?php echo isset($matricule) ? htmlspecialchars($matricule) : ''; ?>">
        </label>
        <label>Date et heure souhaitée :
            <input type="datetime-local" name="date_heure" required value="<?php echo isset($date_heure) ? htmlspecialchars($date_heure) : ''; ?>">
        </label>
        <label>Raison :
            <input type="text" name="raison" required value="<?php echo isset($raison) ? htmlspecialchars($raison) : ''; ?>">
        </label>
        <button type="submit">Envoyer</button>
        <a href="acceuil.php"><button type="button">Annuler</button></a>
    </form>
</div>

<script>
  const canvas = document.getElementById('bgCanvas');
  const ctx = canvas.getContext('2d');
  let width, height;
  const particleCount = 150;
  const maxDist = 130;
  let particles = [];

  // Position globale du fond (canvas) pour translation fluide
  let globalPos = { x: 0, y: 0 };
  let globalVel = { x: 0.15, y: 0.1 }; // vitesse de translation du fond

  function resize() {
    width = window.innerWidth;
    height = window.innerHeight;
    canvas.width = width * 1.1;  // un peu plus grand que la fenêtre
    canvas.height = height * 1.1;
  }
  window.addEventListener('resize', resize);
  resize();

  class Particle {
    constructor() {
      this.x = Math.random() * canvas.width;
      this.y = Math.random() * canvas.height;
      this.radius = 1.5 + Math.random() * 2.5;
      this.vx = (Math.random() - 0.5) * 1;  
      this.vy = (Math.random() - 0.5) * 1;
      this.opacity = Math.random() * 0.7 + 0.3;
      this.opacityDir = Math.random() > 0.5 ? 0.005 : -0.005;
      this.colorPhase = Math.random() * Math.PI * 2;
    }
    update() {
      this.x += this.vx;
      this.y += this.vy;

      // Rebond aux bords élargis
      if (this.x < 0 || this.x > canvas.width) this.vx = -this.vx;
      if (this.y < 0 || this.y > canvas.height) this.vy = -this.vy;

      this.opacity += this.opacityDir;
      if (this.opacity >= 0.9) this.opacityDir = -this.opacityDir;
      else if (this.opacity <= 0.3) this.opacityDir = -this.opacityDir;

      this.colorPhase += 0.02;
    }
    draw() {
      const r = 242 + Math.sin(this.colorPhase) * (169 - 242) * 0.5;
      const g = 159 + Math.sin(this.colorPhase) * (142 - 159) * 0.5;
      const b = 225 + Math.sin(this.colorPhase) * (255 - 225) * 0.5;
      const color = `rgba(${Math.round(r)}, ${Math.round(g)}, ${Math.round(b)}, ${this.opacity.toFixed(2)})`;

      const gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.radius * 5);
      gradient.addColorStop(0, color);
      gradient.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = gradient;
      ctx.shadowColor = color;
      ctx.shadowBlur = 10;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
      ctx.fill();
    }
  }

  function connectParticles() {
    for(let a = 0; a < particles.length; a++) {
      for(let b = a + 1; b < particles.length; b++) {
        let dx = particles[a].x - particles[b].x;
        let dy = particles[a].y - particles[b].y;
        let dist = Math.sqrt(dx * dx + dy * dy);
        if(dist < maxDist) {
          let alpha = (1 - dist / maxDist) * 0.3;
          ctx.strokeStyle = `rgba(200, 160, 240, ${alpha.toFixed(2)})`;
          ctx.lineWidth = 1;
          ctx.beginPath();
          ctx.moveTo(particles[a].x, particles[a].y);
          ctx.lineTo(particles[b].x, particles[b].y);
          ctx.stroke();
        }
      }
    }
  }

  function animate() {
    globalPos.x += globalVel.x;
    globalPos.y += globalVel.y;

    if(globalPos.x > canvas.width * 0.1) globalPos.x = 0;
    if(globalPos.y > canvas.height * 0.1) globalPos.y = 0;

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.save();
    ctx.translate(-globalPos.x, -globalPos.y);

    particles.forEach(p => {
      p.update();
      p.draw();
    });
    connectParticles();

    ctx.restore();

    requestAnimationFrame(animate);
  }

  function init() {
    particles = [];
    for(let i = 0; i < particleCount; i++) {
      particles.push(new Particle());
    }
  }

  init();
  animate();
</script>

</body>
</html>

