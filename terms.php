<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Syarat & Ketentuan</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --primary-blue: #0066ff;
      --secondary-blue: #4d94ff;
      --dark-blue: #003d99;
      --light-blue: #e6f2ff;
      --accent-cyan: #00d4ff;
      --gradient-1: linear-gradient(135deg, #0066ff 0%, #00d4ff 100%);
      --gradient-2: linear-gradient(135deg, #003d99 0%, #0066ff 100%);
      --glass-bg: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: linear-gradient(135deg, #001a4d 0%, #003d99 35%, #0066ff 100%);
      min-height: 100vh;
      color: #ffffff;
      overflow-x: hidden;
      position: relative;
    }

    /* Animated background elements */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 80%, rgba(0, 212, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(0, 102, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(77, 148, 255, 0.05) 0%, transparent 50%);
      animation: backgroundFlow 20s ease-in-out infinite alternate;
      z-index: -1;
    }

    @keyframes backgroundFlow {
      0% {
        transform: translateX(-50px) translateY(-50px) scale(1);
      }
      100% {
        transform: translateX(50px) translateY(50px) scale(1.1);
      }
    }

    /* Floating particles */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
    }

    .particle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: var(--accent-cyan);
      border-radius: 50%;
      opacity: 0.3;
      animation: float 15s infinite linear;
    }

    .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { left: 20%; animation-delay: 2s; }
    .particle:nth-child(3) { left: 30%; animation-delay: 4s; }
    .particle:nth-child(4) { left: 40%; animation-delay: 6s; }
    .particle:nth-child(5) { left: 50%; animation-delay: 8s; }
    .particle:nth-child(6) { left: 60%; animation-delay: 10s; }
    .particle:nth-child(7) { left: 70%; animation-delay: 12s; }
    .particle:nth-child(8) { left: 80%; animation-delay: 14s; }
    .particle:nth-child(9) { left: 90%; animation-delay: 16s; }

    @keyframes float {
      0% {
        transform: translateY(100vh) scale(0);
        opacity: 0;
      }
      10% {
        opacity: 0.3;
      }
      90% {
        opacity: 0.3;
      }
      100% {
        transform: translateY(-100px) scale(1);
        opacity: 0;
      }
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
      padding: 60px 20px;
      position: relative;
      z-index: 1;
    }

    .terms-card {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 24px;
      padding: 50px;
      box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.05);
      position: relative;
      overflow: hidden;
      animation: slideUp 0.8s ease-out;
    }

    .terms-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: var(--gradient-1);
      animation: shimmer 2s ease-in-out infinite;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes shimmer {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }

    h1 {
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 30px;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-align: center;
      position: relative;
      animation: glow 3s ease-in-out infinite alternate;
    }

    @keyframes glow {
      from {
        text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
      }
      to {
        text-shadow: 0 0 30px rgba(0, 212, 255, 0.8);
      }
    }

    h2 {
      font-size: 24px;
      font-weight: 600;
      margin: 40px 0 20px 0;
      color: var(--accent-cyan);
      display: flex;
      align-items: center;
      gap: 15px;
      position: relative;
    }

    h2::before {
      content: '';
      width: 4px;
      height: 30px;
      background: var(--gradient-1);
      border-radius: 2px;
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% {
        transform: scaleY(1);
        opacity: 1;
      }
      50% {
        transform: scaleY(1.2);
        opacity: 0.8;
      }
    }

    p {
      font-size: 16px;
      line-height: 1.8;
      margin-bottom: 15px;
      color: rgba(255, 255, 255, 0.9);
      font-weight: 400;
      text-align: justify;
    }

    .intro-text {
      font-size: 18px;
      color: rgba(255, 255, 255, 0.8);
      text-align: center;
      margin-bottom: 40px;
      padding: 25px;
      background: rgba(0, 212, 255, 0.1);
      border-radius: 16px;
      border: 1px solid rgba(0, 212, 255, 0.2);
    }

    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      margin-top: 40px;
      padding: 16px 32px;
      background: var(--gradient-1);
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 102, 255, 0.3);
    }

    .btn-back::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .btn-back:hover::before {
      left: 100%;
    }

    .btn-back:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(0, 102, 255, 0.4);
    }

    .btn-back:active {
      transform: translateY(-1px);
    }

    .arrow {
      transition: transform 0.3s ease;
    }

    .btn-back:hover .arrow {
      transform: translateX(-5px);
    }

    /* Section styling */
    .terms-section {
      margin-bottom: 35px;
      padding: 25px 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
    }

    .terms-section:last-of-type {
      border-bottom: none;
    }

    .terms-section:hover {
      background: rgba(255, 255, 255, 0.02);
      border-radius: 12px;
      padding: 25px 20px;
      margin: 0 -20px 35px -20px;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .container {
        padding: 40px 15px;
      }

      .terms-card {
        padding: 35px 25px;
        border-radius: 20px;
      }

      h1 {
        font-size: 32px;
        margin-bottom: 25px;
      }

      h2 {
        font-size: 20px;
        margin: 30px 0 15px 0;
      }

      p {
        font-size: 15px;
        line-height: 1.7;
      }

      .intro-text {
        font-size: 16px;
        padding: 20px;
      }

      .btn-back {
        padding: 14px 28px;
        font-size: 15px;
      }

      .terms-section:hover {
        margin: 0 -15px 30px -15px;
        padding: 20px 15px;
      }
    }

    @media (max-width: 480px) {
      .terms-card {
        padding: 25px 20px;
      }

      h1 {
        font-size: 28px;
      }

      h2 {
        font-size: 18px;
        gap: 10px;
      }

      h2::before {
        width: 3px;
        height: 25px;
      }
    }
  </style>
