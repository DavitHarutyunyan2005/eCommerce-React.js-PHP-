import { gql, useMutation } from '@apollo/client';
import { InsertOrderVariables, InsertOrderResponse } from '../../types/insertOrder';

const INSERT_ORDER = gql`
    mutation InsertOrder($products: [OrderProductInput!]!) {
        insertOrder(products: $products) {
        success
        orderRef
        message
        products {
            productId
            priceId
            attributeItemIds
            quantity
            }
        }
    }
`;

export const useInsertOrder = () => {
    return useMutation<InsertOrderResponse, InsertOrderVariables>(INSERT_ORDER);
};
