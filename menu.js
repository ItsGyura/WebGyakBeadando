const fejlec=`<header>
<h1>Web Gyakorlati Beadandó</h1>
<nav>
<a href="index.html">Főoldal</a>
<a href="kepek.php">Képek</a>
<a href="kapcsolat.html">Kapcsolat</a>
<a href="Crud.html">Crud</a>
<a href="bejelentkezes.html">Bejelentkezés</a>
</nav>
</header>`;

const lab=`<footer>
Gyurászik György Marcell - ZX4R0A | Patkós Máté - CS9R44
</footer>`;

document.addEventListener("DOMContentLoaded",function(){
document.body.insertAdjacentHTML("afterbegin",fejlec);
document.body.insertAdjacentHTML("beforeend",lab);

})