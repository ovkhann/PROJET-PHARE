const axios = require('axios');

const Axios = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        Accept: 'application/json'
    }
});

const user = {};

// ------------------
// Tests User Login
// ------------------
describe("User Login", () => {
    test("Vérification de l'authentification", async () => {
        await login(user, {
            email: 'test@example.com',
            password: 'Test123'
        });
    });
});

// Fonction de login
async function login(user, credentials) {
    // Déconnexion avant tout
    // await Axios.get('/logout', { baseURL: 'http://localhost:8000' });

    // Récupération du CSRF
    const res = await Axios.get('/sanctum/csrf-cookie', { baseURL: 'http://localhost:8000' });

    Axios.defaults.headers.cookie = res.headers['set-cookie'];
    Axios.defaults.headers.common['X-XSRF-TOKEN'] = parseCSRFToken(res.headers['set-cookie']);
    Axios.defaults.headers.common['Origin'] = 'http://localhost:8000';
    Axios.defaults.headers.common['Referer'] = 'http://localhost:8000';

    // Transformation des credentials en x-www-form-urlencoded
    const body = new URLSearchParams();
    for (let key in credentials) {
        body.append(key, credentials[key]);
    }

    const auth = await Axios.post('/authenticate', body, {
        baseURL: 'http://localhost:8000',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    });

    Axios.defaults.headers.cookie = auth.headers['set-cookie'];
    Axios.defaults.headers.common['X-XSRF-TOKEN'] = parseCSRFToken(auth.headers['set-cookie']);

    // Remplissage de l'objet user
    for (let key in auth.data.user) {
        user[key] = auth.data.user[key];
    }
}

// Fonction pour extraire le token CSRF
function parseCSRFToken(cookies) {
    const startAt = cookies[0].indexOf('=');
    const endAt = cookies[0].indexOf(';');
    return cookies[0].substring(startAt + 1, endAt - 3);
}

// ------------------
// Tests Produits
// ------------------
describe("Produits GET", () => {
    test('Récupération de la liste des produits', async () => {
        const res = await Axios.get('/products');
        const products = res.data.products || res.data.data || [];
        expect(products.length).toBeGreaterThanOrEqual(2);
    });

    // test('Get Show Product', async () => {
    //     // Récupère la liste des produits publics
    //     const productsRes = await Axios.get('/products');

    //     // Vérifie que la réponse est bien un tableau
    //     const products = Array.isArray(productsRes.data) ? productsRes.data : [];
    //     expect(products.length).toBeGreaterThan(0); // au moins 1 produit

    //     // Récupère le premier produit en détail
    //     const productId = products[0].id;
    //     const res = await Axios.get('/products/' + productId);

    //     // Vérifie que les champs essentiels existent
    //     expect(res.data).toHaveProperty('id', productId);
    //     expect(res.data).toHaveProperty('name');
    //     expect(res.data.name).toBeTruthy(); // nom non vide
    //     expect(res.data).toHaveProperty('price');
    //     expect(typeof res.data.price).toBe('number'); // le prix est un nombre
    // });


});


