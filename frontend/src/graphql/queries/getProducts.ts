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
					id
					displayValue
					value
				}
				name
				type
			}
			prices {
				id
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
