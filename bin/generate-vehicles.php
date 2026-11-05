<?php
declare(strict_types=1);

/**
 * Creates high-fidelity SVG/PNG graphic assets for the vehicles and temple
 * to match the reference UI/UX mockup perfectly.
 */

$root = dirname(__DIR__);
$dir = $root . '/public/assets/images/vehicles';
if (!is_dir($dir)) {
    @mkdir($dir, 0755, true);
}

// 1. Force Urbania SVG (Dark metallic grey, modern luxury van)
$urbaniaSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 360" width="100%" height="100%">
  <defs>
    <linearGradient id="bodyGradUrbania" x1="0%" y1="0%" x2="100%" y2="50%">
      <stop offset="0%" stop-color="#363d47"/>
      <stop offset="50%" stop-color="#222831"/>
      <stop offset="100%" stop-color="#14181d"/>
    </linearGradient>
    <linearGradient id="glassGrad" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#1a2530" stop-opacity="0.9"/>
      <stop offset="100%" stop-color="#0a0f14" stop-opacity="0.95"/>
    </linearGradient>
    <linearGradient id="chromeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#cfd6df"/>
      <stop offset="50%" stop-color="#ffffff"/>
      <stop offset="100%" stop-color="#9aa5b5"/>
    </linearGradient>
    <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="15" stdDeviation="12" flood-color="#000000" flood-opacity="0.35"/>
    </filter>
  </defs>

  <!-- Ground Shadow -->
  <ellipse cx="300" cy="325" rx="260" ry="22" fill="#000000" opacity="0.35" filter="blur(8px)"/>

  <!-- Main Van Body -->
  <g filter="url(#shadow)">
    <!-- Chassis & Cab -->
    <path d="M 80,240 
             C 80,210 90,130 140,110
             L 240,90
             L 480,90
             C 520,90 535,115 535,150
             L 535,270
             C 535,280 525,285 500,285
             L 450,285
             A 45,45 0 0,0 360,285
             L 200,285
             A 45,45 0 0,0 110,285
             L 85,285
             C 80,285 75,275 75,260
             Z" fill="url(#bodyGradUrbania)" stroke="#1a2028" stroke-width="2"/>

    <!-- Roof AC Unit -->
    <path d="M 220,88 L 460,88 L 450,75 L 230,75 Z" fill="#252a32" rx="4"/>

    <!-- Windshield & Windows -->
    <!-- Front Windshield -->
    <path d="M 145,118 L 225,102 L 225,180 L 125,185 C 130,150 135,130 145,118 Z" fill="url(#glassGrad)"/>
    <!-- Passenger Side Windows (Tinted Panorama) -->
    <path d="M 235,102 L 315,102 L 315,180 L 235,180 Z" fill="url(#glassGrad)"/>
    <path d="M 323,102 L 405,102 L 405,180 L 323,180 Z" fill="url(#glassGrad)"/>
    <path d="M 413,102 L 515,105 C 522,120 522,150 520,180 L 413,180 Z" fill="url(#glassGrad)"/>

    <!-- Window Pillars & Dividers -->
    <rect x="227" y="100" width="6" height="82" fill="#14181d"/>
    <rect x="317" y="100" width="6" height="82" fill="#14181d"/>
    <rect x="407" y="100" width="6" height="82" fill="#14181d"/>

    <!-- Modern LED Headlamp -->
    <polygon points="80,215 115,210 112,230 82,235" fill="url(#chromeGrad)"/>
    <polygon points="84,218 108,214 106,226 86,229" fill="#00e5ff" opacity="0.8"/>

    <!-- Urbania Signature Chrome Front Grille -->
    <path d="M 76,238 L 100,238 L 98,265 L 75,265 Z" fill="#161a20"/>
    <line x1="77" y1="244" x2="98" y2="244" stroke="url(#chromeGrad)" stroke-width="3"/>
    <line x1="76" y1="251" x2="97" y2="251" stroke="url(#chromeGrad)" stroke-width="3"/>
    <line x1="75" y1="258" x2="96" y2="258" stroke="url(#chromeGrad)" stroke-width="3"/>

    <!-- Urbania Chrome Badge -->
    <rect x="140" y="215" width="60" height="7" rx="3" fill="url(#chromeGrad)"/>
    <text x="170" y="221" font-family="Arial, sans-serif" font-size="6" font-weight="bold" fill="#14181d" text-anchor="middle" letter-spacing="1">URBANIA</text>

    <!-- Side Character Crease Line -->
    <line x1="110" y1="205" x2="525" y2="205" stroke="#485362" stroke-width="2"/>
    <line x1="112" y1="207" x2="525" y2="207" stroke="#0e1217" stroke-width="1.5"/>

    <!-- Front & Rear Wheels -->
    <!-- Front Wheel -->
    <circle cx="155" cy="285" r="42" fill="#181c22"/>
    <circle cx="155" cy="285" r="32" fill="#2d3540"/>
    <!-- Alloy Spokes -->
    <circle cx="155" cy="285" r="26" fill="#161a20" stroke="url(#chromeGrad)" stroke-width="3"/>
    <circle cx="155" cy="285" r="10" fill="url(#chromeGrad)"/>

    <!-- Rear Wheel -->
    <circle cx="405" cy="285" r="42" fill="#181c22"/>
    <circle cx="405" cy="285" r="32" fill="#2d3540"/>
    <circle cx="405" cy="285" r="26" fill="#161a20" stroke="url(#chromeGrad)" stroke-width="3"/>
    <circle cx="405" cy="285" r="10" fill="url(#chromeGrad)"/>

    <!-- Door Handles -->
    <rect x="235" y="210" width="22" height="6" rx="3" fill="url(#chromeGrad)"/>
    <rect x="330" y="210" width="22" height="6" rx="3" fill="url(#chromeGrad)"/>

    <!-- Wing Mirror -->
    <path d="M 130,175 C 120,170 110,175 110,185 L 125,188 Z" fill="#2d3540" stroke="#14181d"/>
  </g>
</svg>
SVG;
file_put_contents($dir . '/urbania.svg', $urbaniaSvg);

// 2. Force Tempo Traveller SVG (White with yellow/orange side stripe)
$tempoSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 360" width="100%" height="100%">
  <defs>
    <linearGradient id="bodyGradWhite" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ffffff"/>
      <stop offset="60%" stop-color="#f2f5f9"/>
      <stop offset="100%" stop-color="#d6dde6"/>
    </linearGradient>
    <linearGradient id="glassTempo" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#243444" stop-opacity="0.85"/>
      <stop offset="100%" stop-color="#121b24" stop-opacity="0.95"/>
    </linearGradient>
  </defs>

  <!-- Shadow -->
  <ellipse cx="305" cy="325" rx="255" ry="20" fill="#000000" opacity="0.32" filter="blur(7px)"/>

  <!-- High Roof Tempo Body -->
  <g>
    <!-- Roof Carrier -->
    <rect x="220" y="60" width="270" height="20" fill="#3a4450" rx="4"/>
    <line x1="240" y1="60" x2="240" y2="80" stroke="#2a333d" stroke-width="3"/>
    <line x1="300" y1="60" x2="300" y2="80" stroke="#2a333d" stroke-width="3"/>
    <line x1="360" y1="60" x2="360" y2="80" stroke="#2a333d" stroke-width="3"/>
    <line x1="420" y1="60" x2="420" y2="80" stroke="#2a333d" stroke-width="3"/>

    <!-- Van Shell -->
    <path d="M 85,245 
             C 85,215 95,145 135,115
             L 200,80
             L 515,80
             C 530,80 540,95 540,120
             L 540,270
             C 540,280 530,285 505,285
             L 455,285
             A 44,44 0 0,0 367,285
             L 205,285
             A 44,44 0 0,0 117,285
             L 90,285
             C 85,285 82,275 82,260
             Z" fill="url(#bodyGradWhite)" stroke="#b0bac6" stroke-width="2"/>

    <!-- Windows Strip -->
    <path d="M 130,125 L 195,100 L 195,185 L 115,185 C 120,155 125,135 130,125 Z" fill="url(#glassTempo)"/>
    <rect x="203" y="100" width="70" height="85" fill="url(#glassTempo)"/>
    <rect x="279" y="100" width="70" height="85" fill="url(#glassTempo)"/>
    <rect x="355" y="100" width="70" height="85" fill="url(#glassTempo)"/>
    <rect x="431" y="100" width="95" height="85" fill="url(#glassTempo)"/>

    <!-- Classic Tempo Yellow/Orange Touring Decal -->
    <polygon points="120,215 535,215 535,230 115,230" fill="#f59e0b"/>
    <polygon points="125,233 535,233 535,238 120,238" fill="#1e293b"/>

    <!-- "TRAVELLER" Text Graphic on side -->
    <text x="350" y="227" font-family="'Segoe UI', Arial, sans-serif" font-weight="900" font-style="italic" font-size="11" fill="#ffffff" letter-spacing="2">TRAVELLER</text>

    <!-- Front Grille & Headlamp -->
    <rect x="83" y="215" width="28" height="35" rx="3" fill="#cbd5e1" stroke="#94a3b8"/>
    <circle cx="95" cy="230" r="10" fill="#ffffff" stroke="#cbd5e1" stroke-width="2"/>
    <circle cx="95" cy="230" r="6" fill="#fef08a"/>

    <!-- Bumpers -->
    <path d="M 78,255 L 105,255 L 103,285 L 80,285 Z" fill="#334155"/>

    <!-- Wheels -->
    <circle cx="161" cy="285" r="41" fill="#1e293b"/>
    <circle cx="161" cy="285" r="30" fill="#64748b"/>
    <circle cx="161" cy="285" r="16" fill="#f1f5f9" stroke="#94a3b8" stroke-width="3"/>
    <circle cx="161" cy="285" r="8" fill="#1e293b"/>

    <circle cx="411" cy="285" r="41" fill="#1e293b"/>
    <circle cx="411" cy="285" r="30" fill="#64748b"/>
    <circle cx="411" cy="285" r="16" fill="#f1f5f9" stroke="#94a3b8" stroke-width="3"/>
    <circle cx="411" cy="285" r="8" fill="#1e293b"/>
  </g>
</svg>
SVG;
file_put_contents($dir . '/tempo-traveller.svg', $tempoSvg);

// 3. Toyota Innova Crysta SVG (White executive MPV)
$innovaSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 360" width="100%" height="100%">
  <defs>
    <linearGradient id="bodyInnova" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ffffff"/>
      <stop offset="50%" stop-color="#f0f3f8"/>
      <stop offset="100%" stop-color="#cbd5e1"/>
    </linearGradient>
    <linearGradient id="glassInnova" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#1e293b" stop-opacity="0.85"/>
      <stop offset="100%" stop-color="#0f172a" stop-opacity="0.95"/>
    </linearGradient>
    <linearGradient id="chrome" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#cbd5e1"/>
      <stop offset="50%" stop-color="#ffffff"/>
      <stop offset="100%" stop-color="#94a3b8"/>
    </linearGradient>
  </defs>

  <ellipse cx="300" cy="315" rx="250" ry="18" fill="#000000" opacity="0.3" filter="blur(6px)"/>

  <!-- Innova Crysta Sleek Body -->
  <g>
    <path d="M 70,265
             C 70,250 85,225 110,210
             L 190,135
             C 215,120 260,115 320,115
             L 470,120
             C 505,125 530,165 540,210
             L 540,270
             C 540,278 528,282 500,282
             L 455,282
             A 42,42 0 0,0 371,282
             L 210,282
             A 42,42 0 0,0 126,282
             L 80,282
             C 70,282 70,275 70,265 Z" fill="url(#bodyInnova)" stroke="#94a3b8" stroke-width="2"/>

    <!-- Front & Side Glasshouse -->
    <path d="M 190,145 L 260,128 L 260,190 L 140,195 Z" fill="url(#glassInnova)"/>
    <path d="M 268,128 L 365,128 L 365,190 L 268,190 Z" fill="url(#glassInnova)"/>
    <path d="M 373,128 L 480,132 C 495,150 500,175 498,190 L 373,190 Z" fill="url(#glassInnova)"/>

    <!-- Bold Chrome Hexagonal Front Grille -->
    <polygon points="75,230 115,220 110,260 78,260" fill="#1e293b"/>
    <line x1="80" y1="235" x2="112" y2="228" stroke="url(#chrome)" stroke-width="3"/>
    <line x1="79" y1="243" x2="110" y2="238" stroke="url(#chrome)" stroke-width="3"/>
    <line x1="78" y1="251" x2="108" y2="248" stroke="url(#chrome)" stroke-width="3"/>

    <!-- Sleek Projector Headlight -->
    <polygon points="85,215 125,200 120,225 82,230" fill="url(#chrome)"/>
    <circle cx="105" cy="215" r="7" fill="#38bdf8"/>

    <!-- Sharp Side Aerodynamic Character Lines -->
    <path d="M 125,215 Q 310,210 520,205" fill="none" stroke="#ffffff" stroke-width="3"/>
    <path d="M 125,217 Q 310,212 520,207" fill="none" stroke="#94a3b8" stroke-width="1.5"/>

    <!-- Alloy Wheels (Diamond Cut) -->
    <circle cx="168" cy="282" r="38" fill="#0f172a"/>
    <circle cx="168" cy="282" r="28" fill="#475569"/>
    <circle cx="168" cy="282" r="23" fill="#0f172a" stroke="url(#chrome)" stroke-width="4"/>
    <circle cx="168" cy="282" r="8" fill="url(#chrome)"/>

    <circle cx="413" cy="282" r="38" fill="#0f172a"/>
    <circle cx="413" cy="282" r="28" fill="#475569"/>
    <circle cx="413" cy="282" r="23" fill="#0f172a" stroke="url(#chrome)" stroke-width="4"/>
    <circle cx="413" cy="282" r="8" fill="url(#chrome)"/>

    <!-- Chrome Door Handles -->
    <rect x="278" y="210" width="20" height="5" rx="2" fill="url(#chrome)"/>
    <rect x="382" y="210" width="20" height="5" rx="2" fill="url(#chrome)"/>
  </g>
</svg>
SVG;
file_put_contents($dir . '/innova-crysta.svg', $innovaSvg);

// 4. Toyota Fortuner SVG (Aggressive muscular white SUV)
$fortunerSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 360" width="100%" height="100%">
  <defs>
    <linearGradient id="bodyFortuner" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ffffff"/>
      <stop offset="45%" stop-color="#f1f5f9"/>
      <stop offset="100%" stop-color="#cbd5e1"/>
    </linearGradient>
    <linearGradient id="glassFortuner" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#0f172a" stop-opacity="0.9"/>
      <stop offset="100%" stop-color="#020617" stop-opacity="0.95"/>
    </linearGradient>
    <linearGradient id="chrome" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#cbd5e1"/>
      <stop offset="50%" stop-color="#ffffff"/>
      <stop offset="100%" stop-color="#94a3b8"/>
    </linearGradient>
  </defs>

  <!-- Heavy High-Stance SUV Shadow -->
  <ellipse cx="300" cy="320" rx="245" ry="18" fill="#000000" opacity="0.35" filter="blur(7px)"/>

  <!-- High Ground Clearance SUV Body -->
  <g>
    <!-- Roof Rails -->
    <path d="M 230,105 L 450,110" stroke="#475569" stroke-width="6" stroke-linecap="round"/>

    <!-- High Body Shell -->
    <path d="M 65,255
             C 65,230 75,200 100,185
             L 190,120
             C 215,110 250,110 320,110
             L 470,115
             C 505,120 535,160 545,210
             L 545,265
             C 545,275 530,278 495,278
             L 455,278
             A 48,48 0 0,0 359,278
             L 220,278
             A 48,48 0 0,0 124,278
             L 75,278
             C 65,278 65,268 65,255 Z" fill="url(#bodyFortuner)" stroke="#94a3b8" stroke-width="2.5"/>

    <!-- Aggressive Black Front Fascia & Chrome Wave Grille -->
    <polygon points="68,205 110,195 105,255 70,255" fill="#0f172a"/>
    <path d="M 72,215 Q 95,210 108,212" stroke="url(#chrome)" stroke-width="4" fill="none"/>
    <path d="M 71,226 Q 95,221 107,223" stroke="url(#chrome)" stroke-width="3" fill="none"/>
    <path d="M 70,237 Q 95,232 106,234" stroke="url(#chrome)" stroke-width="3" fill="none"/>

    <!-- Sharp LED DRL Headlamps -->
    <polygon points="80,190 125,178 120,205 78,210" fill="url(#chrome)"/>
    <polygon points="85,193 118,184 115,200 82,204" fill="#38bdf8"/>

    <!-- Windows Frame -->
    <path d="M 190,130 L 265,120 L 265,178 L 135,178 Z" fill="url(#glassFortuner)"/>
    <path d="M 273,120 L 370,120 L 370,178 L 273,178 Z" fill="url(#glassFortuner)"/>
    <path d="M 378,120 L 475,125 C 495,145 500,165 498,178 L 378,178 Z" fill="url(#glassFortuner)"/>

    <!-- Muscular Flared Wheel Arches -->
    <path d="M 115,278 A 54,54 0 0,1 233,278" fill="none" stroke="#334155" stroke-width="6"/>
    <path d="M 350,278 A 54,54 0 0,1 468,278" fill="none" stroke="#334155" stroke-width="6"/>

    <!-- Massive High SUV Wheels -->
    <circle cx="174" cy="278" r="44" fill="#0f172a"/>
    <circle cx="174" cy="278" r="33" fill="#334155"/>
    <circle cx="174" cy="278" r="26" fill="#0f172a" stroke="url(#chrome)" stroke-width="5"/>
    <circle cx="174" cy="278" r="9" fill="url(#chrome)"/>

    <circle cx="409" cy="278" r="44" fill="#0f172a"/>
    <circle cx="409" cy="278" r="33" fill="#334155"/>
    <circle cx="409" cy="278" r="26" fill="#0f172a" stroke="url(#chrome)" stroke-width="5"/>
    <circle cx="409" cy="278" r="9" fill="url(#chrome)"/>

    <!-- Side Footstep Rail -->
    <rect x="238" y="280" width="108" height="6" rx="3" fill="#1e293b"/>
  </g>
</svg>
SVG;
file_put_contents($dir . '/fortuner.svg', $fortunerSvg);

// 5. Hero Composite Showcase SVG (All 3 vehicles stacked with temple)
$heroVehiclesSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 480" width="100%" height="100%">
  <!-- Mahakaleshwar Temple Silhouette in Background -->
  <g opacity="0.38" transform="translate(480, 20) scale(0.68)">
    <!-- Main Shikhara -->
    <path d="M 200,20 L 210,120 L 230,220 L 260,340 L 140,340 L 170,220 L 190,120 Z" fill="#d97706"/>
    <!-- Kalash on Top -->
    <circle cx="200" cy="15" r="8" fill="#fbbf24"/>
    <line x1="200" y1="2" x2="200" y2="15" stroke="#fbbf24" stroke-width="3"/>
    <!-- Flag / Dhwaja -->
    <polygon points="200,5 235,12 200,22" fill="#ea580c"/>
    <!-- Tier Ridges -->
    <ellipse cx="200" cy="100" rx="20" ry="5" fill="#f59e0b"/>
    <ellipse cx="200" cy="150" rx="35" ry="7" fill="#f59e0b"/>
    <ellipse cx="200" cy="210" rx="50" ry="10" fill="#f59e0b"/>
    <ellipse cx="200" cy="280" rx="70" ry="12" fill="#f59e0b"/>
    <!-- Side Shrines -->
    <path d="M 90,180 L 100,240 L 130,340 L 60,340 Z" fill="#b45309"/>
    <path d="M 310,180 L 300,240 L 270,340 L 340,340 Z" fill="#b45309"/>
  </g>

  <!-- Arrow & Annotation text: "Mahakaleshwar Ujjain ↗" -->
  <g transform="translate(450, 40)">
    <text x="0" y="0" font-family="'Caveat', 'Comic Sans MS', cursive, sans-serif" font-size="20" font-style="italic" fill="#ffffff" opacity="0.85">Mahakaleshwar</text>
    <text x="35" y="20" font-family="'Caveat', 'Comic Sans MS', cursive, sans-serif" font-size="18" font-style="italic" fill="#fbbf24" opacity="0.85">Ujjain</text>
    <path d="M 75,25 Q 95,20 115,5" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-dasharray="3,3"/>
    <polygon points="115,5 106,4 112,12" fill="#fbbf24"/>
  </g>

  <!-- Left: Force Urbania (Scale & Position) -->
  <g transform="translate(10, 110) scale(0.72)">
    $urbaniaSvg
  </g>

  <!-- Middle: Force Tempo Traveller (White) -->
  <g transform="translate(250, 95) scale(0.72)">
    $tempoSvg
  </g>

  <!-- Right: Toyota Fortuner / Innova (White) -->
  <g transform="translate(490, 130) scale(0.68)">
    $fortunerSvg
  </g>
</svg>
SVG;
file_put_contents($root . '/public/assets/images/hero-vehicles.svg', $heroVehiclesSvg);

// 6. Styled ॐ OM Symbol + Logo SVG
$omLogoSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340 68" width="100%" height="100%">
  <!-- Orange Om Icon in Circle -->
  <g transform="translate(6, 6)">
    <circle cx="28" cy="28" r="27" fill="#fff7ed" stroke="#f26522" stroke-width="2.5"/>
    <!-- Stylized Om symbol -->
    <text x="28" y="38" font-family="'Segoe UI', Arial, sans-serif" font-weight="900" font-size="28" fill="#f26522" text-anchor="middle">ॐ</text>
  </g>

  <!-- Text -->
  <text x="74" y="32" font-family="'Segoe UI', -apple-system, system-ui, sans-serif" font-weight="900" font-size="22" fill="#18283e">
    OM <tspan fill="#f26522">Trip and Travels</tspan>
  </text>
  <text x="74" y="48" font-family="'Segoe UI', -apple-system, system-ui, sans-serif" font-weight="600" font-size="9" fill="#64748b" letter-spacing="0.5">
    Tempo Traveller • Urbania • SUV • Car Rental
  </text>
</svg>
SVG;
file_put_contents($root . '/public/assets/images/logo.svg', $omLogoSvg);

echo "✅ High-fidelity SVG vehicle assets and logo generated successfully!\n";

