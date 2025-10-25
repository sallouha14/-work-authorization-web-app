
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Nexans Electrocontact - Demande d'autorisation</title>
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
    width: 110%; height: 110%; /* un peu plus grand pour éviter les bords vides */
    display: block;
    z-index: 0;
    will-change: transform;
    pointer-events: none; /* éviter de bloquer la souris */
  }
  .card {
    position: relative;
    z-index: 10;
    max-width: 420px;
    margin: auto;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255 255 255 / 0.9);
    border-radius: 20px;
    padding: 50px 40px;
    box-shadow: 0 20px 40px rgba(107, 90, 195, 0.2);
    text-align: center;
    user-select: none;
  }
  h1 {
    font-weight: 700;
    font-size: 28px;
    margin-bottom: 48px;
    letter-spacing: 0.02em;
    color: #4b3b72;
  }
  a {
    text-decoration: none;
  }
  .btn {
    display: block;
    width: 100%;
    padding: 14px 0;
    margin: 20px 0;
    font-weight: 700;
    font-size: 18px;
    border-radius: 16px;
    border: none;
    background: linear-gradient(90deg, #a98eff, #f29fe1);
    color: #fff;
    cursor: pointer;
    box-shadow: 0 0 15px rgba(169, 142, 255, 0.6);
    transition: box-shadow 0.3s ease, transform 0.25s ease;
    user-select: none;
  }
  .btn:hover, .btn:focus {
    box-shadow: 0 0 30px rgba(242, 159, 225, 0.9);
    transform: translateY(-5px) scale(1.05);
    outline: none;
  }
  footer {
    position: fixed;
    bottom: 16px;
    width: 100%;
    text-align: center;
    font-size: 13px;
    color: rgba(75, 59, 114, 0.6);
    font-style: italic;
    user-select: none;
    z-index: 10;
  }
  @media (max-width: 480px) {
    .card {
      padding: 36px 28px;
      max-width: 90%;
    }
    h1 {
      font-size: 24px;
      margin-bottom: 36px;
    }
    .btn {
      font-size: 16px;
      padding: 12px 0;
    }
  }
</style>
</head>
<body>

<canvas id="bgCanvas"></canvas>

<main class="card" role="main" aria-label="Accueil application demande d'autorisation Nexans Electrocontact">
  <h1>Bienvenue sur l'application<br>de demande d'autorisation</h1>
  <a href="type_demande.php"><button class="btn" type="button">Faire une demande</button></a>
  <a href="config.php"><button class="btn" type="button">Espace responsable</button></a>
</main>

<footer>
  &copy; 2025 Nexans Electrocontact – Application interne
</footer>

<script>
  const canvas = document.getElementById('bgCanvas');
  const ctx = canvas.getContext('2d');
  let width, height;
  const particleCount = 150;
  const maxDist = 130;
  let particles = [];

  // Position globale du fond (canvas) pour translation fluide
  let globalPos = { x: 0, y: 0 };
  let globalVel = { x: 0.15, y: 0.1 }; // vitesse de translation du fond (ajuste selon goût)

  function resize() {
    width = window.innerWidth;
    height = window.innerHeight;
    canvas.width = width * 1.1;  // un peu plus large pour éviter bord vide lors de translation
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

      // Rebond aux bords du canvas élargi
      if (this.x < 0 || this.x > canvas.width) this.vx = -this.vx;
      if (this.y < 0 || this.y > canvas.height) this.vy = -this.vy;

      // Variation d'opacité
      this.opacity += this.opacityDir;
      if (this.opacity >= 0.9) this.opacityDir = -this.opacityDir;
      else if (this.opacity <= 0.3) this.opacityDir = -this.opacityDir;

      // Variation couleur
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
    // Translation globale de la toile (on déplace tout ce qu'on dessine "à l'envers" pour simuler déplacement)
    globalPos.x += globalVel.x;
    globalPos.y += globalVel.y;

    // Reset la position pour boucle infinie (effet de déplacement continu en boucle)
    if(globalPos.x > canvas.width * 0.1) globalPos.x = 0;
    if(globalPos.y > canvas.height * 0.1) globalPos.y = 0;

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.save();
    // Décaler tout le dessin selon la position globale
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