</head>
<body>
  <!-- Floating particles -->
  <div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
  </div>

  <div class="container">
    <div class="terms-card">
      <h1>Syarat dan Ketentuan</h1>
      <p class="intro-text">Dengan menggunakan layanan ini, Anda menyetujui syarat dan ketentuan berikut:</p>

      <div class="terms-section">
        <h2>1. Akses dan Penggunaan Data Sekolah</h2>
        <p>Platform ini dirancang untuk mendukung proses pendaftaran dan pengelolaan data siswa secara aman. Anda bertanggung jawab penuh atas data yang Anda masukkan dan menjamin bahwa data tersebut benar dan Anda memiliki otorisasi untuk menginputnya.</p>
      </div>

      <div class="terms-section">
        <h2>2. Keamanan Data</h2>
        <p>Kami menggunakan standar keamanan yang ketat untuk memastikan data siswa yang Anda daftarkan tetap aman, tidak bocor, dan hanya digunakan untuk keperluan yang telah disetujui.</p>
      </div>

      <div class="terms-section">
        <h2>3. Privasi</h2>
        <p>Data pribadi siswa tidak akan dibagikan kepada pihak ketiga tanpa izin. Semua data digunakan hanya untuk kepentingan administrasi dan layanan internal sekolah.</p>
      </div>

      <div class="terms-section">
        <h2>4. Kewajiban Pengguna</h2>
        <p>Anda dilarang menggunakan platform ini untuk tujuan yang melanggar hukum, merugikan pihak lain, atau menyalahgunakan sistem yang tersedia.</p>
      </div>

      <div class="terms-section">
        <h2>5. Perubahan Ketentuan</h2>
        <p>Kami berhak memperbarui syarat dan ketentuan ini sewaktu-waktu. Silakan periksa halaman ini secara berkala untuk mengetahui perubahan yang berlaku.</p>
      </div>

      <a href="index.php" class="btn-back">
        <span class="arrow">←</span>
        Kembali ke Halaman Login
      </a>
    </div>
  </div>
</body>
</html>