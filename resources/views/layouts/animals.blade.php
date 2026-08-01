{{-- 2D Animated Animals Scene --}}
<style>
  #animals-scene {
    position: relative;
    width: 100%;
    height: 90px;
    overflow: hidden;
    background: transparent;
    margin-bottom: 8px;
    border-radius: 12px;
    pointer-events: none;
  }
  #animals-canvas {
    width: 100%;
    height: 100%;
    display: block;
  }

  /* Shadow under each animal */
  .animal-shadow {
    position: absolute;
    bottom: 4px;
    width: 40px;
    height: 8px;
    background: rgba(0,0,0,0.15);
    border-radius: 50%;
    filter: blur(3px);
  }
</style>

<div id="animals-scene">
  <canvas id="animals-canvas"></canvas>
</div>

<script>
(function() {
  const canvas = document.getElementById('animals-canvas');
  const ctx = canvas.getContext('2d');

  function resize() {
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
  }
  resize();
  window.addEventListener('resize', resize);

  const W = () => canvas.width;
  const H = () => canvas.height;

  // ─── Drawing helpers ─────────────────────────────────────────────────

  // Smooth bezier curve helper
  function drawRoundedRect(ctx, x, y, w, h, r) {
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.lineTo(x + w - r, y);
    ctx.quadraticCurveTo(x + w, y, x + w, y + r);
    ctx.lineTo(x + w, y + h - r);
    ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
    ctx.lineTo(x + r, y + h);
    ctx.quadraticCurveTo(x, y + h, x, y + h - r);
    ctx.lineTo(x, y + r);
    ctx.quadraticCurveTo(x, y, x + r, y);
    ctx.closePath();
  }

  // ─── DOG ─────────────────────────────────────────────────────────────
  function drawDog(ctx, x, y, frame, flip) {
    ctx.save();
    if (flip) {
      ctx.scale(-1, 1);
      x = -x;
    }

    const legSwing = Math.sin(frame * 0.3) * 6;
    const bodyBob = Math.abs(Math.sin(frame * 0.3)) * 2;
    const tailWag = Math.sin(frame * 0.25) * 20;
    const scale = 1.0;

    ctx.save();
    ctx.translate(x, y - bodyBob);
    ctx.scale(scale, scale);

    // Shadow
    ctx.save();
    ctx.globalAlpha = 0.2;
    ctx.fillStyle = '#000';
    ctx.beginPath();
    ctx.ellipse(0, 32, 22, 5, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.globalAlpha = 1;
    ctx.restore();

    // Tail
    ctx.save();
    ctx.strokeStyle = '#c8860a';
    ctx.lineWidth = 5;
    ctx.lineCap = 'round';
    ctx.beginPath();
    ctx.moveTo(18, 10);
    const tailX = 18 + Math.cos((tailWag * Math.PI) / 180) * 16;
    const tailY = 10 + Math.sin((tailWag * Math.PI) / 180) * 16;
    ctx.quadraticCurveTo(28, 0, tailX, tailY);
    ctx.stroke();
    ctx.restore();

    // Body
    ctx.fillStyle = '#e09b2a';
    drawRoundedRect(ctx, -20, 0, 40, 22, 8);
    ctx.fill();

    // Belly
    ctx.fillStyle = '#f5c96e';
    ctx.beginPath();
    ctx.ellipse(0, 14, 12, 8, 0, 0, Math.PI * 2);
    ctx.fill();

    // Front legs
    ctx.fillStyle = '#c8860a';
    // Front-left
    ctx.save();
    ctx.translate(-10, 20);
    ctx.rotate((legSwing * Math.PI) / 180);
    drawRoundedRect(ctx, -4, 0, 8, 18, 3);
    ctx.fill();
    // Paw
    ctx.fillStyle = '#a06020';
    ctx.beginPath();
    ctx.ellipse(0, 18, 5, 3, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();

    // Front-right
    ctx.fillStyle = '#c8860a';
    ctx.save();
    ctx.translate(10, 20);
    ctx.rotate((-legSwing * Math.PI) / 180);
    drawRoundedRect(ctx, -4, 0, 8, 18, 3);
    ctx.fill();
    ctx.fillStyle = '#a06020';
    ctx.beginPath();
    ctx.ellipse(0, 18, 5, 3, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();

    // Head
    ctx.fillStyle = '#e09b2a';
    ctx.beginPath();
    ctx.arc(-2, -12, 16, 0, Math.PI * 2);
    ctx.fill();

    // Snout
    ctx.fillStyle = '#f5c96e';
    ctx.beginPath();
    ctx.ellipse(5, -6, 9, 7, 0.2, 0, Math.PI * 2);
    ctx.fill();

    // Nose
    ctx.fillStyle = '#3d1a00';
    ctx.beginPath();
    ctx.ellipse(8, -8, 4, 3, 0, 0, Math.PI * 2);
    ctx.fill();

    // Nose shine
    ctx.fillStyle = 'rgba(255,255,255,0.4)';
    ctx.beginPath();
    ctx.arc(7, -9, 1.5, 0, Math.PI * 2);
    ctx.fill();

    // Eye
    ctx.fillStyle = '#1a0a00';
    ctx.beginPath();
    ctx.arc(-4, -16, 3.5, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#fff';
    ctx.beginPath();
    ctx.arc(-3, -17, 1.2, 0, Math.PI * 2);
    ctx.fill();

    // Ear (floppy)
    const earDroop = Math.sin(frame * 0.3) * 3;
    ctx.fillStyle = '#b07018';
    ctx.save();
    ctx.translate(-14, -20);
    ctx.rotate(((-10 + earDroop) * Math.PI) / 180);
    drawRoundedRect(ctx, -6, 0, 12, 16, 6);
    ctx.fill();
    ctx.restore();

    // Right ear
    ctx.fillStyle = '#b07018';
    ctx.save();
    ctx.translate(8, -22);
    ctx.rotate(((5 - earDroop * 0.5) * Math.PI) / 180);
    drawRoundedRect(ctx, -4, 0, 9, 14, 5);
    ctx.fill();
    ctx.restore();

    // Spots
    ctx.fillStyle = 'rgba(160,90,10,0.35)';
    ctx.beginPath();
    ctx.ellipse(-8, 5, 5, 4, -0.3, 0, Math.PI * 2);
    ctx.fill();
    ctx.beginPath();
    ctx.ellipse(4, 8, 4, 3, 0.2, 0, Math.PI * 2);
    ctx.fill();

    ctx.restore(); // translate
    ctx.restore(); // flip
  }

  // ─── CAT ─────────────────────────────────────────────────────────────
  function drawCat(ctx, x, y, frame, flip) {
    ctx.save();
    if (flip) {
      ctx.scale(-1, 1);
      x = -x;
    }

    const legSwing = Math.sin(frame * 0.28) * 7;
    const bodyBob = Math.abs(Math.sin(frame * 0.28)) * 1.5;
    const tailCurl = Math.sin(frame * 0.18) * 15;

    ctx.save();
    ctx.translate(x, y - bodyBob);

    // Shadow
    ctx.save();
    ctx.globalAlpha = 0.2;
    ctx.fillStyle = '#000';
    ctx.beginPath();
    ctx.ellipse(0, 31, 18, 4, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.globalAlpha = 1;
    ctx.restore();

    // Tail (curly)
    ctx.save();
    ctx.strokeStyle = '#888';
    ctx.lineWidth = 5;
    ctx.lineCap = 'round';
    ctx.beginPath();
    ctx.moveTo(18, 8);
    const cp1x = 30 + tailCurl;
    const cp1y = -5;
    const cp2x = 35 + tailCurl * 0.5;
    const cp2y = -20;
    ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, 32 + tailCurl, -25);
    ctx.stroke();
    ctx.restore();

    // Body
    ctx.fillStyle = '#aaa';
    drawRoundedRect(ctx, -18, 0, 36, 20, 9);
    ctx.fill();

    // Belly
    ctx.fillStyle = '#e8e8e8';
    ctx.beginPath();
    ctx.ellipse(0, 13, 10, 7, 0, 0, Math.PI * 2);
    ctx.fill();

    // Legs
    ctx.fillStyle = '#999';
    // Front-left
    ctx.save();
    ctx.translate(-8, 18);
    ctx.rotate((legSwing * Math.PI) / 180);
    drawRoundedRect(ctx, -3.5, 0, 7, 16, 3);
    ctx.fill();
    ctx.fillStyle = '#e8e8e8';
    ctx.beginPath();
    ctx.ellipse(0, 16, 4, 3, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();

    // Front-right
    ctx.fillStyle = '#999';
    ctx.save();
    ctx.translate(8, 18);
    ctx.rotate((-legSwing * Math.PI) / 180);
    drawRoundedRect(ctx, -3.5, 0, 7, 16, 3);
    ctx.fill();
    ctx.fillStyle = '#e8e8e8';
    ctx.beginPath();
    ctx.ellipse(0, 16, 4, 3, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();

    // Head
    ctx.fillStyle = '#aaa';
    ctx.beginPath();
    ctx.arc(0, -13, 15, 0, Math.PI * 2);
    ctx.fill();

    // Ears (pointed)
    ctx.fillStyle = '#aaa';
    ctx.beginPath();
    ctx.moveTo(-12, -22);
    ctx.lineTo(-18, -36);
    ctx.lineTo(-4, -26);
    ctx.closePath();
    ctx.fill();

    ctx.beginPath();
    ctx.moveTo(8, -23);
    ctx.lineTo(16, -36);
    ctx.lineTo(2, -27);
    ctx.closePath();
    ctx.fill();

    // Inner ear
    ctx.fillStyle = '#ffb3c6';
    ctx.beginPath();
    ctx.moveTo(-11, -24);
    ctx.lineTo(-16, -33);
    ctx.lineTo(-6, -26);
    ctx.closePath();
    ctx.fill();

    ctx.beginPath();
    ctx.moveTo(9, -25);
    ctx.lineTo(14, -33);
    ctx.lineTo(4, -27);
    ctx.closePath();
    ctx.fill();

    // Muzzle
    ctx.fillStyle = '#e8e8e8';
    ctx.beginPath();
    ctx.ellipse(0, -8, 7, 5, 0, 0, Math.PI * 2);
    ctx.fill();

    // Nose
    ctx.fillStyle = '#ff8fad';
    ctx.beginPath();
    ctx.moveTo(0, -9);
    ctx.lineTo(-3, -7);
    ctx.lineTo(3, -7);
    ctx.closePath();
    ctx.fill();

    // Mouth
    ctx.strokeStyle = '#666';
    ctx.lineWidth = 1.2;
    ctx.lineCap = 'round';
    ctx.beginPath();
    ctx.moveTo(0, -7);
    ctx.quadraticCurveTo(-4, -4, -6, -5);
    ctx.moveTo(0, -7);
    ctx.quadraticCurveTo(4, -4, 6, -5);
    ctx.stroke();

    // Eyes
    const blinkOpen = (Math.floor(frame / 80) % 8 === 0) ? 0.5 : 1; // blink
    ctx.fillStyle = '#2a7a2a';
    ctx.save();
    ctx.scale(1, blinkOpen);
    ctx.beginPath();
    ctx.ellipse(-5, -14, 4, 4, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.beginPath();
    ctx.ellipse(5, -14, 4, 4, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();

    // Pupil
    ctx.fillStyle = '#111';
    ctx.save();
    ctx.scale(1, blinkOpen);
    ctx.beginPath();
    ctx.ellipse(-5, -14, 2, 3.5, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.beginPath();
    ctx.ellipse(5, -14, 2, 3.5, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();

    // Eye shine
    ctx.fillStyle = 'rgba(255,255,255,0.6)';
    ctx.beginPath();
    ctx.arc(-4, -15, 1.2, 0, Math.PI * 2);
    ctx.fill();
    ctx.beginPath();
    ctx.arc(6, -15, 1.2, 0, Math.PI * 2);
    ctx.fill();

    // Whiskers
    ctx.strokeStyle = 'rgba(100,100,100,0.7)';
    ctx.lineWidth = 0.8;
    // Left whiskers
    for (let i = -1; i <= 1; i++) {
      ctx.beginPath();
      ctx.moveTo(-7, -8 + i * 2);
      ctx.lineTo(-20, -9 + i * 3);
      ctx.stroke();
    }
    // Right whiskers
    for (let i = -1; i <= 1; i++) {
      ctx.beginPath();
      ctx.moveTo(7, -8 + i * 2);
      ctx.lineTo(20, -9 + i * 3);
      ctx.stroke();
    }

    // Stripes on body
    ctx.strokeStyle = 'rgba(130,130,130,0.4)';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.beginPath();
    ctx.moveTo(-5, 2);
    ctx.quadraticCurveTo(-3, 10, -6, 18);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(5, 2);
    ctx.quadraticCurveTo(3, 10, 6, 18);
    ctx.stroke();

    ctx.restore(); // translate
    ctx.restore(); // flip
  }

  // ─── DINOSAUR ────────────────────────────────────────────────────────
  function drawDino(ctx, x, y, frame, flip) {
    ctx.save();
    if (flip) {
      ctx.scale(-1, 1);
      x = -x;
    }

    const legSwing = Math.sin(frame * 0.22) * 12;
    const bodyBob = Math.abs(Math.sin(frame * 0.22)) * 3;
    const tailSwing = Math.sin(frame * 0.18) * 8;
    const armSwing = Math.sin(frame * 0.22 + 1) * 8;

    ctx.save();
    ctx.translate(x, y - bodyBob);

    // Shadow
    ctx.save();
    ctx.globalAlpha = 0.2;
    ctx.fillStyle = '#000';
    ctx.beginPath();
    ctx.ellipse(0, 35, 20, 5, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.globalAlpha = 1;
    ctx.restore();

    // Tail
    ctx.save();
    ctx.fillStyle = '#4caf50';
    ctx.beginPath();
    ctx.moveTo(18, 8);
    ctx.quadraticCurveTo(36, 12 + tailSwing, 48, 20 + tailSwing * 0.5);
    ctx.quadraticCurveTo(52, 22, 50, 24);
    ctx.quadraticCurveTo(34, 16 + tailSwing * 0.8, 18, 14);
    ctx.closePath();
    ctx.fill();
    ctx.restore();

    // Body
    const bodyGrad = ctx.createLinearGradient(-20, -5, 20, 25);
    bodyGrad.addColorStop(0, '#66bb6a');
    bodyGrad.addColorStop(1, '#388e3c');
    ctx.fillStyle = bodyGrad;
    drawRoundedRect(ctx, -20, -5, 40, 32, 10);
    ctx.fill();

    // Belly
    ctx.fillStyle = '#a5d6a7';
    ctx.beginPath();
    ctx.ellipse(2, 14, 12, 16, 0.1, 0, Math.PI * 2);
    ctx.fill();

    // Back spines
    ctx.fillStyle = '#2e7d32';
    for (let i = 0; i < 4; i++) {
      const sx = -10 + i * 7;
      const sh = 8 + (i % 2) * 4;
      ctx.beginPath();
      ctx.moveTo(sx, -5);
      ctx.lineTo(sx - 4, -5 - sh);
      ctx.lineTo(sx + 4, -5 - sh + 3);
      ctx.closePath();
      ctx.fill();
    }

    // Arms (tiny T-rex style)
    ctx.fillStyle = '#4caf50';
    ctx.save();
    ctx.translate(-12, 5);
    ctx.rotate((armSwing * Math.PI) / 180);
    drawRoundedRect(ctx, -4, 0, 8, 12, 3);
    ctx.fill();
    // Claws
    ctx.strokeStyle = '#2e7d32';
    ctx.lineWidth = 1.5;
    ctx.lineCap = 'round';
    ctx.beginPath(); ctx.moveTo(-2, 12); ctx.lineTo(-5, 16); ctx.stroke();
    ctx.beginPath(); ctx.moveTo(2, 12); ctx.lineTo(2, 16); ctx.stroke();
    ctx.restore();

    // Legs (bigger)
    ctx.fillStyle = '#388e3c';
    // Left leg
    ctx.save();
    ctx.translate(-8, 25);
    ctx.rotate((legSwing * Math.PI) / 180);
    drawRoundedRect(ctx, -6, 0, 12, 20, 4);
    ctx.fill();
    // Foot
    ctx.fillStyle = '#2e7d32';
    ctx.beginPath();
    ctx.ellipse(0, 20, 8, 4, 0, 0, Math.PI * 2);
    ctx.fill();
    // Toes
    ctx.strokeStyle = '#1b5e20';
    ctx.lineWidth = 1.5;
    ctx.lineCap = 'round';
    ctx.beginPath(); ctx.moveTo(-6, 20); ctx.lineTo(-9, 24); ctx.stroke();
    ctx.beginPath(); ctx.moveTo(0, 20); ctx.lineTo(0, 25); ctx.stroke();
    ctx.beginPath(); ctx.moveTo(6, 20); ctx.lineTo(8, 24); ctx.stroke();
    ctx.restore();

    // Right leg
    ctx.fillStyle = '#388e3c';
    ctx.save();
    ctx.translate(8, 25);
    ctx.rotate((-legSwing * Math.PI) / 180);
    drawRoundedRect(ctx, -6, 0, 12, 20, 4);
    ctx.fill();
    ctx.fillStyle = '#2e7d32';
    ctx.beginPath();
    ctx.ellipse(0, 20, 8, 4, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.strokeStyle = '#1b5e20';
    ctx.lineWidth = 1.5;
    ctx.lineCap = 'round';
    ctx.beginPath(); ctx.moveTo(-6, 20); ctx.lineTo(-9, 24); ctx.stroke();
    ctx.beginPath(); ctx.moveTo(0, 20); ctx.lineTo(0, 25); ctx.stroke();
    ctx.beginPath(); ctx.moveTo(6, 20); ctx.lineTo(8, 24); ctx.stroke();
    ctx.restore();

    // Neck
    ctx.fillStyle = '#66bb6a';
    ctx.beginPath();
    ctx.moveTo(-8, -5);
    ctx.quadraticCurveTo(-10, -22, -4, -28);
    ctx.quadraticCurveTo(4, -32, 8, -22);
    ctx.quadraticCurveTo(10, -10, 8, -5);
    ctx.closePath();
    ctx.fill();

    // Head
    const headGrad = ctx.createRadialGradient(-2, -35, 2, -2, -35, 18);
    headGrad.addColorStop(0, '#81c784');
    headGrad.addColorStop(1, '#43a047');
    ctx.fillStyle = headGrad;
    ctx.beginPath();
    ctx.ellipse(-2, -35, 18, 14, -0.2, 0, Math.PI * 2);
    ctx.fill();

    // Snout / jaw
    ctx.fillStyle = '#66bb6a';
    ctx.beginPath();
    ctx.moveTo(8, -35);
    ctx.quadraticCurveTo(22, -35, 22, -28);
    ctx.quadraticCurveTo(22, -22, 10, -22);
    ctx.closePath();
    ctx.fill();

    // Jaw (open slightly when walking)
    const jawOpen = Math.abs(Math.sin(frame * 0.22)) * 4;
    ctx.fillStyle = '#4caf50';
    ctx.beginPath();
    ctx.moveTo(8, -28);
    ctx.quadraticCurveTo(22, -28 + jawOpen, 22, -24 + jawOpen);
    ctx.quadraticCurveTo(16, -22 + jawOpen, 8, -24 + jawOpen);
    ctx.closePath();
    ctx.fill();

    // Teeth
    ctx.fillStyle = '#fff';
    for (let t = 0; t < 3; t++) {
      ctx.beginPath();
      ctx.moveTo(10 + t * 4, -28);
      ctx.lineTo(11 + t * 4, -25);
      ctx.lineTo(12 + t * 4, -28);
      ctx.closePath();
      ctx.fill();
    }

    // Nostril
    ctx.fillStyle = '#2e7d32';
    ctx.beginPath();
    ctx.ellipse(16, -34, 2, 1.5, -0.3, 0, Math.PI * 2);
    ctx.fill();

    // Eye
    ctx.fillStyle = '#fff';
    ctx.beginPath();
    ctx.arc(-6, -38, 5, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#f57f17';
    ctx.beginPath();
    ctx.arc(-6, -38, 3.5, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#111';
    ctx.beginPath();
    ctx.arc(-5.5, -38, 2, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = 'rgba(255,255,255,0.7)';
    ctx.beginPath();
    ctx.arc(-4.5, -39, 1, 0, Math.PI * 2);
    ctx.fill();

    // Scales pattern
    ctx.strokeStyle = 'rgba(56,142,60,0.4)';
    ctx.lineWidth = 1;
    for (let i = 0; i < 3; i++) {
      ctx.beginPath();
      ctx.arc(-5 + i * 8, 5 + i * 4, 5, 0.3, Math.PI - 0.3);
      ctx.stroke();
    }

    ctx.restore(); // translate
    ctx.restore(); // flip
  }

  // ─── Grass / Ground decoration ───────────────────────────────────────
  function drawGround(ctx, w, h) {
    // Grass blades
    ctx.strokeStyle = '#6abf5e';
    ctx.lineWidth = 1.5;
    ctx.lineCap = 'round';
    const bladePositions = [30, 90, 155, 200, 280, 340, 410, 470, 530];
    bladePositions.forEach(bx => {
      if (bx > w) return;
      const t = Date.now() / 1000;
      const sway = Math.sin(t * 2 + bx * 0.05) * 2;
      ctx.beginPath();
      ctx.moveTo(bx, h - 4);
      ctx.quadraticCurveTo(bx + sway, h - 12, bx + sway * 2, h - 18);
      ctx.stroke();
      ctx.beginPath();
      ctx.moveTo(bx + 6, h - 4);
      ctx.quadraticCurveTo(bx + 6 + sway * 0.8, h - 10, bx + 6 + sway * 1.5, h - 15);
      ctx.stroke();
    });

    // Ground line
    ctx.strokeStyle = 'rgba(0,0,0,0.08)';
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(0, h - 4);
    ctx.lineTo(w, h - 4);
    ctx.stroke();
  }

  // ─── Clouds ──────────────────────────────────────────────────────────
  let cloudOffset = 0;
  function drawCloud(ctx, x, y, size) {
    ctx.fillStyle = 'rgba(255,255,255,0.6)';
    ctx.beginPath();
    ctx.arc(x, y, size, 0, Math.PI * 2);
    ctx.arc(x + size * 0.9, y - size * 0.3, size * 0.75, 0, Math.PI * 2);
    ctx.arc(x + size * 1.7, y, size * 0.85, 0, Math.PI * 2);
    ctx.arc(x + size * 0.85, y + size * 0.3, size * 0.7, 0, Math.PI * 2);
    ctx.fill();
  }

  // ─── Animal state ────────────────────────────────────────────────────
  const animals = [
    {
      name: 'dog',
      x: -60,
      speed: 1.2,
      flip: false,
      draw: drawDog,
      label: '🐶 Dog',
      labelColor: '#c8860a',
      yOffset: 0,
    },
    {
      name: 'cat',
      x: -160,
      speed: 1.0,
      flip: false,
      draw: drawCat,
      label: '🐱 Cat',
      labelColor: '#888',
      yOffset: 0,
    },
    {
      name: 'dino',
      x: -280,
      speed: 0.85,
      flip: false,
      draw: drawDino,
      label: '🦕 Dino',
      labelColor: '#4caf50',
      yOffset: -5,
    },
  ];

  let frame = 0;

  function loop() {
    const w = canvas.width;
    const h = canvas.height;

    ctx.clearRect(0, 0, w, h);

    // Subtle background gradient
    const bg = ctx.createLinearGradient(0, 0, 0, h);
    bg.addColorStop(0, 'rgba(255,248,220,0.0)');
    bg.addColorStop(1, 'rgba(200,240,200,0.18)');
    ctx.fillStyle = bg;
    ctx.fillRect(0, 0, w, h);

    // Clouds
    cloudOffset = (cloudOffset + 0.15) % (w + 100);
    if (w > 300) {
      drawCloud(ctx, cloudOffset - 50, 14, 10);
      drawCloud(ctx, (cloudOffset + w * 0.4) % (w + 100) - 50, 10, 8);
      drawCloud(ctx, (cloudOffset + w * 0.7) % (w + 100) - 50, 16, 7);
    }

    // Ground
    drawGround(ctx, w, h);

    // Ground level Y (animals stand on)
    const groundY = h - 22;

    // Update & draw each animal
    animals.forEach(animal => {
      animal.x += animal.speed;

      // When animal walks off right edge, reset to left
      if (animal.x > w + 80) {
        animal.x = -80;
      }

      const drawX = animal.x;
      const drawY = groundY + animal.yOffset;

      animal.draw(ctx, drawX, drawY, frame, false);

      // Label above animal
      if (animal.x > 20 && animal.x < w - 20) {
        ctx.save();
        ctx.globalAlpha = 0.85;
        ctx.font = 'bold 9px Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillStyle = animal.labelColor;
        ctx.fillText(animal.name.toUpperCase(), animal.x, groundY - 48 + animal.yOffset);
        ctx.globalAlpha = 1;
        ctx.restore();
      }
    });

    frame++;
    requestAnimationFrame(loop);
  }

  // Start after a small delay to ensure canvas is properly sized
  setTimeout(() => {
    resize();
    loop();
  }, 100);
})();
</script>
