const menuItems = {
    guest: [
        { href: "index.html", label: "Főoldal" },
        { href: "kepek.php", label: "Képek" },
        { href: "kapcsolat.html", label: "Kapcsolat" },
        { href: "crud.html", label: "CRUD" },
        { href: "bejelentkezes.html", label: "Belépés" }
    ],
    loggedIn: [
        { href: "index.html", label: "Főoldal" },
        { href: "kepek.php", label: "Képek" },
        { href: "kapcsolat.html", label: "Kapcsolat" },
        { href: "uzenetek.php", label: "Üzenetek" },
        { href: "crud.html", label: "CRUD" },
        { href: "logout.php", label: "Kilépés" }
    ]
};


function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function buildHeader(isLoggedIn, displayName) {
    const items = isLoggedIn ? menuItems.loggedIn : menuItems.guest;
    const userLine = isLoggedIn
        ? `<p class="user-status">Bejelentkezett: ${escapeHtml(displayName)}</p>`
        : `<p class="user-status user-status--guest">Nincs bejelentkezve</p>`;

    return `
        <header>
            <h1>Web Gyakorlati Beadandó</h1>
            <nav aria-label="Fő navigáció">
                ${items.map(item => `<a href="${item.href}">${item.label}</a>`).join('')}
            </nav>
            ${userLine}
        </header>
    `;
}

function buildFooter() {
    return `
        <footer>
            Gyurászik György Marcell - ZX4R0A | Patkós Máté - CS9R44
        </footer>
    `;
}

async function renderMenu() {
    let isLoggedIn = false;
    let displayName = "";

    try {
        const response =  fetch("auth_status.php", { cache: "no-store" });
        if (response.ok) {
            const data =  response.json();
            isLoggedIn = Boolean(data.logged_in);
            displayName = data.display_name || "";
        }
    } catch (error) {
        
    }

    document.body.insertAdjacentHTML("afterbegin", buildHeader(isLoggedIn, displayName));
    document.body.insertAdjacentHTML("beforeend", buildFooter());
}

document.addEventListener("DOMContentLoaded", renderMenu);
