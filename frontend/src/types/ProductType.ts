export interface Product {
  id: string;
  name: string;
  inStock: boolean;
  gallery: string[];
  description: string;
  category: string;
  attributes: {
    id: string;
    name: string;
    type: string;
    items: { id: string; value: string; displayValue: string, selected?: boolean }[];
  }[];
  prices: {
    id: string;
    amount: number;
    currency: {
      label: string;
      symbol: string;
    };
  }[];
  brand: string;
  madeFor: "men" | "women" | "kids" | "all";
}
