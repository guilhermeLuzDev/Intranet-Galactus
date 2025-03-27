// Saudação dinâmica
function updateGreeting() {
    const now = new Date();
    const hour = now.getHours();
    const greetings = hour < 12 ? "Bom dia" : hour < 18 ? "Boa tarde" : "Boa noite";
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('greeting').textContent = greetings + '!';
    document.getElementById('currentDateTime').textContent = now.toLocaleDateString('pt-BR', options) + ', ' + now.toLocaleTimeString('pt-BR');

    // Exibir modal ao carregar a página
    $('#welcomeModal').modal('show');
}

function entrar() {
    window.location.href = 'pagina-especifica.html';
}

// Letreiro com efeito
const marqueeText = "Grupo Galactus. Negócios que movimentam!";
const marqueeElement = document.querySelector('.text-marquee');
let index = 0;

setInterval(() => {
    marqueeElement.textContent = marqueeText.slice(0, ++index);
    if (index === marqueeText[_{{{CITATION{{{_1{](https://github.com/AAndreLuis-dev/HTML-CSS_CursoEmVideo/tree/6428913bc9fbaa312d2cdade3550496520635d65/modulo-01%2Fmodulo-1%28d%29%2Fd002%2FREADME.md)[_{{{CITATION{{{_2{](https://github.com/lgfranco22/blog/tree/2ff765f5547038ea91aa40671858d9fd9d5ffb28/entrar.php)[_{{{CITATION{{{_3{](https://github.com/shadoworker5/e-Carnet-de-sante/tree/c19218909b49e08bacb9d70ae11ac224c04e4c19/resources%2Fviews%2Fofflines%2Foffline_show.blade.php)[_{{{CITATION{{{_4{](https://github.com/Arikstrong06/CaturPerkasaLand/tree/92a87352f828905839951a42f2983a585c13d3e7/property.php)[_{{{CITATION{{{_5{](https://github.com/cuelholima/app_bereishit/tree/c6ae9a410f8dfdfa351a019e1d0d38e06116355b/resources%2Fviews%2Fdivisions%2Fcontent%2Fketuvim%2Fcontent%2Fbooks%2Fshow.blade.php)[_{{{CITATION{{{_6{](https://github.com/maylonho/siteVendas/tree/7e4fed0631d4297050a3299a2f109b578acf16ae/pages%2FlistDeve.php)[_{{{CITATION{{{_7{](https://github.com/rkustas/techarticles/tree/598d65a5c026d9ecd3c7f0258f4a44f93e7029d0/client%2Fpages%2F_document.js)