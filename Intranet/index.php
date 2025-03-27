<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Portal do Grupo Galactus - Soluções e Serviços">
  <title>Grupo Galactus - Home</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    /* Base */
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
      margin-bottom: 70px;
    }
    header {
      background: linear-gradient(90deg, #295959, #2a9d8f);
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .navbar {
      background: linear-gradient(90deg, #295959, #2a9d8f);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .navbar-brand img {
      max-height: 40px;
    }
    .navbar-title {
      font-family: 'Montserrat', sans-serif;
      font-size: 1.8rem;
      color: #ffffff;
      font-weight: 700;
      transition: color 0.3s ease, transform 0.3s ease;
    }
    .navbar-title:hover {
      color: #e0f7fa;
      transform: scale(1.05);
    }
    .navbar-title i {
      margin-right: 10px;
      font-size: 1.2rem;
      vertical-align: middle;
    }
    /* Cards Modernos */
    .card {
      position: relative;
      border: none;
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .card .icon-card {
      font-size: 3rem;
      color: #295959;
    }
    .card-body {
      padding: 2rem;
    }
    .card::after {
      content: attr(data-title);
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      background: linear-gradient(180deg, rgba(42,157,143,0.95), rgba(42,157,143,0.75));
      color: #fff;
      font-weight: 600;
      text-align: center;
      padding: 0.75rem;
      opacity: 0;
      transform: translateY(-100%);
      transition: transform 0.4s ease, opacity 0.4s ease;
      pointer-events: none;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }
    .card:hover::after {
      opacity: 1;
      transform: translateY(0);
    }
    /* Banner */
    .modern-banner {
      background: linear-gradient(90deg, #295959, #2a9d8f);
      border-radius: 12px;
      padding: 0.75rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      margin: 2rem auto;
      width: 60%;
      text-align: center;
      overflow: hidden;
    }
    .modern-banner span {
      display: inline-block;
      font-size: 1.2rem;
      font-weight: bold;
      color: #fff;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
      animation: fadeInPulse 3s ease-in-out infinite;
    }
    @keyframes fadeInPulse {
      0% { opacity: 0.6; transform: scale(1); }
      50% { opacity: 1; transform: scale(1.05); }
      100% { opacity: 0.6; transform: scale(1); }
    }
    /* Botões com transição */
    .btn-primary {
      background-color: #295959;
      border: none;
      transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #2a9d8f;
    }
    /* Rodapé fixo */
    footer {
      background-color: #295959;
      color: #fff;
      text-align: center;
      padding: 1rem 0;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
    /* Responsividade */
    @media (max-width: 768px) {
      .col { flex: 0 0 100%; }
      .modern-banner { width: 90%; }
      .modern-banner span { font-size: 1rem; }
      .navbar-title { font-size: 1.5rem; }
    }
  </style>
</head>
<body>
  <header>
    <!-- Navbar responsiva -->
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="./img/logogalactus.jpg" alt="Logo Grupo Galactus" loading="lazy">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Alternar navegação">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarContent">
          <span class="navbar-title">
            <i></i>Grupo Galactus
          </span>
          <button id="loginButton" class="btn btn-outline-light">
            <i class="bi bi-box-arrow-in-right"></i> Login
          </button>
        </div>
      </div>
    </nav>
  </header>
  
  <main class="container my-4">
    <!-- Saudação dinâmica -->
    <section class="text-center mb-4">
      <h3 id="greeting" class="fw-bold" style="color: #295959;"></h3>
      <p id="currentDateTime" class="text-secondary"></p>
    </section>
    
    <!-- Seção de Cards -->
    <section>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
        <!-- Card 01 -->
        <div class="col">
          <div class="card h-100 text-center shadow" data-title="Gestão de Chamados">
            <div class="card-body d-flex flex-column">
              <i class="bi bi-pc-display-horizontal icon-card mb-3"></i>
              <h5 class="card-title fw-bold">GLPI</h5>
              <p class="card-text flex-grow-1">Gestão integrada de chamados técnicos e suporte</p>
              <a href="https://galactus.verdanadesk.com/" class="btn btn-primary mt-auto" target="_blank">
                <i class="bi bi-pc-display-horizontal" aria-label="Acessar GLPI"></i> Acessar
              </a>
            </div>
          </div>
        </div>
        <!-- Card 02 -->
        <div class="col">
          <div class="card h-100 text-center shadow" data-title="Gestão de RH">
            <div class="card-body d-flex flex-column">
              <i class="bi bi-person-check-fill icon-card mb-3"></i>
              <h5 class="card-title fw-bold">Meu RH</h5>
              <p class="card-text flex-grow-1">Plataforma integrada de gestão de recursos humanos</p>
              <a href="https://meurhgalactus.com.br:8080/html-hcm/#/home" class="btn btn-primary mt-auto" target="_blank">
                <i class="bi bi-person-check-fill" aria-label="Acessar Meu RH"></i> Acessar
              </a>
            </div>
          </div>
        </div>
        <!-- Card 03 -->
        <div class="col">
          <div class="card h-100 text-center shadow" data-title="Diretório de Contatos">
            <div class="card-body d-flex flex-column">
              <i class="bi bi-telephone icon-card mb-3"></i>
              <h5 class="card-title fw-bold">Contatos corporativos</h5>
              <p class="card-text flex-grow-1">Diretório completo de colaboradores e departamentos</p>
              <a href="agenda.php" class="btn btn-primary mt-auto" target="_blank">
                <i class="bi bi-telephone" aria-label="Acessar Contatos corporativos"></i> Acessar
              </a>
            </div>
          </div>
        </div>
        <!-- Card 04 -->
        <div class="col">
          <div class="card h-100 text-center shadow" data-title="Aprovação de Processos">
            <div class="card-body d-flex flex-column">
              <i class="bi bi-check-circle icon-card mb-3"></i>
              <h5 class="card-title fw-bold">Aprovador</h5>
              <p class="card-text flex-grow-1">Sistema MLA Totvs (Aprovação)</p>
              <a href="https://meurhgalactus.com.br:8080/portal/#/" class="btn btn-primary mt-auto" target="_blank">
                <i class="bi bi-check-circle" aria-label="Acessar Aprovador"></i> Acessar
              </a>
            </div>
          </div>
        </div>
        <!-- Card 05 -->
        <div class="col">
          <div class="card h-100 text-center shadow" data-title="Planilhas Corporativas">
            <div class="card-body d-flex flex-column">
              <i class="bi bi-table icon-card mb-3"></i>
              <h5 class="card-title fw-bold">Planilhas</h5>
              <p class="card-text flex-grow-1">Acesse as planilhas corporativas</p>
              <a href="planilhas.php" class="btn btn-primary mt-auto" target="_blank">
                <i class="bi bi-table" aria-label="Acessar Planilhas"></i> Acessar
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Banner Moderno -->
    <section class="mt-4">
      <div class="modern-banner">
        <span>Grupo Galactus! Negócios que movimentam.</span>
      </div>
    </section>
  </main>
  
  <!-- Rodapé fixo -->
  <footer>
    © 2025 Galactus | Desenvolvido pela equipe de TI. 
    <a href="https://www.instagram.com/grupogalactus/" target="_blank" rel="noopener noreferrer" style="color: #fff; text-decoration: none;" title="Instagram do Grupo Galactus">
      <i class="bi bi-instagram"></i>
    </a> | 
    <a href="https://www.linkedin.com/company/grupogalactus" target="_blank" rel="noopener noreferrer" style="color: #fff; text-decoration: none;" title="LinkedIn do Grupo Galactus">
      <i class="bi bi-linkedin"></i>
    </a>
  </footer>
  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  
  <!-- Custom Scripts -->
  <script>
    function updateGreeting() {
      const now = new Date();
      const hour = now.getHours();
      const greetings = hour < 12 ? "Bom dia" : hour < 18 ? "Boa tarde" : "Boa noite";
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('greeting').textContent = greetings + '!';
      document.getElementById('currentDateTime').textContent = now.toLocaleDateString('pt-BR', options) + ', ' + now.toLocaleTimeString('pt-BR');
    }
    updateGreeting();
    setInterval(updateGreeting, 1000);
    document.getElementById('loginButton').addEventListener('click', function() {
      window.location.href = 'loginrh.php';
    });
  </script>
</body>
</html>