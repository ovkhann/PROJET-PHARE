// _services/ProductService.ts
import Axios from './CallerService';
import type { Product } from '@/_models/Product';

export async function getCreatures(): Promise<Product[]> {
  const res = await Axios.get('/products');
  return res.data;
}

export async function getCreature(id: number): Promise<Product> {
  const res = await Axios.get('/products/' + id);
  return res.data;
}

export async function createCreature(creature: Product): Promise<Product> {
  const res = await Axios.post('/products', creature, {
    headers: { 'Content-Type': 'multipart/form-data' }
  });
  
  return res.data;
}

export async function updateCreature(creature: Product): Promise<any> {
  const res = await Axios.post('/products/' + creature.id, { ...creature, _method: 'PUT' }, {
    headers: { 'Content-Type': 'multipart/form-data' }
  });
  
  return res.data;
}

export async function deleteCreature(id: number): Promise<any> {
  return await Axios.delete('/products/' + id);
}