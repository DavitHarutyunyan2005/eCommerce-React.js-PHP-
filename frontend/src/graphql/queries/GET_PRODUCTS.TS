import { gql } from '@apollo/client';

export const GET_PRODUCTS = gql`
	query GetProducts {
        products {
            id
            name
			inStock
			gallery
			description
			category
			attributes {
				id
				items {
					displayValue
					value
				}
				name
				type
			}
			prices {
				amount
				currency {
					label
					symbol
				}
			}
			brand
			madeFor
        }
    }
`;

export const GET_PRODUCT_BY_ID = gql`
	query GetProductById($id: String!) {
		product(id: $id) {
			id
			name
			inStock
			gallery
			description
			category
			attributes {
				id
				items {
					displayValue
					value
				}
				name
				type
			}
			prices {
				amount
				currency {
					label
					symbol
				}
			}
			brand
			madeFor
		}
	}
`;
