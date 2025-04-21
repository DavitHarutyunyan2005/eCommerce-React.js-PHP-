import React, { useEffect } from 'react';
import { useQuery, useApolloClient } from '@apollo/client';
import { GET_PRODUCTS } from '../../graphql/queries/GET_PRODUCTS';
import ProductCard from './ProductCard';
import ProductCardSkeleton from './ProductCardSkeleton';

interface ProductListingPageProps {
    category: string;
    madeFor: 'Men' | 'Women' | 'All';
}

const ProductListingPage: React.FC<ProductListingPageProps> = ({ category, madeFor }) => {
    const { data, loading, error } = useQuery(GET_PRODUCTS);
    const client = useApolloClient();

    useEffect(() => {
        client.query({
            query: GET_PRODUCTS,
        });
    }, [client]);

    const products = (data?.products ?? []).filter(
        (product: any) => product.category === category && product.madeFor === madeFor
    );

    return (
        <div className="px-4 md:px-8 lg:px-20 mt-8">
            <h1 className="text-3xl font-medium capitalize mb-8">{madeFor}</h1>

            {error ? (
                <p className="text-red-500">Error: {error.message}</p>
            ) : loading ? (
                <div className="grid grid-cols-2 md:grid-cols-3 gap-6">
                    {Array.from({ length: 6 }).map((_, i) => (
                        <ProductCardSkeleton key={i} />
                    ))}
                </div>
            ) : products.length === 0 ? (
                <p>No products found for {madeFor}.</p>
            ) : (
                <div className="grid grid-cols-2 md:grid-cols-3 gap-6">
                    {products.map((product: any) => (
                        <ProductCard key={product.id} product={product} />
                    ))}
                </div>
            )}
        </div>
    );
};

export default ProductListingPage;
