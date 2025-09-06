const axios = require('axios');

const Axios = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: { Accept: 'application/json' },
});

// ------------------
// Auth
// ------------------
describe('Admin Login', () => {
    test('Vérification de l\'authentification', async () => {
        const res = await Axios.post('/authenticate', {
            email: 'admin@revolverealm.shop',
            password: 'Test123',
        });
        expect(res.status).toBe(200);
        expect(res.data.user).toHaveProperty('token');
        expect(typeof res.data.user.token).toBe('string');
    });
});


// ------------------
// Produits
// ------------------
describe('Produits', () => {

    test('GET all products', async () => {
        const res = await Axios.get('/products');
        expect(res.status).toBe(200);
        expect(Array.isArray(res.data)).toBe(true);
    });

    test('CREATE a product', async () => {
        const productData = {
            name: 'Produit Test Jest',
            price: 123,
            stock: 10,
            description: 'Description test',
            category_id: 1,
            archived: 0
        };
        const res = await Axios.post('/products', productData);
        expect(res.status).toBe(201);
    });

    test('UPDATE a product', async () => {
        const createRes = await Axios.post('/products', {
            name: 'Produit Update',
            price: 50,
            stock: 5,
            description: 'Desc Update',
            category_id: 1,
            archived: 0
        });
        const productId = createRes.data.id;

        const updateRes = await Axios.put(`/products/${productId}`, {
            name: 'Produit Updated',
            price: 75,
            stock: 10,
            description: 'Desc Updated',
            category_id: 1,
            archived: 0
        });
        expect(updateRes.status).toBe(200);
    });

});

// ------------------
// Options
// ------------------
describe('Options', () => {

    test('GET all options', async () => {
        const res = await Axios.get('/options');
        expect(res.status).toBe(200);
    });

    test('CREATE an option', async () => {
        const optionData = { size: 'L' };
        const res = await Axios.post('/options', optionData);
        expect(res.status).toBe(201);
        expect(res.data.data).toHaveProperty('size', 'L');
    });

    test('UPDATE an option', async () => {
        const createRes = await Axios.post('/options', { size: 'M' });
        const optionId = createRes.data.data.id;

        const updateRes = await Axios.put(`/options/${optionId}`, { size: 'S' });
        expect(updateRes.status).toBe(200);
        expect(updateRes.data.data).toHaveProperty('size', 'S');
    });

});

// ------------------
// Categories
// ------------------
describe('Categories', () => {

    test('GET all categories', async () => {
        const res = await Axios.get('/categories');
        expect(res.status).toBe(200);
    });

    test('CREATE a category', async () => {
        const uniqueName = `Catégorie Jest ${Date.now()}`;
        const categoryData = { name: uniqueName };

        const res = await Axios.post('/categories', categoryData);
        expect(res.status).toBe(201);
        expect(res.data.data).toHaveProperty('name', uniqueName);
    });

    test('UPDATE a category', async () => {
        const uniqueName = `Catégorie Update ${Date.now()}`;
        const createRes = await Axios.post('/categories', { name: uniqueName });
        const categoryId = createRes.data.data.id;

        const updatedName = `${uniqueName} Updated`;
        const updateRes = await Axios.put(`/categories/${categoryId}`, { name: updatedName });
        expect(updateRes.status).toBe(200);
        expect(updateRes.data.data).toHaveProperty('name', updatedName);
    });

});

