
// Variables we send:
export interface InsertOrderVariables {
    products: {
        productId: string;
        priceId: string;
        attributeItemIds?: string[];
        quantity: number;
    }[]; 
}

// Response we expect:
export interface InsertOrderResponse {
    insertOrder: {
        success: boolean;
        orderRef: string;
        message: string;
        products: {
            productId: string;
            priceId: string;
            attributeItemIds: string[];
            quantity: number;
        }[];
    };
}
