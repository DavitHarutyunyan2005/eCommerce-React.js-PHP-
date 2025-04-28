import { Product } from "./ProductType";

export interface OrderProduct extends Product  {
  orderItemId: number;
  quantity: number;
}