export interface Product {
    id: number;
    name: string;
    price: number;
    stock?: number;
    description?: string;
    archived?: number;
    option_id?: number;
    category_id?: number;
    picture?: string;
}